<?
//комплект функций для диалога проверки ссылки.
//фактичемки базовые функи.

function links_get_fast_info ($item_db, $links_settings_db)
{
//функция возвращает информационный блок для ссылки $item_db
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.

$link_page_addr=links_get_link_addr ($item_db, $links_settings_db);	//получаем ссылку на страницу, где расположена эта ссылка.

$ex='<div style="margin-top:4px;margin-bottom:4px;"><b>Адрес сайта:</b> <a href="http://'.lecho ($item_db['FIELD_DOMAIN']).'" target="_blank">'.lecho ($item_db['FIELD_DOMAIN']).'</a></div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Название:</b> '.lecho ($item_db['FIELD_NAME']).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Раздел:</b> '.lecho ($item_db['KNAME']).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Штрафных баллов:</b> '.intval ($item_db['FIELD_BAD_BALLS']).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Публикация:</b> '.si_admin_get_enable ($item_db['FIELD_ENABLE'], 0).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Пользователь:</b> '.lecho ($item_db['FIELD_USER_NAME']).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>E-mail:</b> ';
if ($item_db['FIELD_USER_MAIL']) $ex.='<a href="mailto:'.lecho ($item_db['FIELD_USER_MAIL']).'">'.lecho ($item_db['FIELD_USER_MAIL']).'</a>'; 
	else $ex.='<span class="normal_red"><b>E-mail неопределён!</b></span>';
$ex.='</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Дата добавления:</b> '.date('d-m-Y H:i', $item_db['FIELD_CREATE_DATE']).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Проверка:</b> '.date('d-m-Y H:i', $item_db['FIELD_VERIFY_DATE']).'</div>';
$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Адрес в нашем каталоге:</b> <a href="'.$link_page_addr.'" target="_blank">'.$link_page_addr.'</a></div>';

$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Адрес обратной ссылки:</b> ';
if ($item_db['FIELD_RET_LINK_ADDR']) $ex.='<a href="'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'</a>';
else $ex.='<span class="normal_red"><b>Адрес не задан!</b></span>';
$ex.='</div>';

if ($item_db['FIELD_IS_NEW']) $ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Флаг:</b> <span class="normal_green"><b>*NEW*</b></span></div>';
if ($item_db['FIELD_FOR_DEL']) $ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Флаг:</b> <span class="normal_red"><b>Автоперенос</b></span></div>';
if ($item_db['FIELD_DISABLE_AUTOBOT']) $ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Флаг:</b> <span class="normal_red"><b>No Autobot</b></span></div>';

$ex.='<div style="margin-top:4px;margin-bottom:4px;"><b>Пароль для редактирования:</b> '.lecho ($item_db['FIELD_KEY_FOR_EDIT']).'</div>';

return $ex;
}
//================================================

function links_get_dialog_submenu ($item_db)
{
//функа возвращает подменюху для перехода по диалогам.
$num=0;
$sub_menu_param=array();
$sub_menu_param[$num]['href']='fast_dialog.php?id='.$item_db['id'];
$sub_menu_param[$num]['title']='Быстрый диалог';
$sub_menu_param[$num++]['name']='<b>"Быстрый диалог"</b>';

$sub_menu_param[$num]['href']='verify_ret_dialog.php?id='.$item_db['id'];
$sub_menu_param[$num]['title']='Диалог проверки обратной ссылки';
$sub_menu_param[$num++]['name']='<b>Обратка</b>';

$sub_menu_param[$num]['href']='verify_ret_dialog_ya.php?id='.$item_db['id'];
$sub_menu_param[$num]['title']='Диалог проверки обратной ссылки в Yandex';
$sub_menu_param[$num++]['name']='<b>Обратка в Yandex</b>';

$sub_menu_param[$num]['href']='send_dialog.php?id='.$item_db['id'];
$sub_menu_param[$num]['title']='Отправка сообщения партнёру';
$sub_menu_param[$num++]['name']='<b>Message</b>';

$sub_menu_param[$num]['href']='../index.php?actions=edit_links_item&id='.$item_db['id'];
$sub_menu_param[$num]['spec_params']='target="_blank"';
$sub_menu_param[$num]['title']='Редактирование ссылки';
$sub_menu_param[$num++]['name']='<b>Edit</b>';

$sub_menu_param[$num]['href']='javascript:window.close();';
$sub_menu_param[$num]['title']='Закрыть окно';
$sub_menu_param[$num++]['name']='<span class="normal_red"><b>Закрыть окно</b></span>';

$this_script=pathinfo ($_SERVER['SCRIPT_NAME']);
$this_script=$this_script['basename'];

$in_string_count=6;						//количество пунктов с строке.
$string_count=ceil (count($sub_menu_param)/$in_string_count);	//количество строк в меню. 

$ex='';
for ($n=0; $n<$string_count; $n++)
{
$ex.='
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
 <tr class="middletitle" align="center">';
 
for ($i=0; $i<$in_string_count; $i++)
{
$current_num=$n*$in_string_count+$i;	//вычисляем порядковый номер

if ($sub_menu_param[$current_num])
{

$adv_str=' onMouseOver="this.className=\'rowyellow\';" onMouseOut="this.className=\'middletitle\';"';
$query_script=parse_url ($sub_menu_param[$current_num]['href']);
$query_script=$query_script['path'];


if ($query_script==$this_script) $adv_str='class="rowyellow"';	//если в даный момент выполняется этот скрипт, то подсвечиваем жёлтым.

$ex.='<td '.$adv_str.'><a href="'.$sub_menu_param[$current_num]['href'].'" '.$sub_menu_param[$current_num]['spec_params'].' title="'.$sub_menu_param[$current_num]['title'].'">'.$sub_menu_param[$current_num]['name'].'</a></td>';
}

}

$ex.='</tr></table>';

if ($string_count>1 && $n<$string_count-1) $ex.=get_tabs ();

}

return $ex;
}
//================================================

?>