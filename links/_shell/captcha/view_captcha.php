<?
define  ('Root_Dir','../../'); 	//корень сайта относительно ЭТОГО скрипта
define  ('In_Buffer','Off'); 	//включение буфферизации вывода. Если контанта не определена, значит буфферизация выключена

//скрипт отображает капчу, id которой передано в $_GET['captcha_id']

include (Root_Dir.'_shell/start_site.php'); 	//инициализация
include_once (Root_Dir.'_shell/functions/captcha_functions.php');				//загрузка функций для управления почтой

//получаем номер для капчи $captcha_id;
$captcha_db=SI_sql_query("select * from ".Base_Prefix."captcha WHERE id='".intval($_GET['captcha_id'])."'");
if (!$captcha_db) si_get_404_page ();	//если капчи нет в базе, то 404.

$img=captcha_build_image ($captcha_db[0]['FIELD_NUMBER']);

header('Content-type: image/jpeg');
imagejpeg($img);
imagedestroy($img);

include (Root_Dir.'_shell/end_site.php');	//завершение работы сайта

?>