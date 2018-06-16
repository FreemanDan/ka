<?

if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/date_time_functions.php');	//функи для работы с датами.
include_once (Root_Dir.'_shell/functions/main_view_functions.php');	//загружам доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружам доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для отправки сообщений о действиях с каталогом.
include_once (Root_Dir.'_admin/special_dialogs/verify_dialog_ya_functions.php');	//функи для работы вс яндексом
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.

$form_name='vr'.Base_Prefix;

$this_day_start=mktime(0, 0, 0, date ('m', $system_db['THIS_TIME']), date ('d', $system_db['THIS_TIME']), date ('y', $system_db['THIS_TIME']));

$links_items_db=array();	//сюда собираем найденные сылки

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Проверка ссылок</div>
<?

if ($_POST['find'])
{
//селектор
$_SESSION[$form_name]['links_cat']=$_POST['links_cat'];
$_SESSION[$form_name]['enable']=$_POST['enable'];
$_SESSION[$form_name]['retry_href']=$_POST['retry_href'];
$_SESSION[$form_name]['retry']=$_POST['retry'];
$_SESSION[$form_name]['sel_time_start']=get_date_from_array ($_POST['sel_time_start']);
$_SESSION[$form_name]['sel_time_end']=get_date_from_array ($_POST['sel_time_end']);
$_SESSION[$form_name]['is_new']=warn($_REQUEST['is_new']);
$_SESSION[$form_name]['for_del']=warn($_REQUEST['for_del']);

}

if ($page=='clear')
{
//сбрасываем данные отображения
$_SESSION[$form_name]['links_cat']='';
$_SESSION[$form_name]['enable']='1';
$_SESSION[$form_name]['retry_href']='yes_href';
$_SESSION[$form_name]['retry']='no_page';
$_SESSION[$form_name]['sel_time_start']=strtotime("-5 month", $this_day_start);
$_SESSION[$form_name]['sel_time_end']=strtotime("+1 day", $this_day_start);

$_SESSION[$form_name]['is_new']='all';
$_SESSION[$form_name]['for_del']='all';
}

//----
//установки "по умолчанию", если что т ооталось неопределено
if (!eto_ne_pusto ($_SESSION[$form_name]['enable'])) $_SESSION[$form_name]['enable']='1';
if (!eto_ne_pusto ($_SESSION[$form_name]['retry_href'])) $_SESSION[$form_name]['retry_href']='yes_href';
if (!eto_ne_pusto ($_SESSION[$form_name]['retry'])) $_SESSION[$form_name]['retry']='no_page';
if (!eto_ne_pusto ($_SESSION[$form_name]['is_new'])) $_SESSION[$form_name]['is_new']='all';
if (!eto_ne_pusto ($_SESSION[$form_name]['for_del'])) $_SESSION[$form_name]['for_del']='all';
//----

//записываем в переменные параметры формы.
$links_cat=$_SESSION[$form_name]['links_cat'];
$enable=$_SESSION[$form_name]['enable'];
$retry_href=$_SESSION[$form_name]['retry_href'];
$retry=$_SESSION[$form_name]['retry'];
$sel_time_start=$_SESSION[$form_name]['sel_time_start'];
$sel_time_end=$_SESSION[$form_name]['sel_time_end'];
$is_new=$_SESSION[$form_name]['is_new'];
$for_del=$_SESSION[$form_name]['for_del'];

if ($sel_time_start>$sel_time_end)
{
//если период неправильный
$tmp=$sel_time_end;
$sel_time_end=$sel_time_start;
$sel_time_start=$tmp;
}


