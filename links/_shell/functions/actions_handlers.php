<?
//обработчики действий.
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//функции требуют ранее загруженные links_mail_functions.php - функи для отправки уведомлений

//описание действий с сылками.
//формат записи
//$link_act_handlers_db=array();
//$link_act_handlers_db['key'] - ключ действия
//$link_act_handlers_db['key']['name'] - название
//$link_act_handlers_db['key']['pic'] - картинка действия
//$link_act_handlers_db['key']['func'] - функция обработчик
//функции обработчику передаются данные $item_db и $links_settings_db
//но обработчики вызываются через промежуточную link_exec_act ($key, $item_id, $links_settings_db, $send_email) 
//которая является своеобразным контроллером вызываемых действий

//описываем действия
$link_act_handlers_db=array();

//----
$link_act_handlers_db['off']['name']='<span class="normal_red">Выключить.</span> (если включена)';			//название операции
$link_act_handlers_db['off']['pic']=Global_WWW_Path.'_admin/icons/big_off.gif';			//иконка
$link_act_handlers_db['off']['func']='link_handler_off';		//обработчик
//----
$link_act_handlers_db['on']['name']='Включить. (если выключена)';		//название операции
$link_act_handlers_db['on']['pic']=Global_WWW_Path.'_admin/icons/big_on.gif';		//иконка
$link_act_handlers_db['on']['func']='link_handler_on';	//обработчик
//----
$link_act_handlers_db['send_sorry']['name']='Отправить уведомление о тематическом несоответствии сайта.';		//название операции
$link_act_handlers_db['send_sorry']['pic']=Global_WWW_Path.'_admin/icons/send_sorry.gif';		//иконка
$link_act_handlers_db['send_sorry']['func']='link_handler_send_sorry';	//обработчик
//----
$link_act_handlers_db['recicler']['name']='<span class="normal_red">Перенести в "Black List"</span> (если есть категория, определённая как Black List)';		//название операции
$link_act_handlers_db['recicler']['pic']=Global_WWW_Path.'_admin/icons/recicler_middle.gif';		//иконка
$link_act_handlers_db['recicler']['func']='link_handler_recicler';	//обработчик
//----
$link_act_handlers_db['autoperenos']['name']='<span class="normal_red">Выставить флаг "Автоперенос"</span> (если неустановлен)';		//название операции
$link_act_handlers_db['autoperenos']['pic']=Global_WWW_Path.'_admin/icons/autoperenos.gif';		//иконка
$link_act_handlers_db['autoperenos']['func']='link_handler_autoperenos';	//обработчик
//----
$link_act_handlers_db['no_autoperenos']['name']='Снять флаг "Автоперенос" (если установлен)';		//название операции
$link_act_handlers_db['no_autoperenos']['pic']=Global_WWW_Path.'_admin/icons/no_autoperenos.gif';		//иконка
$link_act_handlers_db['no_autoperenos']['func']='link_handler_no_autoperenos';	//обработчик
//----
$link_act_handlers_db['please_set_retry']['name']='Отправить просьбу на установку нашей ссылки.';		//название операции
$link_act_handlers_db['please_set_retry']['pic']=Global_WWW_Path.'_admin/icons/please_set_retry.gif';		//иконка
$link_act_handlers_db['please_set_retry']['func']='link_handler_please_set_retry';	//обработчик
//----
$link_act_handlers_db['gde_obratka']['name']='Отправить запрос на уточнение адреса страницы с обратной ссылкой.';		//название операции
$link_act_handlers_db['gde_obratka']['pic']=Global_WWW_Path.'_admin/icons/gde_obratka.gif';		//иконка
$link_act_handlers_db['gde_obratka']['func']='link_handler_gde_obratka';	//обработчик
//----
$link_act_handlers_db['good_message']['name']='Отправить уведомление о расположении ссылки в нашем каталоге.';		//название операции
$link_act_handlers_db['good_message']['pic']=Global_WWW_Path.'_admin/icons/good_message.gif';		//иконка
$link_act_handlers_db['good_message']['func']='link_handler_good_message';	//обработчик
//----
$link_act_handlers_db['add_bad_ball']['name']='<span class="normal_red">Увеличить штрафные балы на 1</span>';		//название операции
$link_act_handlers_db['add_bad_ball']['pic']=Global_WWW_Path.'_admin/icons/add_bad_ball.gif';		//иконка
$link_act_handlers_db['add_bad_ball']['func']='link_handler_add_bad_ball';	//обработчик
//----
$link_act_handlers_db['dec_bad_ball']['name']='Уменьшить штрафные балы на 1';		//название операции
$link_act_handlers_db['dec_bad_ball']['pic']=Global_WWW_Path.'_admin/icons/dec_bad_ball.gif';		//иконка
$link_act_handlers_db['dec_bad_ball']['func']='link_handler_dec_bad_ball';	//обработчик
//----
$link_act_handlers_db['no_is_new']['name']='Снять флаг <span class="normal_green"><b>*NEW*</b></span>';		//название операции
$link_act_handlers_db['no_is_new']['pic']=Global_WWW_Path.'_admin/icons/no_is_new.gif';		//иконка
$link_act_handlers_db['no_is_new']['func']='link_handler_no_is_new';	//обработчик
//----
$link_act_handlers_db['nah']['name']='<span class="normal_red">Послать на www.nah.ru :[] </span>';		//название операции
$link_act_handlers_db['nah']['pic']=Global_WWW_Path.'_admin/icons/nah.gif';		//иконка
$link_act_handlers_db['nah']['func']='link_handler_nah';	//обработчик
//----
$link_act_handlers_db['bb_admin_notice']['name']='Уведомление модератору каталога ссылок о превышении партнёром штраф.баллов.';		//название операции
$link_act_handlers_db['bb_admin_notice']['pic']=Global_WWW_Path.'_admin/icons/bb_admin_notice.gif';		//иконка
$link_act_handlers_db['bb_admin_notice']['func']='link_handler_bb_admin_notice';	//обработчик
//----
$link_act_handlers_db['edit_admin_notice']['name']='Уведомление модератору каталога ссылок о редактировании пользователем ссылки.';		//название операции
$link_act_handlers_db['edit_admin_notice']['pic']=Global_WWW_Path.'_admin/icons/edit_admin_notice.gif';		//иконка
$link_act_handlers_db['edit_admin_notice']['func']='link_handler_edit_admin_notice';	//обработчик
//----

