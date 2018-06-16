<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


$links_category_db=SI_sql_query("select * from ".Base_Prefix."links_category WHERE id='$id'");

if (!$links_category_db) $err.='Запись с id='.$id.' не найдена!<br>';

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / <a href="<?=$_SERVER['PHP_SELF'].'?actions=links_cat' ?>">Список категорий</a> / Редактирование категории </div>
<?


if ($_POST['ok'])
if (!$err)
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
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_NAME='$NAME' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_ENABLE='$ENABLE' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_PAGE_NAME='$PAGE_NAME' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_META_TITLE='$META_TITLE' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_META_KEYWORDS='$META_KEYWORDS' WHERE id='$id'");
SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_META_DESCRIPTION='$META_DESCRIPTION' WHERE id='$id'");

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку категорий';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_cat';

$choise_db[$num]['name']='Добавить новую категорию';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_cat';

$choise_db[$num]['name']='Продолжить редактирование';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_links_cat&id='.$id;

$message='Изменения приняты.';
echo si_message_box ('Ok',$message, $choise_db, 0, '350px');
my_exit();
}

}

if ($_POST['delete'])
if (!$err)
{
//нажато "Удалить"

//проверяем.. нетли ссылок в категории.
$tmp_db=SI_sql_query("select id from ".Base_Prefix."links_items WHERE FIELD_LINK_CAT='$id' LIMIT 1");
if ($tmp_db) $err.='Категория не пуста!<br>';

if (!$err)
{
MYSQL_QUERY ("DELETE FROM ".Base_Prefix."links_category WHERE id='$id'");

//генерим окно с сообщением
$choise_db[$num]['name']='Вернуться к списку категорий';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_cat';

$choise_db[$num]['name']='Добавить новую категорию';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_cat';

$message='Категория была удалена';
echo si_message_box ('Delete',$message, $choise_db, 1, '350px');
my_exit();
}
}

if ($err)
{
//выводим ошибки и останавливаем скрипт

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться к списку категорий';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=links_cat';

$choise_db[$num]['name']='Добавить новую категорию';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=add_links_cat';

$choise_db[$num]['name']='Продолжить редактирование';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions=edit_links_cat&id='.$id;

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
Название категории:<br>
<?=l_text ('name=NAME style="width:100%"', $links_category_db[0]['FIELD_NAME']) ?><br><br>
</fieldset>

<fieldset class="alert_fieldset"><legend>Публикация:</legend>
<input type="radio" value="0" <? if (intval ($links_category_db[0]['FIELD_ENABLE'])==0) echo 'checked'; ?> name="ENABLE"> - <span class="normal_red"><b>Off</b> (выключено)</span><br>
<input type="radio" value="1" <? if (intval ($links_category_db[0]['FIELD_ENABLE'])==1) echo 'checked'; ?> name="ENABLE"> - <span class="normal_green"><b>On</b> (включено)</span>
</fieldset>


<fieldset class="normal_fieldset"><legend>Дополнительные  параметры:</legend>
Название страницы:<br>
<?=l_text ('name=PAGE_NAME style="width:100%"', $links_category_db[0]['FIELD_PAGE_NAME']) ?>
<div class="help">Если оставить пустым, то будет использоваться название категории</div>
<br>

TITLE:<br>
<?=l_text ('name=META_TITLE style="width:100%"', $links_category_db[0]['FIELD_META_TITLE']) ?>
<div class="help">Если оставить пустым, то будет использоваться название категории</div>
<br>

KEYWORDS:<br>
<?=l_textarea ('name=META_KEYWORDS rows="4" style="width:100%"', $links_category_db[0]['FIELD_META_KEYWORDS']) ?><br><br>

DESCRIPTION:<br>
<?=l_textarea ('name=META_DESCRIPTION rows="4" style="width:100%"', $links_category_db[0]['FIELD_META_DESCRIPTION']) ?>

</fieldset>


<div align="center">
<?=l_buttion ('name=ok', 'Принять изменения') ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?=l_spec_buttion ('delete', ' style="background: #fedada;"', 'Удалить категорию', $form_name); ?>
</div>

</form>

