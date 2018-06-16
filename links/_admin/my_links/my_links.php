<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


//получаем всю базу для перемещений и отслеживания возможности перемещения
if ($subactions) $links_variants_db=SI_sql_query("select id, FIELD_SORT from ".Base_Prefix."links_variants ORDER BY FIELD_SORT ASC");

if (!$err)
switch ($subactions) 
{
   case 'up': 
	//поднимаем элемент $subid вверх
	$this_num='';
	for ($i=0; $i<count ($links_variants_db); $i++) if ($links_variants_db[$i]['id']==$subid) $this_num=$i;
	if ($this_num)
	if ($links_variants_db[$this_num-1]['id'])
	{
	SI_sql_query("UPDATE ".Base_Prefix."links_variants SET FIELD_SORT=".$links_variants_db[$this_num-1]['FIELD_SORT']." WHERE id='$subid'");
	SI_sql_query("UPDATE ".Base_Prefix."links_variants SET FIELD_SORT=".$links_variants_db[$this_num]['FIELD_SORT']." WHERE id=".$links_variants_db[$this_num-1]['id']);
	}
   break;
   case 'down': 
	//опускаем элемент $subid вниз
	$this_num='';
	for ($i=0; $i<count ($links_variants_db); $i++) if ($links_variants_db[$i]['id']==$subid) $this_num=$i;
	if ($links_variants_db[$this_num+1]['id'])
	{
	MYSQL_QUERY("UPDATE ".Base_Prefix."links_variants SET FIELD_SORT=".$links_variants_db[$this_num+1]['FIELD_SORT']." WHERE id='$subid'");
	MYSQL_QUERY("UPDATE ".Base_Prefix."links_variants SET FIELD_SORT=".$links_variants_db[$this_num]['FIELD_SORT']." WHERE id=".$links_variants_db[$this_num+1]['id']);
	}   
   break;
} 

$links_variants_db=SI_sql_query("select * from ".Base_Prefix."links_variants ORDER BY FIELD_SORT ASC");

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Список ссылок для обмена </div>

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">Список ссылок для обмена</td>
</tr>
<tr align="center" class="middletitle">
 <td width="5%"><b>№</td>
 <td width="15%"><b>Кор. описание</td>
 <td><b>HTML</b></td> 
 <td width="5%"><b>Акт.</b></td> 
 <td width="5%">&nbsp;</td>
 <td width="5%">&nbsp;</td>
 <td width="5%"><b>Ред.</td>
</tr>
<?
for ($i=0;$i<count ($links_variants_db); $i++)
{
$tab_bg='rowlight';
if (($i/2)<>floor($i/2)) $tab_bg='rowdark';

?>
<tr align="center" class="<?=$tab_bg ?>" onclick="this.className='rowyellow';">
 <td><?=($i+1) ?></td>
  <td align="left"><?=$links_variants_db[$i]['FIELD_SHORT_NAME'] ?></td>
 <td align="left"><?=$links_variants_db[$i]['FIELD_CODE'] ?></td>
 <td><?=si_admin_get_enable ($links_variants_db[$i]['FIELD_ENABLE'], 0) ?></td>
 <td><? 
 if ($i>0)
 {
 $pic='icons/up.gif';
 echo '<a href="'.$PHP_SELF.'?actions='.$actions.'&subactions=up&subid='.$links_variants_db[$i]['id'].'"><img src="'.$pic.'" border="0" '.get_pic_normal_size ($pic).' alt="Переместить вверх"></a>';
 }
 else echo '&nbsp';
 ?></td>
 <td><? 
 if ($links_variants_db[$i+1]['id'])
 {
 $pic='icons/down.gif';
 echo '<a href="'.$PHP_SELF.'?actions='.$actions.'&subactions=down&subid='.$links_variants_db[$i]['id'].'"><img src="'.$pic.'" border="0" '.get_pic_normal_size ($pic).' alt="Переместить вниз"></a>';
 }
 else echo '&nbsp';
 ?></td>

 <td><a href="<?=$_SERVER['PHP_SELF'] ?>?actions=edit_my_links&id=<?=$links_variants_db[$i]['id'] ?>">Edit</a></td>
</tr>
<?
}
?>
</table>
<p align="right">
<a href="<?=$_SERVER['PHP_SELF'] ?>?actions=add_my_links" title="Добавить новую ссылку для обмена"><b>Добавить новую ссылку для обмена</b></a>
&nbsp;&nbsp;&nbsp;
</p>

