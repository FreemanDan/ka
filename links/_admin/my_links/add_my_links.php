<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / <a href="<?=$_SERVER['PHP_SELF'].'?actions=my_links' ?>">Список ссылок для обмена </a> / Создание ссылки для обмена</div>
<?


if ($_POST['add'])
{
//нажато "Принять изменения"


$CODE=trim ($_POST['CODE']);
$SHORT=trim ($_POST['SHORT']);
$ENABLE=intval ($_POST['ENABLE']);
$BANNER=trim ($_POST['BANNER']);

if (!eto_ne_pusto($CODE)) $err.='Не введено содержание!<br>';

if (!$err)
{
//всё оки

//получаем наименьший индекс сортировки
$tmp_db=SI_sql_query("select MAX(FIELD_SORT) AS MS from ".Base_Prefix."links_variants");
$next_srt=$tmp_db[0]['MS']+1;

mysql_query("insert into ".Base_Prefix."links_variants (FIELD_SORT) values('$next_srt')");
$id_ins=mysql_insert_id(); //id добавленной 

MYSQL_QUERY("UPDATE ".Base_Prefix."links_variants SET FIELD_CODE='$CODE' WHERE id='$id_ins'");
MYSQL_QUERY("UPDATE ".Base_Prefix."links_variants SET FIELD_SHORT_NAME='$SHORT' WHERE id='$id_ins'");
MYSQL_QUERY("UPDATE ".Base_Prefix."links_variants SET FIELD_BANNER='$BANNER' WHERE id='$id_ins'");
MYSQL_QUERY("UPDATE ".Base_Prefix."links_variants SET FIELD_ENABLE='$ENABLE' WHERE id='$id_ins'");

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок для обмена';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=my_links';

$choise_db[$num]['name']='Добавить ещё ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_my_links';

$choise_db[$num]['name']='Редактировать добавленныую';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_my_links&id='.$id_ins;

$message='Ссылка для обмена добавленна';
echo si_message_box ('Add',$message, $choise_db, 0, '350px');
my_exit();
}

}


if ($err)
{
//ашыпки
//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку ссылок для обмена';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=my_links';

$choise_db[$num]['name']='Добавить ещё ссылку';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_my_links';

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
Содержание HTML:<br>
<?=l_textarea ('name=CODE style="width:100%" rows="10"', '') ?><br><br>


Короткое название:<br>
<?=l_textarea ('name=SHORT style="width:100%" rows="3"', $links_variants_db[0]['FIELD_SHORT_NAME']) ?><br><br>

Код баннера:<br>
<?=l_textarea ('name=BANNER style="width:100%" rows="3"', $links_variants_db[0]['FIELD_BANNER']) ?><br><br>
</fieldset>

<fieldset class="alert_fieldset"><legend>Публикация:</legend>
<input type="radio" value="0" name="ENABLE"> - <span class="normal_red"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1" checked  name="ENABLE"> - <span class="normal_green"><b>On</b> (включено)</span>
</fieldset>

<center>
<?=l_buttion ('name=add', 'Добавить') ?>
</form>

