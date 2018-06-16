<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//скрипт построения системной менюхи.
//системная менюха загружается в $page_stream_db['SYSTEM_MENU']

//описываем пункты менюшки.
$num=0;

$system_menu_db=array();
$system_menu_db[$num]['ACTIONS']='';
$system_menu_db[$num]['HREF']=links_get_url ($system_menu_db[$num]['ACTIONS'], '', '');
$system_menu_db[$num]['TITLE']='Перейти на главную страницу каталога';
$system_menu_db[$num++]['NAME']='Главная';

$system_menu_db[$num]['ACTIONS']='add';
$system_menu_db[$num]['HREF']=links_get_url ($system_menu_db[$num]['ACTIONS'], '', '');
$system_menu_db[$num]['TITLE']='Добавить новую ссылку в каталог';
$system_menu_db[$num++]['NAME']='Добавить ссылку';

$system_menu_db[$num]['ACTIONS']='edit';
$system_menu_db[$num]['HREF']=links_get_url ($system_menu_db[$num]['ACTIONS'], '', '');
$system_menu_db[$num]['TITLE']='Редактировать ранее добавленную ссылку в каталоге';
$system_menu_db[$num++]['NAME']='Редактировать ссылку';

$system_menu_db[$num]['ACTIONS']='message';
$system_menu_db[$num]['HREF']=links_get_url ($system_menu_db[$num]['ACTIONS'], '', '');
$system_menu_db[$num]['TITLE']='Отправить сообщение администратру каталога ссылок';
$system_menu_db[$num++]['NAME']='Отправить сообщение';

$tpl_db=array();
$tpl_db['system_menu']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/system_menu.tpl'));	//получаем шаблон
$tpl_db['system_menu_no_sel']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/system_menu_no_sel.tpl'));	//получаем шаблон
$tpl_db['system_menu_sel']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/system_menu_sel.tpl'));	//получаем шаблон

//перебираем все пункты меню.
$MENU='';
for ($i=0; $i<count($system_menu_db); $i++)
{
//выбираем нужный шаблон (выбранный/невыбранный)
$use_tpl='system_menu_no_sel';
if ($actions==$system_menu_db[$i]['ACTIONS']) $use_tpl='system_menu_sel';

$MENU.=si_field_replace ($system_menu_db[$i], $tpl_db[$use_tpl]);
}

//выводим в поток менюху.
$page_stream_db['SYSTEM_MENU']=si_field_replace (array('MENU'=>$MENU), $tpl_db['system_menu']);
?>
