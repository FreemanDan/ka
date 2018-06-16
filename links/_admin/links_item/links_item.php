<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


include_once (Root_Dir.'_shell/functions/page_bar_funtions.php');	//загрузка функций для управления страницами.
include_once (Root_Dir.'_shell/functions/mail_functions.php');		//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц

$max_on_page=$links_settings_db['REC_ON_PAGE_ADMIN']; 		//максимум записей на странице из настроек страницы.
$form_name=Base_Prefix.'links'.$actions;

if ($_REQUEST['ok'])
{
//селектор
$_SESSION[$form_name]['search']=$_REQUEST['search'];
$_SESSION[$form_name]['enable']=warn($_REQUEST['enable']);
$_SESSION[$form_name]['is_new']=warn($_REQUEST['is_new']);
$_SESSION[$form_name]['for_del']=warn($_REQUEST['for_del']);
$_SESSION[$form_name]['view_category']=warn($_REQUEST['view_category']);
$page=1;
}

if ($_POST['clear'] || $page=='clear')
{
//сбрасываем данные отображения
$_SESSION[$form_name]['search']='';
$_SESSION[$form_name]['enable']='all';
$_SESSION[$form_name]['is_new']='all';
$_SESSION[$form_name]['for_del']='all';
$_SESSION[$form_name]['view_category']='no_black_list';
$page=1;
}

$page=init_page ($actions, $page); //достём страниицу из сессии или очищаем страницу

//установки "по умолчанию"
if (!$_SESSION[$form_name]['view_category']) $_SESSION[$form_name]['view_category']='all';
if (!eto_ne_pusto ($_SESSION[$form_name]['enable'])) $_SESSION[$form_name]['enable']='all';
if (!eto_ne_pusto ($_SESSION[$form_name]['is_new'])) $_SESSION[$form_name]['is_new']='all';
if (!eto_ne_pusto ($_SESSION[$form_name]['for_del'])) $_SESSION[$form_name]['for_del']='all';

$search=$_SESSION[$form_name]['search'];
$view_category=$_SESSION[$form_name]['view_category'];
$enable=$_SESSION[$form_name]['enable'];
$is_new=$_SESSION[$form_name]['is_new'];
$for_del=$_SESSION[$form_name]['for_del'];


$adv_query="";	//доп. условие в запрос.

if ($search)
{
//задана строка поиска... поехали составлять доп. условие в запрос.
$adv_query.=" AND (
		".Base_Prefix."links_items.FIELD_DOMAIN LIKE '%".get_string_for_like ($search)."%' 
		OR ".Base_Prefix."links_items.FIELD_NAME LIKE '%".get_string_for_like ($search)."%'
		) "; 
}

if ($view_category=='no_black_list') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_LINK_CAT<>'".$links_settings_db['BLACK_LIST_ID']."' ";
if ('a'.intval($view_category)=='a'.$view_category) $adv_query.=" AND ".Base_Prefix."links_items.FIELD_LINK_CAT='$view_category' ";
if ($enable!='all') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_ENABLE='$enable' ";
if ($is_new!='all') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_IS_NEW='$is_new' ";
if ($for_del!='all') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_FOR_DEL='$for_del' ";

