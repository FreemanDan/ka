<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.

include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для отправки сообщений о действиях с каталогом.


?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / <a href="<?=$_SERVER['PHP_SELF'].'?actions=links_item' ?>">Список ссылок </a> / Создание ссылки </div>
<?

if ($_POST['add'])
{
//нажато "Принять изменения"

$LINK_CAT=intval ($_POST['LINK_CAT']);
$NAME=trim ($_POST['NAME']);
$DOMAIN=links_get_clear_domain ($_POST['DOMAIN']);
$IMG_ADDR=trim ($_POST['IMG_ADDR']);
$RET_LINK_ADDR=trim ($_POST['RET_LINK_ADDR']);
$USER_NAME=trim ($_POST['USER_NAME']);
$USER_MAIL=trim ($_POST['USER_MAIL']);
$TEXT_HTML=trim ($_POST['TEXT_HTML']);
$ENABLE=intval ($_POST['ENABLE']);
$IS_NEW=intval ($_POST['IS_NEW']);
$FOR_DEL=intval ($_POST['FOR_DEL']);
$BAD_BALLS=intval ($_POST['BAD_BALLS']);
$DISABLE_AUTOBOT=intval ($_POST['DISABLE_AUTOBOT']);
$KEY_FOR_EDIT=trim ($_POST['KEY_FOR_EDIT']);

if (!$LINK_CAT) $err.='Не введена категория сайта!<br>';
if (!eto_ne_pusto($NAME)) $err.='Не введено название сайта!<br>';
if (!eto_ne_pusto($DOMAIN)) $err.='Не введён адрес сайта!<br>';
if (!eto_ne_pusto($TEXT_HTML)) $err.='Не введён HTML код ссылки!<br>';

if (links_verify_unic_domain ($id, $DOMAIN, 0)) $err.='Ссылка на такой сайт уже есть в базе каталога!<br>';

if (!$err)
{
//всё оки

//получаем индекс сортировки
if ($links_settings_db['INSERT_IN']==1)
{
$tmp_db=SI_sql_query("select MIN(FIELD_SORT) AS MS from ".Base_Prefix."links_items");
$next_srt=$tmp_db[0]['MS']-1;
}
else
{
$tmp_db=SI_sql_query("select MAX(FIELD_SORT) AS MS from ".Base_Prefix."links_items");
$next_srt=$tmp_db[0]['MS']+1;
}

mysql_query("insert into ".Base_Prefix."links_items (FIELD_LINK_CAT, FIELD_SORT) values('$LINK_CAT', '$next_srt')");
$id_ins=mysql_insert_id(); //id добавленной 

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_CREATE_DATE='".$system_db['THIS_TIME']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_DOMAIN='$DOMAIN' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_NAME='$NAME' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_IMG_ADDR='$IMG_ADDR' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_TEXT_HTML='$TEXT_HTML' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_RET_LINK_ADDR='$RET_LINK_ADDR' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_USER_NAME='$USER_NAME' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_USER_MAIL='$USER_MAIL' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='$ENABLE' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_IS_NEW='$IS_NEW' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_FOR_DEL='$FOR_DEL' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS='$BAD_BALLS' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_DISABLE_AUTOBOT='$DISABLE_AUTOBOT' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_KEY_FOR_EDIT='$KEY_FOR_EDIT' WHERE id='$id_ins'");

//после добавления производим проверку на возможность вставки новой ссылки вместо ссылки с флагом "автоперенос"
//если такая возможность есть, то выполняем вставку и автоперенос
link_perenos ($id_ins, $links_settings_db);

//если выставлено "уведомить пользователя", то отправляем ему письмо с уведомлением о добавлении его ссылки
if ($_POST['send_mess']) links_send_create_admin (links_get_item_full_data ($id_ins), $links_settings_db);

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_item';

$choise_db[$num]['name']='Добавить ещё ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_item';

$choise_db[$num]['name']='Редактировать добавленную';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_links_item&id='.$id_ins;

$message='Ссылка добавленна';
echo si_message_box ('Add',$message, $choise_db, 0, '350px');
my_exit();
}

}