$link_act_handlers_db['nolink_admin_notice']['name']='Пожаловаться модератору на отсутствие обратной ссылки по указанному адресу.';		//название операции
$link_act_handlers_db['nolink_admin_notice']['pic']=Global_WWW_Path.'_admin/icons/nolink_admin_notice.gif';		//иконка
$link_act_handlers_db['nolink_admin_notice']['func']='link_handler_nolink_admin_notice';	//обработчик
//----

//====================================================================================
//	вспомогательные функции
//====================================================================================

function links_get_act_table ($arr_name, $act_db, $act_sel_db)
{
//функция возвращает табличку с выбором дайствий.
//$arr_name - название массива в форме в который будут добавленны эти параметры.
//$act_db - массив с перечислением ключей дейсвий 0..n
//$act_sel_db - массив 0..n с перечисленными ключами, которые должны быть выбранны.
global $link_act_handlers_db;

$ex='<table width="100%" cellspacing="1" cellpadding="2" border="0">';
for ($i=0; $i<count($act_db); $i++)
{
 $sel='';
 if (my_in_array($act_sel_db, $act_db[$i])) $sel='checked';
 $ex.='
    <tr>
     <td><img src="'.$link_act_handlers_db[$act_db[$i]]['pic'].'" alt="0"></td>
     <td><input type="checkbox" name="'.$arr_name.'[]" value="'.$act_db[$i].'" '.$sel.'></td>
     <td width="100%">'.$link_act_handlers_db[$act_db[$i]]['name'].'</td>
    </tr>';
}

$ex.='</table>';

return $ex;
}

//-------------------------------

