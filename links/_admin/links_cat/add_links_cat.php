<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / <a href="<?=$_SERVER['PHP_SELF'].'?actions=links_cat' ?>">Список категорий</a> / Создание категории </div>
<?


if ($_POST['add'])
{
//нажато "Принять изменения"


$NAME=trim ($_POST['NAME']);
$ENABLE=intval ($_POST['ENABLE']);

$PAGE_NAME=trim ($_POST['PAGE_NAME']);
$META_TITLE=trim ($_POST['META_TITLE']);
$META_KEYWORDS=trim ($_POST['META_KEYWORDS']);
$META_DESCRIPTION=trim ($_POST['META_DESCRIPTION']);


if (!eto_ne_pusto($NAME)) $err.='Не введено название!<br>';

if (!$err)
{
//всё оки

//получаем наименьший индекс сортировки
$tmp_db=SI_sql_query("select MAX(FIELD_SORT) AS MS from ".Base_Prefix."links_category");
$next_srt=$tmp_db[0]['MS']+1;

SI_sql_query("insert into ".Base_Prefix."links_category (FIELD_SORT) values('$next_srt')");
$id_ins=mysql_insert_id(); //id добавленной 

SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_NAME='$NAME' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_ENABLE='$ENABLE' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_PAGE_NAME='$PAGE_NAME' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_META_TITLE='$META_TITLE' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_META_KEYWORDS='$META_KEYWORDS' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_META_DESCRIPTION='$META_DESCRIPTION' WHERE id='$id_ins'");

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку категорий';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_cat';

$choise_db[$num]['name']='Добавить ещё категорию';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_cat';

$choise_db[$num]['name']='Редактировать добавленныую';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_links_cat&id='.$id_ins;

$message='Категория добавленна';
echo si_message_box ('Add',$message, $choise_db, 0, '350px');
my_exit();
}

}


if ($err)
{
//ашыпки
//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку категорий';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_cat';

$choise_db[$num]['name']='Добавить ещё категорию';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_cat';

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
Название категории:<br>
<?=l_text ('name=NAME style="width:100%"', '') ?><br><br>
</fieldset>

<fieldset class="alert_fieldset"><legend>Публикация:</legend>
<input type="radio" value="0" name="ENABLE"> - <span class="normal_red"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1" checked  name="ENABLE"> - <span class="normal_green"><b>On</b> (включено)</span>
</fieldset>

<fieldset class="normal_fieldset"><legend>Дополнительные  параметры:</legend>
Название страницы:<br>
<?=l_text ('name=PAGE_NAME style="width:100%"', '') ?>
<div class="help">Если оставить пустым, то будет использоваться название категории</div>
<br>

TITLE:<br>
<?=l_text ('name=META_TITLE style="width:100%"', '') ?>
<div class="help">Если оставить пустым, то будет использоваться название категории</div>
<br>

KEYWORDS:<br>
<?=l_textarea ('name=META_KEYWORDS rows="4" style="width:100%"', '') ?><br><br>

DESCRIPTION:<br>
<?=l_textarea ('name=META_DESCRIPTION rows="4" style="width:100%"', '') ?>

</fieldset>

<div align="center">
<?=l_buttion ('name=add', 'Добавить') ?>
</div>

</form>

