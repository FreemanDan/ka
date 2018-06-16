<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//=============================================
//	комплект функций для работы с почтой
//			Kelkos 2006
//=============================================


//=============================================
function SI_send_mail ($mailto, $subj, $text)
{
//функция для отправки писем.
//4-ым параметром может передаваться доп. параметры:
//$params_db - список доп. данных для передачи
//$params_db['mailfrom'] - от кого отправляем. (по умолчанию адрес админа)
//$params_db['type'] - формат text/html или text/plain (по умолчанию text/html)
//$params_db['charset'] - кодировка (по умолчанию windows-1251)
global $system_db;

$params_db=array();
if (func_num_args()==4) $params_db=func_get_arg (3);

if (!si_key_exists ('mailfrom', $params_db)) $params_db['mailfrom']='"=?windows-1251?B?'.base64_encode(Site_Name).'?=" <'.$system_db['FIELD_ADMIN_MAIL'].'>'; 
if (!si_key_exists ('type', $params_db)) $params_db['type']='text/html';
if (!si_key_exists ('charset', $params_db)) $params_db['charset']='windows-1251';

$headers='';
$headers .= "From: ".$params_db['mailfrom']."\r\n";
$headers .= "Reply-To: ".$params_db['mailfrom']."\r\n"; 
$headers .= "Return-Path: ".$params_db['mailfrom']."\r\n";
$headers .= "MIME-Version: 1.0\r\n"; 
$headers .= "Content-Type: ".$params_db['type']."; charset=".$params_db['charset']."\r\n"; 
$headers .= "Date: ".date("m.d.Y (H:i:s)", $system_db['THIS_TIME'])."\r\n"; 

//выправляем тему в правильный вид.
$subj = '=?windows-1251?B?'.base64_encode($subj).'?=';

//отправка.
@mail($mailto, $subj, $text, $headers);
}
//=============================================

?>