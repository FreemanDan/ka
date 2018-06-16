<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//список ссылок в категории 

//получаемые переменные:
//$actions - действие. В данном случае это "cat"
//$id - ид каталога который открываем.
//$page - порядковая страница

include_once (Root_Dir.'_shell/functions/page_bar_funtions.php');			//загрузка функций для управления страницами.

$tpl_db=array();	//загребаем шаблоны
$tpl_db['cat']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/cat.tpl'));	//получаем шаблон
$tpl_db['link_list_string']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/link_list_string.tpl'));	//получаем шаблон


$global_db=array();	//сюда собираем данные для глобальной формы.
$global_db['LINK_LIST']='';
$global_db['CAT_LIST']='';
$global_db['PAGES_BAR']='';

$global_db['CAT_LIST']=get_link_cat_list ($id);		//получаем мини лист каталогов

//страница.
if ($page<=0) $page=1;

//читаем каталог каторый надо открыть.
$links_cat_db=SI_sql_query("select * from ".Base_Prefix."links_category WHERE id='$id' AND FIELD_ENABLE='1'");

if (!$links_cat_db) si_get_404_page ();	//если каталога несущесвует, то нафих убиваем вывод.


//получаем сколько записей
$total_count=SI_sql_query("select count(id) AS TCNT from ".Base_Prefix."links_items WHERE FIELD_LINK_CAT='$id' AND FIELD_ENABLE='1'");
$total_count=$total_count[0]['TCNT'];

//получаем список ссылок в зависимости от страницы.
$limit_str=pages_get_lim_str ($links_settings_db['REC_ON_PAGE'], $page); //получаем строку для лимита
$links_items_db=SI_sql_query("select * from ".Base_Prefix."links_items WHERE FIELD_LINK_CAT='$id' AND FIELD_ENABLE='1' ORDER BY FIELD_SORT ASC ".$limit_str);

//-----
//формируем список ссылок
for ($i=0; $i<count($links_items_db); $i++)
$global_db['LINK_LIST'].=si_field_replace (links_modify_db($links_items_db[$i]), $tpl_db['link_list_string']);
//-----


//формируем список страниц
$global_db['PAGES_BAR']=get_pages_bar ($total_count, $links_settings_db['REC_ON_PAGE'], $page, links_get_url ('cat', $id, '{page_num}'));

//загружаем в поток.
$page_stream_db['CONTENT'].=si_field_replace ($global_db, $tpl_db['cat']);

//-----------------------
//переопределяем МЕТА данные для этого каталога.

//выставляем значения "по умолчанию"
$page_stream_db['TITLE']=$links_cat_db[0]['FIELD_NAME'];
$page_stream_db['PAGE_NAME']=$links_cat_db[0]['FIELD_NAME'];

//переопределяем, если заданы специальные в настройках категории.
if (eto_ne_pusto($links_cat_db[0]['FIELD_META_TITLE'])) $page_stream_db['TITLE']=$links_cat_db[0]['FIELD_META_TITLE'];
if (eto_ne_pusto($links_cat_db[0]['FIELD_PAGE_NAME'])) $page_stream_db['PAGE_NAME']=$links_cat_db[0]['FIELD_PAGE_NAME'];
$page_stream_db['KEYWORDS']=$links_cat_db[0]['FIELD_META_KEYWORDS'];
$page_stream_db['DESCRIPTION']=$links_cat_db[0]['FIELD_META_DESCRIPTION'];
//-----------------------


?>