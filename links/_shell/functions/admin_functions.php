<?
//дополнительные функции для работы в админке.
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

function empty_table ( ) {
//функция выводит пустую невидимую таблицу для вставки между элементами сайта
echo '<table border="0" width="4" height="4" cellspacing="0" cellpadding="0"><tr><td></td></tr></table>';
}
//========================================


function instrument_name ($name) 
{
//функция выводит в рамочке название инструмента (добавление нвовсти, и пр.)
?>
<div class="instrument_name"><?=lecho ($name) ?></div>
<?
}
//========================================

function get_admin_page_bar_settings ()
{
//функция вовзращает настройки для отображения page bar для админки.
$view_bar_params_db=array();
//количество номеров страниц в группе
//т.е. если выбрана страница 1 то: [>1] [2] [3] [4] [5]
//т.е. если выбрана страница 4 то: [1] [2] [3] [>4] [5]
//т.е. если выбрана страница 7 то: [6] [>7] [8] [9] [10]
$view_bar_params_db['numbers_in_bar']=15;
//Если return_empty=true - если страница 1 или нет вообще, то функция возвартит '' 
//Если return_empty=false, то функция вовзарит пустой блок с надписью empty_mesage
$view_bar_params_db['return_empty']=false;	
$view_bar_params_db['empty_mesage']='Нет записей';		//если return_empty=false и нет страниц то вместо страниц возвращаем это поле. (например "Нет записей")
//обозначения переходов по группам и страницам.
$view_bar_params_db['buttons']['first_page']='<img src="'.Global_WWW_Path.'_admin/icons/page_bar_first.gif" width="24" height="17" alt="Первая страница" border="0">';
$view_bar_params_db['buttons']['preview_group']='<img src="'.Global_WWW_Path.'_admin/icons/page_bar_preview_group.gif" width="17" height="17" alt="Предыдущие '.$view_bar_params_db['numbers_in_bar'].' страниц" border="0">';
$view_bar_params_db['buttons']['preview_page']='<img src="'.Global_WWW_Path.'_admin/icons/page_bar_preview.gif" width="17" height="17" alt="Предыдущая страница" border="0">';
$view_bar_params_db['buttons']['next_page']='<img src="'.Global_WWW_Path.'_admin/icons/page_bar_next.gif" width="17" height="17" alt="Следующая страница" border="0">';
$view_bar_params_db['buttons']['next_group']='<img src="'.Global_WWW_Path.'_admin/icons/page_bar_next_group.gif" width="17" height="17" alt="Следующие '.$view_bar_params_db['numbers_in_bar'].' страниц" border="0">';
$view_bar_params_db['buttons']['last_page']='<img src="'.Global_WWW_Path.'_admin/icons/page_bar_last.gif" 	width="24" height="17" alt="Последняя страница ({total_pages})" border="0">';
//определяем шаблоны отображения.
$view_bar_params_db['templates']['bar']='
<table width="100%" border="0" cellspacing="1" cellpadding="2" class="maintable">
 <tr class="middletitle">
  <td align="center">
<table width="40%" border="0" cellspacing="0" cellpadding="0" align="center"> 
<tr>
 <td width="24">{first_page}</a></td>
 <td width="17">{preview_group}</td>
 <td width="17">{preview_page}</td>
 <td nowrap><div class="page_bar_center" nowrap align="center">{page_bar}</div></td>
 <td width="17">{next_page}</td>
 <td width="17">{next_group}</td>
 <td width="24">{last_page}</td>
 <td width="15" align="right"><span class="normal_green" 
 title="Страниц: {total_pages} 
Записей: {total_records} 
На странице: {max_on_page}"><a href="#"><b>?</b></a></span></td>
</tr> 
</table> 
  </td>
 </tr>
</table>
';
//шаблоны для номеров страниц
$view_bar_params_db['templates']['select_page']=' <span class="normal_red"><a href="{link_to_page}">{number}</a></span> ';	//выбранная
$view_bar_params_db['templates']['no_select_page']=' <span class="normal_blue"><a href="{link_to_page}">{number}</a></span> ';	//невыбранная
//шаблон вывода номера
//{num} - номер страницы. (после передачи через шаблоны select_page или no_select_page)
//т.е. если надо вынести [] за ссылки, то их над перенести в шабоны select_page и no_select_page
//{start} - номер первой записи на этой странице
//{end} - номер последней записи на этой странице
$view_bar_params_db['templates']['number_page']='<span title="{start}-{end}">[{num}]</span>';

return $view_bar_params_db;
}
//========================================

