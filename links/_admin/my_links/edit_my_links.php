<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


$links_variants_db=SI_sql_query("select * from ".Base_Prefix."links_variants WHERE id='$id'");

if (!$links_variants_db) $err.='Запись с id='.$id.' не найдена!<br>';

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / <a href="<?=$_SERVER['PHP_SELF'].'?actions=my_links' ?>">Список ссылок для обмена </a> / Редактирование ссылки для обмена</div>
<?


if ($_POST['ok'])
if (!$err)
{
//нажато "Принять изменения"

$CODE=trim ($_POST['CODE']);
$SHORT=trim ($_POST['SHORT']);
$BANNER=trim ($_POST['BANNER']);
$ENABLE=intval ($_POST['ENABLE']);

if (!eto_ne_pusto($CODE)) $err.='Не введено содержание!<br>';

if (!$err)
{
//всё оки
SI_sql_query("UPDATE ".Base_Prefix."links_variants SET FIELD_CODE='$CODE' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_variants SET FIELD_SHORT_NAME ='$SHORT' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_variants SET FIELD_ENABLE='$ENABLE' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_variants SET FIELD_BANNER='$BANNER' WHERE id='$id'");
//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок для обмена';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=my_links';

$choise_db[$num]['name']='Добавить новую ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_my_links';

$choise_db[$num]['name']='Продолжить редактирование';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_my_links&id='.$id;

$message='Изменения приняты.';
echo si_message_box ('Ok',$message, $choise_db, 0, '350px');
my_exit();
}

}

if ($_POST['delete'])
if (!$err)
{
//нажато "Удалить"

SI_sql_query ("DELETE FROM ".Base_Prefix."links_variants WHERE id='$id'");

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок для обмена';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=my_links';

$choise_db[$num]['name']='Добавить новую ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_my_links';

$message='Ссылка была удалена';
echo si_message_box ('Delete',$message, $choise_db, 1, '350px');
my_exit();

}

if ($err)
{
//выводим ошибки и останавливаем скрипт

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок для обмена';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=my_links';

$choise_db[$num]['name']='Добавить новую ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_my_links';

$choise_db[$num]['name']='Продолжить редактирование';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_my_links&id='.$id;

echo si_message_box ('Errors',$err, $choise_db, 1, '350px');
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
Содержание:<br>
<?=l_textarea ('name=CODE style="width:100%" rows="10"', $links_variants_db[0]['FIELD_CODE']) ?><br><br>

Короткое название:<br>
<?=l_textarea ('name=SHORT style="width:100%" rows="3"', $links_variants_db[0]['FIELD_SHORT_NAME']) ?><br><br>

Код баннера:<br>
<?=l_textarea ('name=BANNER style="width:100%" rows="3"', $links_variants_db[0]['FIELD_BANNER']) ?><br><br>

</fieldset>

<fieldset class="alert_fieldset"><legend>Публикация:</legend>
<input type="radio" value="0" <? if (intval ($links_variants_db[0]['FIELD_ENABLE'])==0) echo 'checked'; ?> name="ENABLE"> - <span class="normal_red"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1" <? if (intval ($links_variants_db[0]['FIELD_ENABLE'])==1) echo 'checked'; ?> name="ENABLE"> - <span class="normal_green"><b>On</b> (включено)</span>
</fieldset>


<center>
<?=l_buttion ('name=ok', 'Принять изменения') ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?=l_spec_buttion ('delete', ' style="background: #fedada;"', 'Удалить cсылку', $form_name); ?>
</form>

