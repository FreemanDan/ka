<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//завершение работы сайта
//этот кусок вызывается в функции выхода.. поэтому приходится определять все перменные, которые ему там понадобятся
global $base_connect, $page_stream_db, $system_db, $links_settings_db;


//закрытие соединений к мускулу
if ($base_connect) mysql_close($base_connect); 

//если были загруженные данные в поток, то выводим поток в шаблон.
//выводим данные из $page_stream_db в шаблон tpl/global.tpl и выбрасываем пользоватлю.
if ($page_stream_db) 
{

$integrate_in_page_tpl='';		//страница в которую интегрируемся.
//определён адрес страницы в которую интегрируемся.
//читаем страницу
if (defined ('Integrate_In_Page') && eto_ne_pusto (Integrate_In_Page)) $integrate_in_page_tpl=file_get_contents (Integrate_In_Page);

//---------
//подсчитыаем время выполнения. (всётаки хочется получать время как можно более полное при интеграции в дизайн. т.е. вместе в временем выполнения и получения страницы в которую интегрируемся.)

//сколько запросов было
$page_stream_db['QUERIES_USED']=count($system_db['SQL_EXEC_QUERY_DB']);

//сколько времени выполнялись запросы
$page_stream_db['QUERIES_EXEC_TIME']=number_format(array_sum($system_db['SQL_EXEC_QUERY_DB']), 3, '.', '');

//общее время выполнения
$page_stream_db['TOTAL_EXEC_TIME']=microtime_exec(getmicrotime($FL_start_microtime), getmicrotime(), 3); //время выполнения до 3х знаков после запятой
//---------

if (!eto_ne_pusto($integrate_in_page_tpl)) echo si_ff_replace ($page_stream_db, Root_Dir.'tpl/'.Use_Template.'/global.tpl', 1);	//нет интеграйии в страницу. Используем простой вывод.
else
 {
 //определёна интеграции. делаем двойную подстановку.
 //сначала в глобальный шаблон и в поле $page_stream_db['FAIRLINKS_HERE']
 //а затем все данные в страницу, которая получена в $integrate_in_page_tpl
 //это нужно, чтобы в одном массиве был уже и польный результат и первичные данные.
 $page_stream_db['FAIRLINKS_HERE']=si_ff_replace ($page_stream_db, Root_Dir.'tpl/'.Use_Template.'/global.tpl', 1);
 echo si_field_replace ($page_stream_db, $integrate_in_page_tpl);
 }

}

//выкидываем буффер, если была включена буффферизация.
if (In_Buffer=='On') ob_end_flush();

//Да здравствуют роботы! Слава роботам!!!
?>