function get_tabs ()
{
//функция возвращает строку для вставки между таблицами, что бы был зазор в несколько пикселей.. 
//ничего умнее не придумал, как вставлять пустую таблицу
return '<table border=0 cellspacing="1"><tr><td></td></tr></table>';
}


function init_page ($actions, $page)
{
//функция возвращает номер страницы для этой операции..
//clear - комманда на сброс страницы
global $id;

//если это редактирование страниц, то добавляем id страницы, которую редактируем.
$page_sess_key=$actions.'_pg';
if ($actions=='edit_pages') $page_sess_key=$actions.'_'.$id.'_pg';

if ($page=='clear')  $_SESSION[$page_sess_key]='';
		else if (intval ($page)>0) $_SESSION[$page_sess_key]=$page;

$page=intval ($_SESSION[$page_sess_key]);
if ($page==0) $page=1;
return $page;
}
//========================================

function get_admin_actions ()
{
//функция возвращает массив действий
//в поле $ex['full_data'] записываем полную полученную инфу.
$ex=array ();
$full_data=array();	//массив в который записываем данные "как есть", а не только разделы actions
$num=0;
$dir_db=ReadFolder(Root_Dir.'_admin');

for ($i=0; $i<count ($dir_db); $i++)
if (is_dir(Root_Dir.'_admin/'.$dir_db[$i]))
if (file_exists(Root_Dir.'_admin/'.$dir_db[$i].'/actions.php') )
{
$actions_info = parse_ini_file(Root_Dir.'_admin/'.$dir_db[$i].'/actions.php', true);	//получаем данные с опцией разбивки на секции

//собираем все данные в один массив
if ($actions_info['actions']) $ex=array_merge ($ex, $actions_info['actions']);

$full_data[]=$actions_info;	//добавляем полную инфу.
}

$ex['full_data']=$full_data;

return $ex;
}
//========================================


function get_admin_menu ()
{
//функция возвращает массив менюшек для адмики
//формат менюшки:
//$menu[n]['sort'] 	- индекст сортировки
//$menu[n]['name'] 	- название пункта меню
//$menu[n]['link'] 	- ссылка для этого пункта
$ex=array ();
$num=0;
$dir_db=ReadFolder(Root_Dir.'_admin');
for ($i=0; $i<count ($dir_db); $i++)
if (is_dir(Root_Dir.'_admin/'.$dir_db[$i]))
if (file_exists(Root_Dir.'_admin/'.$dir_db[$i].'/admin_menu.php') )
{
$menu_info = parse_ini_file(Root_Dir.'_admin/'.$dir_db[$i].'/admin_menu.php');
//собираем все данные в один массив
if ($menu_info) $ex[count($ex)]= $menu_info;
}
return $ex;
}
//========================================


function ReadFolder($catalog) 
{
//чтение каталога
$dirlist=array();
if ($dir = @opendir($catalog)) 
{ 
 while (($file = readdir($dir)) !== false) 
 { 
  if ($file != '..' && $file != '.') $dirlist[]= $file;
 }             
 closedir($dir); 
}

return $dirlist;
}
//=====================================================

