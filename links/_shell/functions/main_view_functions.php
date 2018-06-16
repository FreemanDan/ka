<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//доп функи для отображения каталога.

//====================================

function link_resort_db_in_column ($db, $column_count)
{
//функция рассортирует массив $db таким образом, что получатся колонки (разложит по строчкам)
//массив $db имеет структуру результата из базы.
//$column_count под сколько колонок рассортироовываем.
//колонки и строчки нумеруются с 0
$ex=array();

$in_column=ceil (count($db)/$column_count);

for ($i=0; $i<$in_column; $i++)
{
$kursor=$i;
while ($kursor<count($db)) 
{
$ex[$i][]=$db[$kursor];
$kursor+=$in_column;
}

}

return $ex;
}
//====================================


function get_link_cat_list ($link_cat_id)
{
//функция возвращает минилист каталогов и подсвечивает каталог $link_cat_id

$link_cat_id=intval($link_cat_id);

$ex='';

$tpl_db=array();	//загребаем шаблоны
$tpl_db['cat_string']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/cat_string.tpl'));	//получаем шаблон
$tpl_db['cat_string_sel']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/cat_string_sel.tpl'));	//получаем шаблон


//получаем все включенные каталоги и количество включенных ссылок в них
$links_cat_db=SI_sql_query("select 
	".Base_Prefix."links_category.id, 
	".Base_Prefix."links_category.FIELD_NAME,
	SUM(".Base_Prefix."links_items.FIELD_ENABLE) AS LINKS_COUNT
	from ".Base_Prefix."links_category 
	LEFT JOIN ".Base_Prefix."links_items ON ".Base_Prefix."links_items.FIELD_LINK_CAT=".Base_Prefix."links_category.id
	WHERE 	".Base_Prefix."links_category.FIELD_ENABLE='1' 
	GROUP BY ".Base_Prefix."links_category.id
	ORDER BY ".Base_Prefix."links_category.FIELD_SORT ASC
	");

for ($i=0; $i<count($links_cat_db); $i++)
{
//выправляем сколько активных ссылок в каталоге.
$links_cat_db[$i]['LINKS_COUNT']=intval($links_cat_db[$i]['LINKS_COUNT']);

//создаём ссылку в этот каталог.
$links_cat_db[$i]['LINK']=links_get_url ('cat', $links_cat_db[$i]['id'], '');

//определяем, какой шаблон используем.
$use_tpl='cat_string';
if ($link_cat_id==$links_cat_db[$i]['id']) $use_tpl='cat_string_sel';

$ex.=si_field_replace ($links_cat_db[$i], $tpl_db[$use_tpl]);	//через шаблон строки добавляем надпись
}

return $ex;
}

//====================================

function links_modify_db($links_items_db)
{
//функция модифицирует данные о ссылки для подстановк в шаблон.

//если адрес картинки неизвестен, то используем картинку default_pic.gif из используемого шаблона.
if (!$links_items_db['FIELD_IMG_ADDR']) $links_items_db['FIELD_IMG_ADDR']='http://'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'tpl/'.Use_Template.'/img/default_pic.gif';

//вставляем картинку в хтмл код
$links_items_db['FIELD_IMG_ADDR']='<a href="http://'.lecho($links_items_db['FIELD_DOMAIN']).'" target="_blank"><img src="'.$links_items_db['FIELD_IMG_ADDR'].'" border="0" width="88" height="31" alt="'.get_only_text($links_items_db['FIELD_NAME']).'"></a>';

return $links_items_db;
}
//====================================

function links_get_meta ($key, $name)
{
//функция запрашивает метаданные для ключа $key. Если записи с таким ключом ещё нет, то создаёт запись.
//т.е. для создания новой записи нужно всеголишь запросить её, после чего её можно будет заполнить из админки в разделе "мета данные".
//$key - ключ. не более 16 символов.
//$name - человеческое название записи. Оно нужно при создании новой записи. Для последующих запросов они не нужно.
//если новой записи нет, и $name пустой, то запсь создана НЕБУДЕТ!
//дополнительным параметром может быть передан массив
//$meta_db=array();
//$meta_db['TITLE'] - заголовок html документа
//$meta_db['KEYWORDS'] - ключевые слова html документа
//$meta_db['DESCRIPTION'] - описание html документа
//$meta_db['PAGE_NAME'] - название страницы
//$вообще то, по умолчанию на основные разделы записи создаются при инсталляции таблиц.

//получаем запись по ключу.
$ex_db=SI_sql_query("select 
	FIELD_PAGE_NAME AS PAGE_NAME,
	FIELD_TITLE AS TITLE,
	FIELD_KEYWORDS AS KEYWORDS,
	FIELD_DESCRIPTION AS DESCRIPTION
	from ".Base_Prefix."links_meta 
	WHERE FIELD_KEY='".adds ($key)."'
	");

//если запись с таким ключом есть - возвращаем.
if ($ex_db) return $ex_db[0];

//записи нет. создаём.
$meta_db=array();
if (!eto_ne_pusto($name)) return $meta_db;		//$name пустой.. запись не создаётся.

if (func_num_args()==3) $meta_db=func_get_arg (2);
SI_sql_query("insert into ".Base_Prefix."links_meta 
	(
	FIELD_KEY,
	FIELD_NAME,
	FIELD_PAGE_NAME,
	FIELD_TITLE,
	FIELD_KEYWORDS,
	FIELD_DESCRIPTION
	) 
	values(
	'".adds($key)."',
	'".adds($name)."',
	'".adds($meta_db['PAGE_NAME'])."',
	'".adds($meta_db['TITLE'])."',
	'".adds($meta_db['KEYWORDS'])."',
	'".adds($meta_db['DESCRIPTION'])."'
	)");

//ну и через маленькую рекурсию получаем результат.
return links_get_meta ($key, '');

}
//====================================

?>