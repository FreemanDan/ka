<?
define  ('Root_Dir','../'); 	//корень сайта относительно ЭТОГО скрипта
define  ('In_Buffer','On'); 		//включение буфферизации вывода. Если контанта не определена, значит буфферизация выключена

//авторобот для проверки ссылок для моделы "каталог ссылок".
include (Root_Dir.'_shell/start_site.php'); 	//инициализация

ignore_user_abort(1); 	//этот скрипт недолжен вырубаться.. НЕДОЛЖЕН!

//загружаем функи для работы.
include_once (Root_Dir.'_shell/functions/mail_functions.php');	//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/actions_handlers.php');	//загружаем информацию о действиях и их обработчики.
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для отправки сообщений о действиях с каталогом.
include_once (Root_Dir.'autobot/autobot_functions.php');	//функи для авторобота

$away_time=0;		//время задержки перед следующим шагом. (по умолчанию)

$autobot_settings_db=get_link_autobot_settings ();	//получаем настройки авторобота


if ($autobot_settings_db['FIELD_ENABLE'])
{
//включена проверка.

$view_str='<br>';			//сюда пишем результат проверки по каталогу.

//если для этого каталога большая задержка, то берём её.
if ($away_time<intval ($autobot_settings_db['AUТOBOT_STOP_TIME'])) $away_time=intval ($autobot_settings_db['AUТOBOT_STOP_TIME']);

//получаем массив с ссылками для текущей проверки.
$next_link_db=links_autobot_get_next_links_db ($links_settings_db, $autobot_settings_db);

//теперь для всех полученных ссылок выставляем новое время проверки.
//по идее этот скрипт не должен завершится до окончания работы.
//ну а т.к. проверка - это долгая операция. то при нескольких вызовах могут быть задвоенные проверки.
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_VERIFY_DATE='".intval($system_db['THIS_TIME'])."' WHERE id IN (".si_get_in_string (si_generate_aka ($next_link_db, 'id')).")");


for ($z=0; $z<count($next_link_db); $z++)
{
//закидываем ссылки в функу проверки.
$save_log_text.=links_autobot_verify_links ($next_link_db[$z], $links_settings_db, $autobot_settings_db);
}

if (!$next_link_db) $save_log_text.='Нет ссылок для проверки!<br>';

//сохраняем текст выполнения.
links_autobot_save_log ($autobot_settings_db, $save_log_text, $next_link_db);

//формируем текст для вывода пользователю
$view_str.='Проверено: "<b>'.count($next_link_db).'</b>"<br>';
$view_str.='Осталось: "<b>'.links_autobot_get_next_links_count ($links_settings_db, $autobot_settings_db).'</b>"<br><br>';
}
else 
{
$view_str='Проверка отключена.<br>';
$away_time=600;
}

//выводми сообщение и редирект
?>
<html>
<head>
<title>Авторобот</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="<?=Global_WWW_Path ?>_admin/css/admin.css">
<script language="javascript"><!--
var stop_timer=0;
function next_step(tm_sec, goto_url)
{
//функция делает переход с следующему шагу goto_url через tm_sec секунд

//если флаг остановки таймена высталвен, то отсчёт не производим.
//нужно для остановки таймера при клике на ссылке "следующие ссылки"
if (stop_timer==1) return;

if (tm_sec>=0)
{
 document.getElementById('away_time').innerHTML=tm_sec;
 tm_sec=tm_sec-1;
 setTimeout("next_step('"+tm_sec+"', '"+goto_url+"')", 1000);
}
else 
{
 window.location=goto_url;
 document.getElementById('away_time').innerHTML="Go!";
}

return;
}
//-->
</script>
</head>
<body onLoad="next_step('<?=$away_time ?>', '<?=$_SERVER['PHP_SELF'] ?>');">
<!-- <meta http-equiv="refresh" content="<?=$away_time ?>; url=<?=$_SERVER['PHP_SELF'] ?>"> -->
<br><br><br><br><br><br>
<div align="center">
<div style="width:400px;" align="left">
<fieldset class="good_fieldset"><legend>Проверка ссылок автороботом</legend>
<?=$view_str ?>
</fieldset>

<div align="center" style="margin-top:15px;">
<a href="<?=$_SERVER['PHP_SELF'] ?>" onClick="stop_timer=1;">проверить следующие ссылки</a>
<div class="help">(автоматический переход через <span id="away_time"><?=$away_time ?></span> секунд)</div>
</div>

</div></div>
</body>
</html>
<?


include (Root_Dir.'_shell/end_site.php');	//завершение работы сайта
?>