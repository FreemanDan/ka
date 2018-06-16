<?
//Диалог отправки сообщения партнёру.
//скрипт проверки обратной ссылки

define  ('Root_Dir','../../'); 	//корень сайта относительно ЭТОГО скрипта
define  ('In_Buffer','Off'); 	//включение буфферизации вывода. Если контанта не определена, значит буфферизация выключена

include (Root_Dir.'_shell/start_site.php'); 	//инициализация
include (Root_Dir.'_shell/functions/admin_functions.php');	//загрузка админских функций

//запускаем скрипт проверки авторизации.
//если авторизация не пройдена, то выполнение будет остановленно и будет выведенеа форма регистрации.
//также в этом скрипте устанавливается флаг инициализации админки.
include (Root_Dir.'_admin/admin_init.php');

include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц
include_once ('fast_dialog_functions.php');	//функи для работы в этом диалоге.
include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для отправки сообщений о действиях с каталогом.


$form_name='msgdlg';

//---------------
//получаем инфу о ссылке id
$item_db=links_get_item_full_data ($id);
//---------------


?>
<head>
<title>Отправка сообщения для "<?=lecho($item_db['FIELD_DOMAIN']) ?>"</title>
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

//отправляем.
$subj=si_filters ($_POST['subj'], '1,5');
$text=si_filters ($_POST['text'], '1,5');

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='Вернуться';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?id='.$id;

$choise_db[$num]['name']='Закрыть окно';
$choise_db[$num++]['link']='javascript:window.close();';

$message='Письмо отправлено!';
echo si_message_box ('Ok',$message, $choise_db, 0, '350px');
my_exit();

}
?>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">Отправка сообщения для "<?=lecho($item_db['FIELD_DOMAIN']) ?>"</td>
</tr>
</table>

<br>
<b>Тема:</b><br>
<?=l_text ('name="subj" style="width:100%"', 'Сообщение от модератора каталога ссылок сайта "'.$_SERVER['HTTP_HOST'].'"'); ?>
<br><br>

<b>Текст:</b><br>
<?
//формируем шаблон письма:
$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n"."\r\n"."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>'."\r\n"."\r\n"."\r\n"."\r\n";
$text.=links_generate_link_addr_block ($item_db, $links_settings_db)."\r\n";	//информация о адресе расположения партнёрской ссылки у нас

$text.=links_generate_moder_block ($item_db, $links_settings_db)."\r\n";	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

echo l_textarea ('name="text" rows="35" style="width:100%"', $text);

?>
   
<?=get_tabs () ?>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
 <tr class="rowlight">
   <td align="center" height="30"><?=l_buttion ('name=ok', 'Отправить письмо') ?></td>
  </tr>
</table>
<?=get_tabs () ?>

<?=links_get_dialog_submenu ($item_db); ?>
</form>
</body>
<?
include (Root_Dir.'_shell/end_site.php');	//завершение работы сайта
?>