//получаем всю базу для перемещений и отслеживания возможности перемещения
$full_links_items_db=SI_sql_query("select 
	".Base_Prefix."links_items.id, 
	".Base_Prefix."links_items.FIELD_SORT 
	from ".Base_Prefix."links_items 
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE 	".Base_Prefix."links_items.id>'0' 
	".$adv_query." 
	GROUP BY ".Base_Prefix."links_items.id
	ORDER BY ".Base_Prefix."links_items.FIELD_SORT ASC");
$total_count=count($full_links_items_db);	//получаем всего записей на этой странице

if (!$err)
switch ($subactions) 
{
   case 'up': 
	//поднимаем элемент вверх
	$this_num='';
	for ($i=0; $i<count ($full_links_items_db); $i++) if ($full_links_items_db[$i]['id']==$subid) $this_num=$i;
	if ($this_num)
	if ($full_links_items_db[$this_num-1]['id'])
	{
	SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_SORT=".$full_links_items_db[$this_num-1]['FIELD_SORT']." WHERE id='$subid'");
	SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_SORT=".$full_links_items_db[$this_num]['FIELD_SORT']." WHERE id=".$full_links_items_db[$this_num-1]['id']);
	}
   break;
   case 'down': 
	//опускаем элемент вниз
	$this_num='';
	for ($i=0; $i<count ($full_links_items_db); $i++) if ($full_links_items_db[$i]['id']==$subid) $this_num=$i;
	if ($full_links_items_db[$this_num+1]['id'])
	{
	SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_SORT=".$full_links_items_db[$this_num+1]['FIELD_SORT']." WHERE id='$subid'");
	SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_SORT=".$full_links_items_db[$this_num]['FIELD_SORT']." WHERE id=".$full_links_items_db[$this_num+1]['id']);
	}   
   break;
} 

//получаем записи в зависимости от страницы
$limit_str=pages_get_lim_str ($max_on_page, $page); //получаем строку для лимита
$links_items_db=SI_sql_query("select 
	".Base_Prefix."links_items.id, 
	".Base_Prefix."links_items.FIELD_LINK_CAT, 
	".Base_Prefix."links_items.FIELD_SORT, 
	".Base_Prefix."links_items.FIELD_CREATE_DATE, 
	".Base_Prefix."links_items.FIELD_VERIFY_DATE, 
	".Base_Prefix."links_items.FIELD_BAD_BALLS, 
	".Base_Prefix."links_items.FIELD_DOMAIN, 
	".Base_Prefix."links_items.FIELD_NAME, 
	".Base_Prefix."links_items.FIELD_RET_LINK_ADDR, 
	".Base_Prefix."links_items.FIELD_ENABLE, 
	".Base_Prefix."links_items.FIELD_IS_NEW, 
	".Base_Prefix."links_items.FIELD_FOR_DEL, 
	".Base_Prefix."links_items.FIELD_DISABLE_AUTOBOT, 
	".Base_Prefix."links_category.FIELD_NAME AS CNAME 
	from ".Base_Prefix."links_items 
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE 	".Base_Prefix."links_items.id>'0' 
	".$adv_query." 
	GROUP BY ".Base_Prefix."links_items.id
	ORDER BY ".Base_Prefix."links_items.FIELD_SORT ASC
	".$limit_str);

//получаем код планки со страницами с настройками для админки.
$page_bar=get_pages_bar ($total_count, $max_on_page, $page, $_SERVER['PHP_SELF'].'?actions='.$actions.'&page={page_num}', get_admin_page_bar_settings ());


//получаем название категории, определённой как "black list"
$black_list_name='';
if ($links_settings_db['BLACK_LIST_ID'])
{
$tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE id='".intval($links_settings_db['BLACK_LIST_ID'])."'");
$black_list_name=$tmp_db[0]['FIELD_NAME'];
}

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Список ссылок </div>

<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
 <tr class="middletitle">
  <td align="right"><?
  $enable_db=array();
  $enable_db['all']='Не учитывать';
  $enable_db['0']='Off';
  $enable_db['1']='On';
  echo 'Публикация: '.l_select ('name="enable"', $enable_db, $enable);
  echo '&nbsp;&nbsp;&nbsp;';
  
  $is_new_db=array();
  $is_new_db['all']='Не учитывать';
  $is_new_db['0']='Только не новые';
  $is_new_db['1']='Только новые';
  echo 'Новые: '.l_select ('name="is_new"', $is_new_db, $is_new);
  echo '&nbsp;&nbsp;&nbsp;';

if ($links_settings_db['BLACK_LIST_ID'])
{
  $for_del_db=array();
  $for_del_db['all']='Не учитывать';
  $for_del_db['0']='Только не для переноса в "'.$black_list_name.'"';
  $for_del_db['1']='Только для переноса в "'.$black_list_name.'"';
  echo 'Автоперенос: '.l_select ('name="for_del"', $for_del_db, $for_del);
  echo '&nbsp;&nbsp;&nbsp;';
}
  ?></td>
 </tr>
</table>

<?=get_tabs () ?>

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
 <tr class="middletitle">
  <td align="right"><?
  //получаем все группы.
  $tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category ORDER BY FIELD_SORT ASC");
  $view_category_db=array();
  $view_category_db['all']='Все группы';
  if ($black_list_name) $view_category_db['no_black_list']='Все, кроме "'.$black_list_name.'"';
  $view_category_db=si_array_push ($view_category_db, si_generate_afa ($tmp_db, 'id', 'FIELD_NAME'));  
  echo 'Категория: '.l_select ('name="view_category"', $view_category_db, $view_category);
  echo '&nbsp;&nbsp;&nbsp;';
  
  echo 'Поиск: '.l_text ('name="search" size="20"', $search);
  echo '&nbsp;&nbsp;&nbsp;';
  
  echo l_buttion ('name=ok', 'Принять');
  echo '&nbsp;&nbsp;&nbsp;';
  echo l_buttion ('name=clear', 'Сброс');
  echo '&nbsp;&nbsp;&nbsp;';
  ?></td>
 </tr>
</table>

<?=get_tabs ().$page_bar.get_tabs () ?>

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">Список ссылок</td>
</tr>
<tr align="center" class="middletitle">
 <td width="5%"><b>№</td>
 <td><b>Название</td> 
 <td><b>Сайт</td> 
 <td><b>Категория</td> 
 <td><b>Дата</td> 
 <td><b>Штраф</td> 
 <td width="4%"><b>Pub.</b></td> 
 <td width="4%">&nbsp;</td> 
 <td width="4%">&nbsp;</td>
 <td width="4%">&nbsp;</td>
 <td width="4%"><b>Ред.</td>
</tr>
<?

for ($i=0;$i<count ($links_items_db); $i++)
{
$tab_bg='rowlight';
if (($i/2)<>floor($i/2)) $tab_bg='rowdark';

?>
<tr align="center" class="<?=$tab_bg ?>" onclick="this.className='rowyellow';">
 <td><?=get_numeric ($max_on_page, $page, $i) ?></td>
 <td align="left"><?
 echo lecho ($links_items_db[$i]['FIELD_NAME']);
 if ($links_items_db[$i]['FIELD_IS_NEW']) echo ' <span class="normal_green"><b>*NEW*</b> </span> ';
 if ($links_items_db[$i]['FIELD_FOR_DEL']) echo ' <span class="normal_red"><b>Автоперенос</b> </span> ';
 if ($links_items_db[$i]['FIELD_DISABLE_AUTOBOT']) echo ' <span class="normal_red"><b>No Autobot</b> </span> ';
 ?></td>
 <td><a href="http://<?=lecho ($links_items_db[$i]['FIELD_DOMAIN']) ?>" target="_blank"><?=lecho ($links_items_db[$i]['FIELD_DOMAIN']) ?></a></td>
 <td><?
 //навзание категори..если это блэк лист. то выделаяем красным. FIELD_LINK_CAT
 if ($links_items_db[$i]['FIELD_LINK_CAT']==$links_settings_db['BLACK_LIST_ID']) echo '<span class="normal_red">'.lecho ($links_items_db[$i]['CNAME']).'</span>';
		else echo lecho ($links_items_db[$i]['CNAME']);
 ?></td>
 <td><div nowrap><?=date ('d-m', $links_items_db[$i]['FIELD_CREATE_DATE']) ?></div><div><?=date ('Y', $links_items_db[$i]['FIELD_CREATE_DATE']) ?></div></td>
 <td><?=intval ($links_items_db[$i]['FIELD_BAD_BALLS']) ?></td>
 <td><?=si_admin_get_enable ($links_items_db[$i]['FIELD_ENABLE'], 0) ?></td>
 <td align="left"><? 
 
 //выводм картиночки для перехода в "быстрый диалог"
 $fast_dialog='javascript:popUpOpen (\''.Global_WWW_Path.'_admin/special_dialogs/fast_dialog.php?id='.$links_items_db[$i]['id'].'\', 700, 630);';
 echo '<a href="'.$fast_dialog.'"><img src="icons/fast_dialog.gif" border="0" alt="Быстрый диалог" hspace="1"></a>';

 //выводм картиночки для просмотра обратки на сайте патнёра
 $ret_dialog='javascript:popUpOpen (\''.Global_WWW_Path.'_admin/special_dialogs/verify_ret_dialog.php?id='.$links_items_db[$i]['id'].'\', 700, 630);';
 if ($links_items_db[$i]['FIELD_RET_LINK_ADDR']) $ret_img='<img src="icons/view_ret_page.gif" border="0" alt="Проверить обратную ссылку" hspace="1">';
	else $ret_img='<img src="icons/view_ret_page_off.gif" border="0" alt="Адрес обратной ссылки не задан" hspace="1">';
	
 //выводим кнопочку диалога проверки обратки.	
 echo '<a href="'.$ret_dialog.'" '.$blank_str.'>'.$ret_img.'</a>';
 
  //генерим урл для проверки через яндекс обратки.
 $ya_dialog='javascript:popUpOpen (\''.Global_WWW_Path.'_admin/special_dialogs/verify_ret_dialog_ya.php?id='.$links_items_db[$i]['id'].'\', 700, 630);';
 echo '<a href="'.$ya_dialog.'" ><img src="icons/ya.gif" border="0" alt="Проверить в Яндексе" hspace="1"></a>';
 

 ?></td>
  <td><? 
 //высчитываем положение этого товраа в массиве $full_goods_db
 $global_pos=($page-1)*$max_on_page + $i;
 if ($full_links_items_db[$global_pos-1]['id'])
 {
 $pic='icons/up.gif';
 echo '<a href="'.$_SERVER['PHP_SELF'].'?actions='.$actions.'&subactions=up&subid='.$links_items_db[$i]['id'].'"><img src="'.$pic.'" border="0" '.get_pic_normal_size ($pic).' alt="Переместить вверх"></a>';
 }
 else echo '&nbsp';
 ?>
 </td>
 <td><? 
 if ($full_links_items_db[$global_pos+1]['id'])
 {
 $pic='icons/down.gif';
 echo '<a href="'.$_SERVER['PHP_SELF'].'?actions='.$actions.'&subactions=down&subid='.$links_items_db[$i]['id'].'"><img src="'.$pic.'" border="0" '.get_pic_normal_size ($pic).' alt="Переместить вниз"></a>';
 }
 else echo '&nbsp';
 ?></td>

 <td><a href="<?=$_SERVER['PHP_SELF'] ?>?actions=edit_links_item&id=<?=$links_items_db[$i]['id'] ?>">Edit</a></td>
</tr>
<?
}
?>
</table>
<?=get_tabs ().$page_bar; ?>

<p align="right">
<a href="<?=$_SERVER['PHP_SELF'] ?>?actions=add_links_item" title="Добавить новую ссылку"><b>Добавить новую ссылку</b></a>
&nbsp;&nbsp;&nbsp;
</p>

</form>

<fieldset class="help_fieldset"><legend>Подсказка:</legend>
<table width="100%" border="0" cellpadding="0" cellspacing="2">
 <tr>
  <td><span class="normal_green"><b>*NEW*</b></span></td>
  <td width="100%">- флаг "новая ссылка". Устаноавливается всем вновь добаленным ссылкам. Снять флаг можно в "Быстром диалоге" или в редактировании.</td>
 </tr> 
 <tr>
  <td><span class="normal_red"><b>Автоперенос</b></span></td>
  <td width="100%">- флаг "автоперенос". Наличие этого флага означает, что ссылка скоро автоматически 
			выключится и/или будет перенесена в "Black List" (если есть раздел, определённый как "Black List" в настройках). 
			Автоматический перенос будет выполнен в том случае, 
			если новая ссылка будет добавлена в тот же раздел, что и с флагом "автоперенос". 
			Т.е. новая ссылка запишется вместо одной из ссылок с таким флагом. 
			Это нужно для того, чтобы ссылки в каталоге не смещались и их адреса не изменялись.</td>
 </tr> 
 <tr>
  <td><span class="normal_red"><b>No Autobot </b></span></td>
  <td width="100%">- флаг "Не проверять автороботом". Ссылки с этим флагом не проверяются автороботом.</td>
 </tr> 
 <tr>
  <td><img src="icons/fast_dialog.gif" border="0" alt="" hspace="1"></td>
  <td width="100%">- перейти в панель "Быстрый диалог"</td>
 </tr> 
 <tr>
  <td><img src="icons/view_ret_page.gif" border="0" alt="" hspace="1"></td>
  <td width="100%">- диалог проверки обратной ссылки (есть адрес обратной ссылки)</td>
 <tr> 
 <tr>
  <td><img src="icons/view_ret_page_off.gif" border="0" alt="" hspace="1"></td>
  <td width="100%">- диалог проверки обратной ссылки (нет адреса обратной ссылки)</td>
 <tr> 
 <tr>
  <td><img src="icons/ya.gif" border="0" alt="" hspace="1"></td>
  <td width="100%">- диалог поиска страницы с нашей ссылкой через Яндекс</td>
 <tr> 
</table>
</fieldset>