function link_exec_act ($key, $item_id, $links_settings_db, $send_email)
{
//функция выполняет действие $key над ссылкой $item_id с настройками каталога $links_settings_db
//$send_email - отправка почты при выполнения действия. 0 - отправляем, 1 - отправляем.
//возвращает строку выполнения вызванной операции.
global $link_act_handlers_db;

//проверяем. существуетли данная функция и елси нет, то вылетаем.
if (!function_exists ($link_act_handlers_db[$key]['func'])) return 'ERROR: Действие "'.lecho ($key).'" не имеет своего обработчика!';

//перепрочитываем инфу о ссылке.
$item_db=links_get_item_full_data ($item_id);

//вызываем функу и возвращем результат выполнения.
return call_user_func ($link_act_handlers_db[$key]['func'], $item_db, $links_settings_db, $send_email);
}
//====================================================================================



//====================================================================================
//	обработчики действий
//====================================================================================
//-------------------------------

function link_handler_off ($item_db, $links_settings_db, $send_email)
{
//функция выключает ссылку
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Выключение ссылки" =>';
if ($item_db['FIELD_ENABLE']==0) return $ex.' ссылка была выключена ранее. (действие отменено)';

//выключаем ссылку
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='0' WHERE id='".intval($item_db['id'])."'");
$ex.=' ссылка выключена.';

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_off ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------

function link_handler_on ($item_db, $links_settings_db, $send_email)
{
//функция включает ссылку
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Включение ссылки" =>';
if ($item_db['FIELD_ENABLE']==1) return $ex.' ссылка была включена ранее. (действие отменено)';

//включаем ссылку
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='1' WHERE id='".intval($item_db['id'])."'");
$ex.=' ссылка включена.';

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_on ($item_db, $links_settings_db);
$ex.=' (сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_send_sorry ($item_db, $links_settings_db, $send_email)
{
//функция отправляет сообщение о тематическом несоотвествии сайта
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "уведомление о тематическом несоответствии сайта" => ';

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_send_sorry ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_recicler ($item_db, $links_settings_db, $send_email)
{
//функция переносит ссылку в чёрный список, если он есть.
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Перенос в Black List" => ';

if (!$links_settings_db['BLACK_LIST_ID']) 
{
//чёрный список неопределён.
$ex.=' в каталоге "'.lecho($item_db['CNAME']).'" Black List не определён. отмена действия!.';
return $ex;
}

//получаем название раздела определённого как чёрный список
$tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE id='".intval($links_settings_db['BLACK_LIST_ID'])."'");
$black_list_name=$tmp_db[0]['FIELD_NAME'];

//переносим
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_LINK_CAT='".intval($links_settings_db['BLACK_LIST_ID'])."' WHERE id='".intval($item_db['id'])."'");
$ex.=' ссылка перенесена в "'.lecho($black_list_name).'"';

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_black_list ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_autoperenos ($item_db, $links_settings_db, $send_email)
{
//функция выставляет флаг "автоперенос" если он не установлен
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Установка флага Автоперенос" =>';

if ($item_db['FIELD_FOR_DEL']==1) return $ex.' флаг уже выставлен! (действие отменено).';

//выключаем ссылку
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_FOR_DEL='1' WHERE id='".intval($item_db['id'])."'");
$ex.=' флаг установлен.';

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_autoperenos ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_no_autoperenos ($item_db, $links_settings_db, $send_email)
{
//функция снимает флаг "автоперенос" если он установлен
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Снятие флага Автоперенос" =>';

if ($item_db['FIELD_FOR_DEL']==0) return $ex.' флаг не установлен! (действие отменено).';

//выключаем ссылку
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_FOR_DEL='0' WHERE id='".intval($item_db['id'])."'");
$ex.=' флаг снят.';

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_good_message ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_please_set_retry ($item_db, $links_settings_db, $send_email)
{
//функция отправляет уведомление просьбу о установке обратки.
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Отправка просьбы на установку нашей ссылки." =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_please_set_retry ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_gde_obratka ($item_db, $links_settings_db, $send_email)
{
//функция отправляет запрос на уточнение адреса страницы с обратной ссылкой
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Отправка запроса на уточнение адреса страницы с обратной ссылкой." =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_gde_obratka ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_good_message ($item_db, $links_settings_db, $send_email)
{
//функция отправляет уведомление о расположении ссылки в нашем каталоге
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Отправка уведомления о расположении ссылки в нашем каталоге." =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_good_message ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_add_bad_ball ($item_db, $links_settings_db, $send_email)
{
//функция увеличивает штрафные балы на 1
//если штраф баллы равны максимально допустимому, то применяются действия описанные в настройках.
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Увеличение штрафных балов на 1" =>';

if ($item_db['FIELD_BAD_BALLS']>=$links_settings_db['MAX_BAD_BALLS']) return $ex.' штраф. балы уже достигли '.$links_settings_db['MAX_BAD_BALLS'].' балов. (действие отменено)';

//увеличиваем штраф баллы.
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS=FIELD_BAD_BALLS+1 WHERE id='".intval($item_db['id'])."'");

if ($item_db['FIELD_BAD_BALLS']==($links_settings_db['MAX_BAD_BALLS']-1))
{
// штраф.балы достигли значения определённого в насторйках как максимальное.
//выполняем действия выбранные в настройках.

$ex.=' достигнуто максимально допуcтимое значение штраф.баллов! <div class="help"><b>Применяем действия:</b> <br>';
for ($i=0; $i<count($links_settings_db['BAD_BALLS_ACT']); $i++) $ex.=link_exec_act ($links_settings_db['BAD_BALLS_ACT'][$i], $item_db['id'], $links_settings_db, $send_email).'<br>';
$ex.='</div>';
}

if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_good_message ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_dec_bad_ball ($item_db, $links_settings_db, $send_email)
{
//функция уменьшает штрафные балы на 1
//если штраф баллы меньше 0, то устанавливаются в 0
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Уменьшение штрафных балов на 1" =>';

//на всякий случай выставляем 0. елси штрафбаллов меньше нуля.
if ($item_db['FIELD_BAD_BALLS']<0) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS='0' WHERE id='".intval($item_db['id'])."'");

if ($item_db['FIELD_BAD_BALLS']<=0) return $ex.' штраф. балы уже равны 0. (действие отменено)';

//уменьшаем штраф баллы.
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS=FIELD_BAD_BALLS-1 WHERE id='".intval($item_db['id'])."'");
$ex.=' штраф. балы уменьшены.';

return $ex;
}
//-------------------------------


function link_handler_no_is_new ($item_db, $links_settings_db, $send_email)
{
//функция снимает флаг "новый", если он был установлен
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Снятие флага *NEW*" =>';

if ($item_db['FIELD_IS_NEW']==0) return $ex.' флаг уже снят. (действие отменено)';

//уменьшаем штраф баллы.
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_IS_NEW='0' WHERE id='".intval($item_db['id'])."'");
$ex.=' флаг снят.';

return $ex;
}
//-------------------------------


function link_handler_nah ($item_db, $links_settings_db, $send_email)
{
//функция отправляет нахуй
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Отправка нахуй" =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_nah ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';
}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------

function link_handler_bb_admin_notice ($item_db, $links_settings_db, $send_email)
{
//функция отправляет уведомление модеру каталога о том, что одна из ссылок превысила штрафбалы.
//$item_db - инфа по ссылке
//$links_settings_db - натсройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Уведомление модератору каталога ссылок о превышении партнёром штраф.баллов" =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_bb_admin_notice ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';

}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_edit_admin_notice ($item_db, $links_settings_db, $send_email)
{
//функция отправляет уведомление модеру каталога о том, что пользователь отредактировал свою ссылку.
//$item_db - инфа по ссылке
//$links_settings_db - настройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Уведомление модератора о изменении пользователем свой ссылки" =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_edit_admin_notice ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';

}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


function link_handler_nolink_admin_notice ($item_db, $links_settings_db, $send_email)
{
//функция отправляет уведомление модеру каталога о том, авторобот не нашёл обратки.
//$item_db - инфа по ссылке
//$links_settings_db - настройки каталога
//$send_email - флаг отправки почты (1 - отправлять, 0 - нет)

$ex='Действие: "Уведомление модератора об отсутствии обратной ссылки по указанному адресу" =>';


if ($send_email==1)
{
//если стоит флаг отправки почты. то отправляем почту и дописываем в выполнение соотв строку.
links_send_nolink_admin_notice ($item_db, $links_settings_db);
$ex.='(сообщение отправлено на '.lecho ($item_db['FIELD_USER_MAIL']).')';

}
else $ex.='(сообщение не отправлено)';

return $ex;
}
//-------------------------------


//====================================================================================

?>