<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.
//редактирование настроек.

include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружаем доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.

$form_name='autobotsett'.Base_Prefix;

$autobot_settings_db=get_link_autobot_settings ();	//получаем настройки авторобота

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Настройки "Авторобота"</div>
<?

//========================================================================
//здесь заводим новые товары в список.
if ($_POST['ok'])
{
//записываем изменения.

$ENABLE=intval ($_POST['ENABLE']);
$SETTINGS=base64_encode (@serialize ($_POST));

//записываем инфу.
SI_sql_query("UPDATE ".Base_Prefix."links_auto_bot_settings SET FIELD_ENABLE='$ENABLE'");
SI_sql_query("UPDATE ".Base_Prefix."links_auto_bot_settings SET FIELD_SETTINGS='$SETTINGS'");

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='В главное меню "Каталог ссылок"';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'];

$choise_db[$num]['name']='Продолжить редактирование';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions='.$actions;

$message='Изменения приняты!';
echo si_message_box ('Ок',$message, $choise_db, 0, '350px');
my_exit();
}
//========================================================================

//print_ar ($autobot_settings_db);

//получаем название категории, определённой как "black list"
$black_list_name='';
if ($links_settings_db['BLACK_LIST_ID'])
{
$tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE id='".intval($links_settings_db['BLACK_LIST_ID'])."'");
$black_list_name=$tmp_db[0]['FIELD_NAME'];
}


?>
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">

<fieldset class="alert_fieldset"><legend>Проверка каталога "Автороботом":</legend>
<input type="radio" value="0" <? if (intval ($autobot_settings_db['FIELD_ENABLE'])==0) echo 'checked'; ?> name="ENABLE"> - <span class="normal_red"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1" <? if (intval ($autobot_settings_db['FIELD_ENABLE'])==1) echo 'checked'; ?> name="ENABLE"> - <span class="normal_green"><b>On</b> (включено)</span>
</fieldset>

<fieldset class="normal_fieldset"><legend>Параметры проверки:</legend>

<br>
Проверять каждую ссылку не чаще чем 1 раз в:<br>
<?=l_text ('name=LIM_HOURS size="5"', intval ($autobot_settings_db['LIM_HOURS'])) ?> часов.<br>
<br><br>

За одну проверку проверять ссылок не более:<br>
<?=l_text ('name=LIM_COUNT size="5"', intval ($autobot_settings_db['LIM_COUNT'])) ?> штук.<br>
<br><br>

Брать ссылки только добавленные за последние:<br>
<?=l_text ('name=LIM_DAY size="5"', intval ($autobot_settings_db['LIM_DAY'])) ?> дней.<br>
<br><br>

В случае отсутствия обратной ссылки выполнить:<br>
<?
//формируем список действий
$act_db=array();
$act_db[]='off';
$act_db[]='recicler';
$act_db[]='autoperenos';
$act_db[]='please_set_retry';
$act_db[]='nolink_admin_notice';
$act_db[]='gde_obratka';
$act_db[]='good_message';
$act_db[]='add_bad_ball';
$act_db[]='nah';
echo links_get_act_table ('ACT_NO_FIND', $act_db, $autobot_settings_db['ACT_NO_FIND']);   
?>
<br><br>
   

В случае нахождения обратной ссылки выполнить:<br>
<?
//формируем список действий
$act_db=array();
$act_db[]='on';
$act_db[]='good_message';
$act_db[]='dec_bad_ball';
echo links_get_act_table ('ACT_FIND', $act_db, $autobot_settings_db['ACT_FIND']);   
?>
<br><br>

Информировать вэб-мастеров при изменении их ссылок:<br>
<input type="radio" value="1" <? if ($autobot_settings_db['MAIL_CREATE']==1) echo 'checked'; ?> name="MAIL_CREATE"> - <span class="normal_green">да</span><br>
<input type="radio" value="0" <? if ($autobot_settings_db['MAIL_CREATE']==0) echo 'checked'; ?> name="MAIL_CREATE"> - <span class="normal_red">нет</span>
<div class="help">
Отправка вэб-мастерам других сайтов e-mail уведомлений о изменении их ссылок в автоматическом режиме "автоботом".
</div>

</fieldset>

<fieldset class="normal_fieldset"><legend>Дополнительные параметры:</legend>
Хранить лог проверки автороботом:<br>
<?=l_text ('name=LOG_SAVE_DAY size="10"', intval ($autobot_settings_db['LOG_SAVE_DAY'])) ?> дней.<br>
<br>

В окне проверки автороботом делать задержку:<br>
<?=l_text ('name=AUТOBOT_STOP_TIME size="10"', intval ($autobot_settings_db['AUТOBOT_STOP_TIME'])) ?> секунд.<br>

</fieldset>


<br><br>
<center><?=l_buttion ('name=ok', 'Принять изменения') ?></center>
</form>