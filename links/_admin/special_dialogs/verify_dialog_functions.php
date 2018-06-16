<?
//комплект функций для диалога проверки ссылки.
//фактичемки базовые функи.

//================================================
function links_vd_on ($item_db, $links_settings_db)
{
//включаем ссылку 
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='1' WHERE id='".intval($item_db['id'])."'");
$ex_str='Ссылка включена.';
if ($links_settings_db['DEF_FAST_MAIL']) 
{
 links_send_message ('on', $item_db['id'], $links_settings_db);	//если есть натсройка о отправке писем из "быстрых" действий - отправляем письмо.
 $ex_str.=' (уведомление отправлено)';
}

return $ex_str;
}
//================================================

function links_vd_off ($item_db, $links_settings_db)
{
//выключаем ссылку 
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='0' WHERE id='".intval($item_db['id'])."'");
$ex_str='Ссылка выключена.';
if ($links_settings_db['DEF_FAST_MAIL']) 
{
 links_send_message ('off', $item_db['id'], $links_settings_db);	//если есть натсройка о отправке писем из "быстрых" действий - отправляем письмо.
 $ex_str.=' (уведомление отправлено)';
}

return $ex_str;
}
//================================================

function links_vd_gde_obratka ($item_db, $links_settings_db)
{
//Отправить запрос на уточнение адреса страницы с обратной ссылкой
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.
links_send_message ('gde_obratka', $item_db['id'], $links_settings_db);
return 'Сообщение отправленно!';
}
//================================================

function links_vd_please_set_retry ($item_db, $links_settings_db)
{
//Отправить просьбу на установку нашей ссылки
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.
links_send_message ('please_set_retry', $item_db['id'], $links_settings_db);
return 'Сообщение отправленно!';
}
//================================================


function links_vd_recicler ($item_db, $links_settings_db)
{
//выключаем ссылку 
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.

if (!$links_settings_db['BLACK_LIST_ID']) return 'Каталог неопределён!';

//получаем название категории, определённой как "black list"
$tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE id='".intval($links_settings_db['BLACK_LIST_ID'])."'");
$black_list_name=$tmp_db[0]['FIELD_NAME'];


SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_LINK_CAT='".intval($links_settings_db['BLACK_LIST_ID'])."' WHERE id='".intval($item_db['id'])."'");
$ex_str='Ссылка перенесена в "'.$black_list_name.'"';
if ($links_settings_db['DEF_FAST_MAIL']) 
{
 links_send_message ('black_list', $item_db['id'], $links_settings_db);	//если есть натсройка о отправке писем из "быстрых" действий - отправляем письмо.
 $ex_str.=' (уведомление отправлено)';
}

return $ex_str;
}
//================================================

function links_vd_add_bad_ball ($item_db, $links_settings_db)
{
//Увеличить штрафные балы на 1 
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS=FIELD_BAD_BALLS+1 WHERE id='".intval($item_db['id'])."'");
$ex_str='Штрафные баллы увеличены.';

return $ex_str;
}
//================================================

function links_vd_dec_bad_ball ($item_db, $links_settings_db)
{
//Уменьшить штрафные балы на 1 
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS=FIELD_BAD_BALLS-1 WHERE id='".intval($item_db['id'])."'");
$ex_str='Штрафные баллы уменьшены.';

return $ex_str;
}
//================================================

function links_vd_send_sorry ($item_db, $links_settings_db)
{
//Отправить уведомление о тематическом несоответствии сайта.
//$item_db - полная инфа о ссылке с которой работаем.
//$links_settings_db - настройки списка.
links_send_message ('send_sorry', $item_db['id'], $links_settings_db);
return 'Сообщение отправленно!';
}
//================================================

function link_get_dialog_action ($act_pic, $act_link, $act_name)
{
//функция возвращает строчку для действий в диалоге
//$act_pic - картинка
//$act_link - ссылка.
//$act_name - название операции
//дополнительным параметром могут переланы настройки ссылки.. например target="_blank"

$target_str='';
if (func_num_args()==4) $target_str=func_get_arg (3);

$ex='<tr>';

$ex.='<td width="20" align="right">';
if ($act_pic) $ex.='<a href="'.$act_link.'" '.$target_str.'><img src="'.$act_pic.'" border="0" alt="'.lecho($act_name).'"></a>';
$ex.='</td>';

$ex.='<td width="100%">';
$ex.='<a href="'.$act_link.'" '.$target_str.'>'.$act_name.'</a>';
$ex.='</td>';

$ex.='</tr>';

return $ex;
}
//================================================

?>