function si_message_box ($title, $message, $choise, $status, $width)
{
//функция возвращает окно с сообщением.
//$title - название окна.
//$message - текст сообщения

//$choise - варианты для выбора
//структура вариантов:
//$choise[n]['name'] - название варианта
//$choise[n]['link'] - ссылка для этого варианта

//$status - статус окна. 0 - зелёное окно 1 - красное. 2 - серое, 3 - миние
//$width - ширина окна.
//дополнительно функции может передавать 6-ой параметр.
//значения доп. параметра:
//no_up_otstup - невыводим отступ сверху диалога.

$spec_param='';	//дополнительный параметр
if (func_num_args()==6) $spec_param=func_get_arg (5);

$ex='';
//создаём варианты.
$choise_text='<table border="0" width="100%" cellpadding="3">';
$choise_key=array_keys ($choise);
for ($i=0; $i<count($choise_key); $i++) 
$choise_text.='
<tr>
 <td width="20" align="center"><img src="'.Global_WWW_Path.'_admin/icons/message_choise.gif" alt=""></td>
 <td align="left"><a href="'.$choise[$choise_key[$i]]['link'].'">'.$choise[$choise_key[$i]]['name'].'</a></td>
</tr>
';
$choise_text.='</table>';

//стиль сообщение
$fieldset_style='good_fieldset';
if ($status==1) $fieldset_style='alert_fieldset';
if ($status==2) $fieldset_style='help_fieldset';
if ($status==3) $fieldset_style='normal_fieldset';

$message_style='normal_green';
if ($status==1) $message_style='normal_red';
if ($status==2) $message_style='help';
if ($status==3) $message_style='normal_blue';

//если в доп параметре запрещён отсутп сверху.
if ($spec_param<>'no_up_otstup') $ex.='<br><br><br><br><br><br><br><br><br><br>';

$ex.='<div align="center">
<fieldset class="'.$fieldset_style.'" style="width:'.$width.'; text-align:left;"><legend>'.$title.'</legend>
<div class="'.$message_style.'"><b>'.$message.'</b></div>
'.$choise_text.'
</fieldset>
';

$ex.='</div>';
return $ex;
}
//========================================

function si_admin_get_enable ($enable, $ret_type)
{
//функция возращает On или Off в зависимости от статуса $enable
//$ret_type - тип значений.
//0 - тектсовые надписи.
//1 - картиночки.

$ret_type_db=array();
$ret_type_db[0][0]='<span class="normal_red" style="width:23px;"><b>Off</b></span>';
$ret_type_db[0][1]='<span class="normal_green" style="width:23px;"><b>On</b></span>';

$ret_type_db[1][0]='<img src="/_admin/icons/off.gif" border="0" alt="Off" border="0">';
$ret_type_db[1][1]='<img src="/_admin/icons/on.gif" border="0" alt="On" border="0">';

return $ret_type_db[$ret_type][$enable];
}
//========================================

function get_admin_menun ($menun_db)
{
//функция генерит страницу-меню из описания $menun_db.
//формат описания:
//$menun_db['name']	- название меню
//$menun_db['items'][0]['name']		- название пункта меню
//$menun_db['items'][0]['desc']		- описание пункта
//$menun_db['items'][0]['link']		- ссылка на этом пункте
//$menun_db['items'][0]['img']		- картинка для пункта

$tpl_db=array();
$tpl_db['global']=clear_garbage_tpl (file_get_contents (Root_Dir.'_shell/sys_tpl/admin/menun/global.tpl'));	//получаем шаблон
$tpl_db['items']=clear_garbage_tpl (file_get_contents (Root_Dir.'_shell/sys_tpl/admin/menun/items.tpl'));	//получаем шаблон

$global_db=array();
$global_db['MENU_NAME']=$menun_db['name'];
$global_db['MENU_ITEMS']='';

//перебираем пункты меню
foreach ($menun_db['items'] as $key => $value)
{
//подготавливаем данные.
$items_db=array();
$items_db['HREF']=$value['link'];
$items_db['IMG']='<img src="'.$value['img'].'" border="0" title="'.get_only_text($value['name']).'">';
$items_db['NAME']=$value['name'];
$items_db['DESC']=$value['desc'];
$global_db['MENU_ITEMS'].=si_field_replace ($items_db, $tpl_db['items']);	//добавляем строку.
}

return si_field_replace ($global_db, $tpl_db['global']);
}
//=====================================================

function verify_admin ()
{
//функция проверяет.. админ ли это? А прошёл ли он авторизацию?
global $system_db;

$last_act=intval (si_get_session (Base_Prefix.'admin_act_time'));	//получаем время последнего действия.

//если время последнего действия неопределено или просрочено, то возвращаем false
if ($last_act<$system_db['THIS_TIME']-Life_Time_Admin_Sess) return false;

//если записанный хэш в сессию логина и пароля несовпадают, то возвращаем false
if (si_get_session (Base_Prefix.'admin_hash')!=md5(Admin_Login.Admin_Password)) return false;

//нуу.. всё нормально.. возвращаем true.
return true;
}
//=====================================================

?>