//========================================================================
if ($_POST['find'])
{
//ищем ссылки согласно селектору.
//для начала делаем выборки.
$adv_query="";
if ('a'.$enable!='a'.'all') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_ENABLE='".intval($enable)."' ";
if ($retry_href=='yes_href') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_RET_LINK_ADDR<>'' ";
if ($retry_href=='no_href') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_RET_LINK_ADDR='' ";
if ($is_new!='all') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_IS_NEW='$is_new' ";
if ($for_del!='all') $adv_query.=" AND ".Base_Prefix."links_items.FIELD_FOR_DEL='$for_del' ";

//генерим in строку категорий.
$in_str=si_get_in_string ($links_cat);
if (!$in_str) $in_str='0';
$adv_query.=" AND ".Base_Prefix."links_items.FIELD_LINK_CAT IN ($in_str) ";

//время
$adv_query.=" AND ".Base_Prefix."links_items.FIELD_CREATE_DATE>='$sel_time_start' AND ".Base_Prefix."links_items.FIELD_CREATE_DATE<'$sel_time_end' ";

//загребаем ссылки соглавно выборке.
$tmp_items_db=SI_sql_query("select 
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
	WHERE 	".Base_Prefix."links_items.id>0
	".$adv_query." 
	GROUP BY ".Base_Prefix."links_items.id
	ORDER BY ".Base_Prefix."links_items.FIELD_SORT ASC
	");


switch ($retry)
{

 case 'page':
  //выбираем только те страницы, у которых нашли обратку по указанному адресу.
  for ($i=0; $i<count($tmp_items_db); $i++)
  if (link_verify_any_ret_link ($_SERVER['HTTP_HOST'], $tmp_items_db[$i]['FIELD_RET_LINK_ADDR']))
  {
   //обратка найдена
   $links_items_db[]=$tmp_items_db[$i];
   $links_items_db[count($links_items_db)-1]['FIND_RETRY_STR']='<span class="normal_green"><b>Yes</b></span>';
  }
 break;
 
 case 'ya':
  //выбираем только те страницы, у которых нашли обратку через яндекс
  for ($i=0; $i<count($tmp_items_db); $i++)
  if (links_get_ya_ret ($_SERVER['HTTP_HOST'], $tmp_items_db[$i]['FIELD_DOMAIN']))
  {
   //хоть одна обратка на яндексе найдена
   $links_items_db[]=$tmp_items_db[$i];
   $links_items_db[count($links_items_db)-1]['FIND_RETRY_STR']='<span class="normal_green"><b>Yes</b></span>';
  }
 break;

 case 'no_page':
  //выбираем только те страницы, у которых НЕ нашли обратку по указанному адресу.
  for ($i=0; $i<count($tmp_items_db); $i++)
  if (!link_verify_any_ret_link ($_SERVER['HTTP_HOST'], $tmp_items_db[$i]['FIELD_RET_LINK_ADDR']))
  {
   //обратка найдена
   $links_items_db[]=$tmp_items_db[$i];
   $links_items_db[count($links_items_db)-1]['FIND_RETRY_STR']='<span class="normal_red"><b>No</b></span>';
  }
 break;
 case 'no_ya':
  //выбираем только те страницы, у которых НЕ нашли обратку через яндекс
  for ($i=0; $i<count($tmp_items_db); $i++)
  if (!links_get_ya_ret ($_SERVER['HTTP_HOST'], $tmp_items_db[$i]['FIELD_DOMAIN']))
  {
   //хоть одна обратка на яндексе найдена
   $links_items_db[]=$tmp_items_db[$i];
   $links_items_db[count($links_items_db)-1]['FIND_RETRY_STR']='<span class="normal_red"><b>No</b></span>';
  }
 break;

 default:
  $links_items_db=$tmp_items_db;	//если невыставленна проверка обраток, то передаём временный массив в основной без проблем.
 break;
}

}
//========================================================================

if ($_POST['ok'])
{
//применяем дейсвия.

$message='Действия приняты!<br>';
$message.='Действия применены к сайтам:<br>';

for ($i=0; $i<count($_POST['links']); $i++)
{
$item_db=links_get_item_full_data ($_POST['links'][$i]);	//получаем инфу о ссылке.

$message.=lecho ($item_db['FIELD_DOMAIN']).'<br>';

//применяем к ссылке выбранные действия.
for ($z=0; $z<count($_POST['ACT']); $z++) link_exec_act ($_POST['ACT'][$z], $item_db['id'], $links_settings_db, $_POST['send_fast_mail']).'<br>';

}

//генерим окно с сообщением
$choise_db=array(); $num=0;

$choise_db[$num]['name']='В главное меню "Каталог ссылок"';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'];

$choise_db[$num]['name']='Вернуться';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions='.$actions;

echo si_message_box ('Ок',$message, $choise_db, 0, '350px');
my_exit();
}
//========================================================================

//print_ar ($links_settings_db);

//получаем название категории, определённой как "black list"
$black_list_name='';
if ($links_settings_db['BLACK_LIST_ID'])
{
$tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE id='".intval($links_settings_db['BLACK_LIST_ID'])."'");
$black_list_name=$tmp_db[0]['FIELD_NAME'];
}

?>
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">

<script language="javascript"><!--
function sel_all(formname, elemid)
{
//функция инвертирует чекбоксы id=elemid в форме formname.
 if(!document.forms[formname].elements[elemid]) return;
 if(!document.forms[formname].elements[elemid].length)
	document.forms[formname].elements[elemid].checked = document.forms[formname].elements[elemid].checked ? false : true;
 else
	for(var i=0;i<document.forms[formname].elements[elemid].length;i++)
		document.forms[formname].elements[elemid][i].checked = document.forms[formname].elements[elemid][i].checked ? false : true;
}
//-->
</script>

<fieldset class="normal_fieldset"><legend>Настройки выборки</legend>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
  <tr class="middletitle">
   <td>
    <b>Категории:</b> <a href="javascript:sel_all('<?=$form_name ?>', 'checks')" title="Выделить всё/снять выделение">*</a><br>
    <?
//получаем все категории.
$links_cat_db=SI_sql_query("select 
	".Base_Prefix."links_category.id, 
	".Base_Prefix."links_category.FIELD_NAME, 
	".Base_Prefix."links_category.FIELD_ENABLE, 
	count(".Base_Prefix."links_items.id) AS LCNT
	from ".Base_Prefix."links_category 
	LEFT JOIN ".Base_Prefix."links_items ON ".Base_Prefix."links_items.FIELD_LINK_CAT=".Base_Prefix."links_category.id
	GROUP BY ".Base_Prefix."links_category.id
	ORDER BY ".Base_Prefix."links_category.FIELD_SORT ASC
	");
$links_cat_db=link_resort_db_in_column ($links_cat_db, 3);	//раскладываем в вид удобный для формирования колонок
for ($i=0; $i<count($links_cat_db); $i++)
  {  
  for ($z=0; $z<count($links_cat_db[$i]); $z++) 
  {
   $sel='';
   if (!$_POST && $links_cat_db[$i][$z]['id']!=$links_settings_db['BLACK_LIST_ID']) $sel=' checked ';	//если ничего не определено, то все флажки выставлены (первый заход)
   if ($_POST) if (my_in_array ($links_cat, $links_cat_db[$i][$z]['id']))  $sel=' checked ';
   $adv_class='';
   if ($links_cat_db[$i][$z]['id']==$links_settings_db['BLACK_LIST_ID']) $adv_class='class="normal_red"';	//чёрный лист подсвечен красным
   echo '<span style="width:33%;" '.$adv_class.' nowrap><input '.$sel.' id="checks" type="checkbox" name="links_cat[]" value="'.$links_cat_db[$i][$z]['id'].'"> - '.$links_cat_db[$i][$z]['FIELD_NAME'].' ['.intval($links_cat_db[$i][$z]['LCNT']).']</span>';
  }
  echo '<br>';
}
	
    ?>
   </td>
  </tr>
  
 <tr class="middletitle">
   <td align="right">
    <?
  $enable_db=array();
  $enable_db['all']='Все';
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
    ?>
   </td>
  </tr>
  
  
 <tr class="middletitle">
   <td align="right">
    <?

    $retry_href_db=array();
    $retry_href_db['all']='Не учитывать';
    $retry_href_db['yes_href']='Указан';
    $retry_href_db['no_href']='Не указан';
    echo 'Адрес обратной ссылки: '.l_select ('name="retry_href"', $retry_href_db, $retry_href);
    echo '&nbsp;&nbsp;&nbsp;';

    $retry_db=array();
    $retry_db['all']='Не учитывать';
    $retry_db['page']='Есть на указанной странице';
    $retry_db['ya']='Есть результаты в Yandex';
    $retry_db['no_page']='Нет на указанной странице';
    $retry_db['no_ya']='Нет результатов в Yandex';
    echo 'Поиск обратной ссылки: '.l_select ('name="retry"', $retry_db, $retry);
    echo '&nbsp;&nbsp;&nbsp;';

    ?>
   </td>
  </tr>

 <tr class="middletitle">
   <td align="right">
   Добавлены с: <?=date_selected_box ('sel_time_start', $sel_time_start, '{d} - {m} - {y}') ?>
   &nbsp;&nbsp;&nbsp;
   до: <?=date_selected_box ('sel_time_end', $sel_time_end, '{d} - {m} - {y}') ?>&nbsp;&nbsp;&nbsp;
   </td>
  </tr>

 <tr class="rowlight">
   <td align="center" height="30"><?=l_buttion ('name=find', 'Найти ссылки') ?></td>
  </tr>

</table>
</fieldset>

<?
//выводим результаты поиска.

//если результатов нет, то выводим красное поле.
if (!$links_items_db && $_POST['find'])
{
?>
<fieldset class="alert_fieldset"><legend>Ссылки не найдены:</legend>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
  <tr class="rowlight">
   <td><br><div align="center"><b>Ссылки по условию не найдены<b></div><br></td>
  </tr>
</table>
</fieldset>
<?
}

else
if ($links_items_db)
{
//выводим найденные ссылки
//----------
?>
<fieldset class="normal_fieldset"><legend>Найденные ссылки:</legend>
<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr align="center" class="middletitle">
 <td width="5%"><b>№</td>
 <td width="5%"><a href="javascript:sel_all('<?=$form_name ?>', 'links')" title="Выделить всё/снять выделение">*</a></td>
 <td><b>Сайт</td> 
 <td><b>Категория</td> 
 <td><b>Обр.ссылка</td> 
 <td><b>Дата</td> 
 <td><b>Штраф</td> 
 <td width="4%"><b>Pub.</b></td> 
 <td width="4%">&nbsp;</td> 
 <td width="4%"><b>Ред.</td>
</tr>

<?
for ($i=0; $i<count($links_items_db); $i++)
{
$tab_bg='rowlight';
if (($i/2)<>floor($i/2)) $tab_bg='rowdark';

if ($links_items_db[$i]['FIELD_FOR_DEL']) $tab_bg='rowred';

?>
<tr align="center" class="<?=$tab_bg ?>" onclick="this.className='rowyellow';">
 <td><?=($i+1) ?></td>
 <td><input id="links" type="checkbox" name="links[]" value="<?=$links_items_db[$i]['id'] ?>"></td>
 <td align="left">
 <div><a href="http://<?=lecho ($links_items_db[$i]['FIELD_DOMAIN']) ?>" target="_blank"><?=lecho ($links_items_db[$i]['FIELD_DOMAIN']) ?></a>
 <? 
  if ($links_items_db[$i]['FIELD_IS_NEW']) echo ' <span class="normal_green"><b>*NEW*</b> </span> '; 
  if ($links_items_db[$i]['FIELD_FOR_DEL']) echo ' <span class="normal_red"><b>Автоперенос</b> </span> ';
  if ($links_items_db[$i]['FIELD_DISABLE_AUTOBOT']) echo ' <span class="normal_red"><b>No Autobot</b> </span> ';
  
 ?>

 </div>
 <div class="help"><?=lecho ($links_items_db[$i]['FIELD_NAME']) ?></div>
 </td>
 <td><?
 //навзание категори..если это блэк лист. то выделаяем красным. FIELD_LINK_CAT
 if ($links_items_db[$i]['FIELD_LINK_CAT']==$links_settings_db['BLACK_LIST_ID']) echo '<span class="normal_red">'.lecho ($links_items_db[$i]['CNAME']).'</span>';
		else echo lecho ($links_items_db[$i]['CNAME']);
 ?></td>
 <td>
 <?
  if ($retry=='all') echo '---';
  else echo $links_items_db[$i]['FIND_RETRY_STR'];
 ?>
 </td>
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
 <td><a href="<?=$_SERVER['PHP_SELF'] ?>?actions=edit_links_item&id=<?=$links_items_db[$i]['id'] ?>" target="_blank">Edit</a></td>
 </tr>
<?
}
?>
</table>
</fieldset>
<?
//----------
}

//выводим диалог с действиями.
if ($links_items_db)
{

?>
<fieldset class="normal_fieldset"><legend>Действия к выбранным ссылкам:</legend>
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
<div style="padding:5px;" class="alert_fieldset"><input type="checkbox" name="send_fast_mail" value="1" <?=$links_settings_db['DEF_FAST_MAIL'] ?>> - Оправить e-mail уведомление пользователям о выполненный действиях</div>
</td>
  </tr>
 <tr class="rowlight">
   <td align="center" height="30"><?=l_buttion ('name=ok', 'Применить действия') ?></td>
  </tr>
</table>
</fieldset>
<?
}
?>

</form>