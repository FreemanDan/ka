<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//список категорий ссылок на главной странице каталога

//получаемые переменные:
//$id - ид подраздела меню к кторому привязан этот каталог.
//this_cat_db - база этого элемента меню
//$elem_db - информация этого элемента контента

//результат выполнения загружается в $page_stream_db['CONTENT']
//$mod_rewrite_path_db - последовательность параметров в массиве.

$main_page_db=array();	//сюда складываем данные для глобального шаблона
$main_page_db['BIG_CAT_LIST']='';	//сформированный список каталогов


$tpl_db=array();	//загребаем шаблоны
$tpl_db['main_page']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/main_page.tpl'));	//получаем шаблон
$tpl_db['cat_string']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/cat_string.tpl'));	//получаем шаблон
$tpl_db['main_page_cat_table']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/main_page_cat_table.tpl'));	//получаем шаблон
$tpl_db['main_page_cat_string']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/main_page_cat_string.tpl'));	//получаем шаблон
$tpl_db['main_page_cat_cell']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/main_page_cat_cell.tpl'));	//получаем шаблон


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
	
//раскладываем в вид удобный для формирования колонок. По умолчанию раскладываем на 3 колонки.
$links_cat_db=link_resort_db_in_column ($links_cat_db, 3);


//собираем по колонкам.
$table_db=array();
$table_db['STRINGS']='';
for ($i=0; $i<count($links_cat_db); $i++)
{  
//сбираем таблицу из кусочков..маетно.
$string_db=array();
$string_db['CELLS']='';

 for ($z=0; $z<count($links_cat_db[$i]); $z++) 
  {
   
   //дописываем необходимые данные в информацию о разделе.
   
   $links_cat_db[$i][$z]['LINKS_COUNT']=intval($links_cat_db[$i][$z]['LINKS_COUNT']);
   
   $links_cat_db[$i][$z]['LINK']=links_get_url ('cat', $links_cat_db[$i][$z]['id'], '');
   //данные по ячейки с каталогом загружаем в cat_string.tpl и потом загружаем main_page_cat_cell.tpl
   
   //массив с данными по ячейке таблицы. Хочу отметить, что сам контент таблицы загружается в cat_string
   //вообще вся эгра с шаблонами как с матрёшками только ради раскладки на несколько красивых колонок в таблице.
   //когда список каталогов выводится в ссылках, то он строится значительно проще.
   $cell_db=array();
   $cell_db['CONT']=si_field_replace ($links_cat_db[$i][$z], $tpl_db['cat_string']);
   $string_db['CELLS'].=si_field_replace ($cell_db, $tpl_db['main_page_cat_cell']);
  }

//собрав 3 ячейки выгражаем данные в шаблон строки.
$table_db['STRINGS'].=si_field_replace ($string_db, $tpl_db['main_page_cat_string']);
}

//выгружаем собранную таблику в массив.
$main_page_db['BIG_CAT_LIST']=si_field_replace ($table_db, $tpl_db['main_page_cat_table']);


//загружаем в поток.
$page_stream_db['CONTENT'].=si_field_replace ($main_page_db, $tpl_db['main_page']);

?>