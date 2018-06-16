<?
//"быстрый диалог"

define  ('Root_Dir','../../'); 	//корень сайта относительно ЭТОГО скрипта
define  ('In_Buffer','Off'); 	//включение буфферизации вывода. Если контанта не определена, значит буфферизация выключена

include (Root_Dir.'_shell/start_site.php'); 	//инициализация
include (Root_Dir.'_shell/functions/admin_functions.php');	//загрузка админских функций

//запускаем скрипт проверки авторизации.
//если авторизация не пройдена, то выполнение будет остановленно и будет выведенеа форма регистрации.
//также в этом скрипте устанавливается флаг инициализации админки.
include (Root_Dir.'_admin/admin_init.php');


include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц
include_once ('fast_dialog_functions.php');	//функи для работы в этом диалоге.
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для отправки сообщений о действиях с каталогом.


$form_name='fastdlg';

//---------------
//получаем инфу о ссылке id
$item_db=links_get_item_full_data ($id);
//---------------


?>
<head>
<title>"Быстрый диалог"</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body style="margin:3px;">
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">

<?
//обрабатываем действия.
if ($_POST['ok'])
{

//выполняем действия:
$ex='';
for ($i=0; $i<count($_POST['ACT']); $i++) $ex.=link_exec_act ($_POST['ACT'][$i], $id, $links_settings_db, $_POST['send_fast_mail']).'<br>';

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?id='.$id;

$choise_db[$num]['name']='Закрыть окно';
$choise_db[$num++]['link']='javascript:window.close();';

$message='Действия применены!<br></b>'.$ex.'<b>';
echo si_message_box ('Ok',$message, $choise_db, 0, '350px');
my_exit();

}
?>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">"Быстрый диалог"</td>
</tr>
</table>
<fieldset class="normal_fieldset"><legend>Информация о ссылке:</legend>
<?=links_get_fast_info ($item_db, $links_settings_db) ?>
</fieldset>

<fieldset class="normal_fieldset"><legend>Доступные действия:</legend>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
  <tr class="middletitle">
   <td>
<?
//формируем список действий
$act_db=array();
$act_db[]='off';
$act_db[]='on';
$act_db[]='send_sorry';
$act_db[]='recicler';
$act_db[]='autoperenos';
$act_db[]='no_autoperenos';
$act_db[]='please_set_retry';
$act_db[]='gde_obratka';
$act_db[]='good_message';
$act_db[]='add_bad_ball';
$act_db[]='dec_bad_ball';
$act_db[]='no_is_new';
$act_db[]='nah';
echo links_get_act_table ('ACT', $act_db, '');   
?>
<div style="padding:5px;" class="alert_fieldset"><input type="checkbox" name="send_fast_mail" value="1" <?=$links_settings_db['DEF_FAST_MAIL'] ?> > - Оправить e-mail уведомление пользователю о выполненный действиях</div>
   </td>
  </tr>
 <tr class="rowlight">
   <td align="center" height="30"><?=l_buttion ('name=ok', 'Применить действия') ?></td>
  </tr>
</table>
</fieldset>

<?=links_get_dialog_submenu ($item_db); ?>
</form>
</body>
<?
include (Root_Dir.'_shell/end_site.php');	//завершение работы сайта
?>