<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.

?>
<div class="catalog_path">Действия: Завершение сеанса </div>
<?

//убиваем данные в админке.
si_set_session (Base_Prefix.'admin_act_time', 0);
si_set_session (Base_Prefix.'admin_hash', '');

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Авторизоваться снова';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'];

$choise_db[$num]['name']='Выход в каталог ссылок';
$choise_db[$num++]['link']=Global_WWW_Path;

$choise_db[$num]['name']='Выход на главную страницу сайта';
$choise_db[$num++]['link']='/';

$message='Сеанс закончен.';
echo si_message_box ('Exit',$message, $choise_db, 0, '350px');


?>
