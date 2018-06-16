<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.
//редактирование настроек.

include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружаем доп. функи для этого типа страниц

$form_name='links_meta'.Base_Prefix;

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Мета данные</div>
<?

//========================================================================

if ($_POST['ok'])
{
//записываем изменения.

if ($_POST['meta'])
foreach ($_POST['meta'] as $key => $value)
{
SI_sql_query("UPDATE ".Base_Prefix."links_meta 
	SET 
	FIELD_PAGE_NAME='".trim ($value['PAGE_NAME'])."',
	FIELD_TITLE='".trim ($value['TITLE'])."',
	FIELD_KEYWORDS='".trim ($value['KEYWORDS'])."',
	FIELD_DESCRIPTION='".trim ($value['DESCRIPTION'])."'
	WHERE FIELD_KEY='".adds($key)."'
	");

}

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

?>
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">

<?
//получаем все мета записи.
$links_meta_db=SI_sql_query("select * from ".Base_Prefix."links_meta ORDER BY id ASC");

for ($i=0; $i<count($links_meta_db); $i++)
{
?>
<fieldset class="normal_fieldset"><legend><?=$links_meta_db[$i]['FIELD_NAME'] ?></legend>
Название страницы:<br>
<?=l_text ('name="meta['.$links_meta_db[$i]['FIELD_KEY'].'][PAGE_NAME]" style="width:100%"', $links_meta_db[$i]['FIELD_PAGE_NAME']) ?>
<br><br>

TITLE:<br>
<?=l_text ('name="meta['.$links_meta_db[$i]['FIELD_KEY'].'][TITLE]" style="width:100%"', $links_meta_db[$i]['FIELD_TITLE']) ?>
<br><br>

KEYWORDS:<br>
<?=l_textarea ('name="meta['.$links_meta_db[$i]['FIELD_KEY'].'][KEYWORDS]" rows="3" style="width:100%"', $links_meta_db[$i]['FIELD_KEYWORDS']) ?>
<br><br>

DESCRIPTION:<br>
<?=l_textarea ('name="meta['.$links_meta_db[$i]['FIELD_KEY'].'][DESCRIPTION]" rows="3" style="width:100%"', $links_meta_db[$i]['FIELD_DESCRIPTION']) ?>
</fieldset>
<?
}
?>

<div align="center"><?=l_buttion ('name=ok', 'Принять изменения') ?></div>
</form>