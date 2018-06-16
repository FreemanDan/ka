<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.

include_once (Root_Dir.'_shell/functions/page_bar_funtions.php');			//загрузка функций для управления страницами.

$max_on_page=5; 		//максимум записей на странице из настроек страницы.
$form_name='auto_bot_log'.Base_Prefix;

if ($_REQUEST['ok'])
{
//селектор
$_SESSION[$form_name]['search']=$_REQUEST['search'];
$_SESSION[$form_name]['noempty']=warn($_REQUEST['noempty']);
$page=1;
}

if ($_POST['clear'] || $page=='clear')
{
//сбрасываем данные отображения
$_SESSION[$form_name]['search']='';
$_SESSION[$form_name]['noempty']='all';
$page=1;
}

$page=init_page ($actions, $page); //достём страниицу из сессии или очищаем страницу

//установки "по умолчанию"
if (!eto_ne_pusto ($_SESSION[$form_name]['noempty'])) $_SESSION[$form_name]['noempty']='all';

$search=$_SESSION[$form_name]['search'];
$noempty=$_SESSION[$form_name]['noempty'];


$adv_query="";	//доп. условие в запрос.

if ($search)
{
//задана строка поиска... поехали составлять доп. условие в запрос.
$adv_query.=" AND FIELD_VERIFY_TEXT LIKE '%".get_string_for_like ($search)."%'  "; 
}

if ($noempty!='all') 
{
 if ($noempty==1) $adv_query.=" AND FIELD_VERIFY_CNT>'0' ";	//только пустые
 if ($noempty==2) $adv_query.=" AND FIELD_VERIFY_CNT='0' ";	//только результативные.
}


//получаем количество записей
$total_count=SI_sql_query("select count(id) AS TCNT from ".Base_Prefix."links_auto_bot_log WHERE id>'0' ".$adv_query);
$total_count=$total_count[0]['TCNT'];


//получаем записи в зависимости от страницы
$limit_str=pages_get_lim_str ($max_on_page, $page); //получаем строку для лимита
$links_auto_bot_log_db=SI_sql_query("select 
	id, 
	FIELD_DATE, 
	FIELD_VERIFY_CNT, 
	FIELD_VERIFY_TEXT 
	from ".Base_Prefix."links_auto_bot_log 
	WHERE 	id>'0' 
	".$adv_query." 
	ORDER BY FIELD_DATE DESC
	".$limit_str);

//получаем код планки со страницами с настройками для админки.
$page_bar=get_pages_bar ($total_count, $max_on_page, $page, $_SERVER['PHP_SELF'].'?actions='.$actions.'&page={page_num}', get_admin_page_bar_settings ());

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Лог авторобота</div>

<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">


<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
 <tr class="middletitle">
  <td align="right"><?
  //получаем все каталоги.
  
  $noempty_db=array();
  $noempty_db['all']='Не учитывать';
  $noempty_db['1']='Только с результатами проверки';
  $noempty_db['2']='Только без результатов проверки';
  echo 'Результат: '.l_select ('name="noempty"', $noempty_db, $noempty);
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
 <td><b>Дата</td> 
 <td><b>Пров.</td> 
 <td><b>Ход проверки</td> 
</tr>
<?

for ($i=0;$i<count ($links_auto_bot_log_db); $i++)
{
$tab_bg='rowlight';
if (($i/2)<>floor($i/2)) $tab_bg='rowdark';

?>
<tr align="center" class="<?=$tab_bg ?>" onclick="this.className='rowyellow';">
 <td><?=get_numeric ($max_on_page, $page, $i) ?></td>
 <td><?=date ('d.m.Y H:i', $links_auto_bot_log_db[$i]['FIELD_DATE']) ?></td>
 <td><?=$links_auto_bot_log_db[$i]['FIELD_VERIFY_CNT'] ?></td>
 <td align="left"><?=$links_auto_bot_log_db[$i]['FIELD_VERIFY_TEXT'] ?></td>
</tr>
<?
}
?>
</table>
<?=get_tabs ().$page_bar; ?>

</form>
