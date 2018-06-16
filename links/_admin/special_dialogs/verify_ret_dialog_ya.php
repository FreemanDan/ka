<?
//скрипт проверки обратной ссылки через Яндекс
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
include_once ('verify_dialog_ya_functions.php');	//функи для работы вс яндексом


//---------------
//получаем инфу о ссылке id
$item_db=links_get_item_full_data ($id);

$link_page_addr=links_get_link_addr ($id, $links_settings_db);	//получаем ссылку на страницу, где расположена эта ссылка.
//---------------


?>
<head>
<title>Диалог проверки обратной ссылки через Yandex</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body style="margin:3px;">

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">Проверка обратной ссылки через Yandex</td>
</tr>
</table>

<fieldset class="normal_fieldset"><legend>Информация о ссылке:</legend>
<?=links_get_fast_info ($item_db, $links_settings_db) ?>
</fieldset>

<?
//получаем список страниц с обраткой через яндекс.
$set_ret_link_flag=0;	//флаг нахождения в яндексе.
$tmp_str='';

$ret_ya_db=links_get_ya_ret ($_SERVER['HTTP_HOST'], $item_db['FIELD_DOMAIN']);
if (!$ret_ya_db) $tmp_str.='
<br>
<div align="center">
<b>Страницы с обратной ссылкой не найдены на сайте "'.lecho ($item_db['FIELD_DOMAIN']).'"</b><br>
<span class="help">
Попробуйте перейти по нижепреведённой ссылке и проверить, что возможно Яндекс за основной сайт принимает одно из зеркал сайта "'.lecho ($item_db['FIELD_DOMAIN']).'".
Также обратите внимание, что для скрипта проверки сайты с "www." и без него - это РАЗНЫЕ сайты.
</span>
</div>
<br>';
else
{
//выводим ссылки на страницу партнёра на которых яндекс нашёл нашё ссылку.
$set_ret_link_flag=1;
$tmp_str.='<table width="100%" cellspacing="1" cellpadding="2" class="maintable">';
for ($i=0; $i<count($ret_ya_db); $i++)
{
$tab_bg='rowlight';
if (($i/2)<>floor($i/2)) $tab_bg='rowdark';

if ($ret_ya_db[$i]==$item_db['FIELD_RET_LINK_ADDR']) $tab_bg='rowred';	//это назначенная в данный момент сслка.
$tmp_str.='<tr align="center" class="'.$tab_bg.'" onclick="this.className=\'rowyellow\';" height="25">';
$tmp_str.='<td align="left" width="90%"><a href="'.lecho ($ret_ya_db[$i]).'" target="_blank">'.lecho ($ret_ya_db[$i]).'</a></td>'; 
$tmp_str.='<td width="10%"><a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&subactions=set_new_link&new='.base64_encode ($ret_ya_db[$i]).'" title="записать в качестве обратной ссылки этот адрес">Set</a></td>'; 
$tmp_str.='</tr>';
}
$tmp_str.='</table>';

}

$fclass='alert_fieldset';
if ($set_ret_link_flag) $fclass='good_fieldset';

?>

<fieldset class="<?=$fclass ?>"><legend>Найденные страницы с обратной ссылкой:</legend>
<?=$tmp_str ?>
</fieldset>

<fieldset class="normal_fieldset"><legend>Ссылка для проверки:</legend>
<div style="margin-top:5px;margin-bottom:5px;">
<a href="<?=lecho (links_get_ya_ret_link ($_SERVER['HTTP_HOST'], $item_db['FIELD_DOMAIN'])) ?>" target="_blank"><?=lecho (links_get_ya_ret_link ($_SERVER['HTTP_HOST'], $item_db['FIELD_DOMAIN'])) ?></a>
</div>
</fieldset>


<?=links_get_dialog_submenu ($item_db); ?>

</body>
<?
include (Root_Dir.'_shell/end_site.php');	//завершение работы сайта
?>