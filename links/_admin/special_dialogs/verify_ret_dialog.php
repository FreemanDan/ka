<?
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


//---------------
//получаем инфу о ссылке id
$item_db=links_get_item_full_data ($id);
//---------------


?>
<head>
<title>Диалог проверки обратной ссылки</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body style="margin:3px;">

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">Проверка обратной ссылки</td>
</tr>
</table>

<fieldset class="normal_fieldset"><legend>Информация о ссылке:</legend>
<?=links_get_fast_info ($item_db, $links_settings_db) ?>
</fieldset>

<?
//проверяем обратку на указанной странице

$set_ret_link_flag=0;	//флаг.. былали найдена обратка.
$tmp_str='';
if ($item_db['FIELD_RET_LINK_ADDR'])
{
//адрес задан.. проверяем.
$link_list_db=get_all_my_banner_links ();		//выбираем все ссылки из включенных баннеров для обмена.

if (link_verify_set_link ($link_list_db, $item_db['FIELD_RET_LINK_ADDR'])) 
 {
  $tmp_str.='Обратная ссылка найдена!';
  $set_ret_link_flag=1;
 } 
else $tmp_str.='Обратная ссылка (или ссылка из баннера для обмена) не найдена!';

}
else $tmp_str.='Адрес не задан!';

$fclass='alert_fieldset';
if ($set_ret_link_flag) $fclass='good_fieldset';
?>

<fieldset class="<?=$fclass ?>"><legend>Проверка обратной ссылки:</legend>
<div align="center"><br><b><?=$tmp_str ?></b><br><br></div>
</fieldset>


<?=links_get_dialog_submenu ($item_db); ?>

</body>
<?
include (Root_Dir.'_shell/end_site.php');	//завершение работы сайта
?>