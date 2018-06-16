<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.

//собираем данные о менюшках в админке по всем каталогам, в которых есть файл admin_menu.php
//формат менюшки:
//$menu[n]['menu_signed'] 	- ключ связки с загруженным модулем.
//$menu[n]['sort'] 	- индекст сортировки
//$menu[n]['name'] 	- название пункта меню
//$menu[n]['link'] 	- ссылка для этого пункта

$admin_menu_db=get_admin_menu ();

//сортируем менюв порядке возрастания параметра sort
$tmp_db=array();
for ($z=0; $z<=count($admin_menu_db); $z++)
for ($i=0; $i<=count($admin_menu_db); $i++)
if ($admin_menu_db[$i+1]['sort'])
if ($admin_menu_db[$i+1]['sort']<$admin_menu_db[$i]['sort'])
{
$tmp_db=$admin_menu_db[$i+1];
$admin_menu_db[$i+1]=$admin_menu_db[$i];
$admin_menu_db[$i]=$tmp_db;
}

$open_menu_signed='';
//вычисляем ключ menu_signed у текущей акции.
for ($i=0; $i<count($actions_db['full_data']); $i++) if (my_in_array(array_keys ($actions_db['full_data'][$i]['actions']), $actions)) $open_menu_signed=$actions_db['full_data'][$i]['menu_signed'];

//перебираем пункты меню
for ($i=0; $i<count($admin_menu_db);$i++) 
if ($admin_menu_db[$i]['link'])
{

$this_param=' class="admin_menu_no_sel" onMouseOver="this.className=\'admin_menu_sel\';" onMouseOut="this.className=\'admin_menu_no_sel\';"';
if ($open_menu_signed==$admin_menu_db[$i]['menu_signed']) 
{
$this_param=' class="admin_menu_sel" ';
$path_db[0]['open_menu']=$admin_menu_db[$i];	//записываем в массив данные по открытоу в данный момент раздеу. Что бы потом вывести синею надпись сверху контента.
}

?>
<div <?=$this_param ?>><a href="<?=$_SERVER['PHP_SELF'].$admin_menu_db[$i]['link'] ?>"><?=$admin_menu_db[$i]['name'] ?></a></div>
<?
}
else echo $admin_menu_db[$i]['name'];
?>