if ($err)
{
//ашыпки
//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_item';

$choise_db[$num]['name']='Добавить ещё ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_item';

echo si_message_box ('Error',$err, $choise_db, 1, '350px');
my_exit();
}

$form_name='edit_message_form';
?>
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">

<fieldset class="normal_fieldset"><legend>Основные параметры:</legend>
Категория:<br>
<?
//получаем все категории.
$links_cat_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category ORDER BY FIELD_SORT ASC");
$links_cat_db=si_generate_afa ($links_cat_db, 'id', 'FIELD_NAME');
echo l_select ('name="LINK_CAT"', $links_cat_db, '');
?><br><br>

Название сайта:<br>
<?=l_text ('name=NAME style="width:100%"', '') ?>
<div class="help">Наример: Аккало-Каккало. Лучшая финская сантехника по оптовым ценам.</div>
<br>

Адрес сайта:<br>
<?=l_text ('name=DOMAIN style="width:100%"', 'http://') ?>
<div class="help">Наример: http://www.akkalo-kakkalo.ru</div>
<br>

Адрес картинки:<br>
<?=l_text ('name=IMG_ADDR style="width:100%"', '') ?>
<div class="help">Наример: http://www.akkalo-kakkalo.ru/banner88x31.gif</div>
<br>

Адрес страницы с обратной ссылкой:<br>
<?=l_text ('name=RET_LINK_ADDR style="width:100%"', '') ?>
<div class="help">Наример: http://www.akkalo-kakkalo.ru/links.html</div>
<br>

Имя пользователя:<br>
<?=l_text ('name=USER_NAME size="50"', '') ?><br><br>

E-mail пользователя:<br>
<?=l_text ('name=USER_MAIL size="50"', '') ?><br><br>

HTML код:<br>
<?=l_textarea ('name=TEXT_HTML style="width:100%" rows="7"', '') ?>
<div class="help"><?=lecho ('Например: Лучшая <a href=" http://www.akkalo-kakkalo.ru">сантехника из финляндии</a>, низкие цены, высокий сервис, <a href=" http://www.akkalo-kakkalo.ru">доставка сантехники.</a> '); ?></div>
<br><br>

Штрафные баллы:<br>
<?=l_text ('name=BAD_BALLS size="10"', 0) ?>
<br><br>

Пароль для изменения ссылки пользователем:<br>
<?=l_text ('name=KEY_FOR_EDIT size="30"', links_generate_edit_key ()) ?>

</fieldset>

<table width="100%">
<tr>
 <td>
<fieldset class="alert_fieldset"><legend>Публикация:</legend>
<input type="radio" value="0" name="ENABLE"> - <span class="normal_red"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1" checked  name="ENABLE"> - <span class="normal_green"><b>On</b> (включено)</span>
</fieldset>
 </td>
 <td>
<fieldset class="good_fieldset"><legend>Флаг "*NEW*":</legend>
<input type="radio" value="0" checked name="IS_NEW"> - <b>Off</b> (выключено)<br>
<input type="radio" value="1" name="IS_NEW"> - <b>On</b> (включено)
</fieldset>
 </td>
 <td>
<fieldset class="alert_fieldset"><legend>Флаг "Автоперенос":</legend>
<input type="radio" value="0" checked name="FOR_DEL"> - <span class="normal_green"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1"  name="FOR_DEL"> - <span class="normal_red"><b>On</b> (включено)</span>
</fieldset>
 </td>
 <td>
<fieldset class="alert_fieldset"><legend>Флаг "No Autobot":</legend>
<input type="radio" value="0" checked name="DISABLE_AUTOBOT"> - <span class="normal_green"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1"  name="DISABLE_AUTOBOT"> - <span class="normal_red"><b>On</b> (включено)</span>
</fieldset>
 </td>
</tr> 
</table>

<fieldset class="good_fieldset"><legend>Уведомление вэбмастеру сайта:</legend>
<input type="checkbox" name="send_mess" value="1" <?=$links_settings_db['DEF_FAST_MAIL'] ?>> - отправить уведомление вэбмастеру о создании ссылки?
</fieldset>

<center>
<?=l_buttion ('name=add', 'Добавить ссылку') ?>
</form>

