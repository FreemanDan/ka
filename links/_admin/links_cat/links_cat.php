<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.



//получаем всю базу для перемещений и отслеживания возможности перемещения
if ($subactions) $links_category_db=SI_sql_query("select id, FIELD_SORT from ".Base_Prefix."links_category ORDER BY FIELD_SORT ASC");

if (!$err)
switch ($subactions) 
{
   case 'up': 
	//поднимаем элемент $subid вверх
	$this_num='';
	for ($i=0; $i<count ($links_category_db); $i++) if ($links_category_db[$i]['id']==$subid) $this_num=$i;
	if ($this_num)
	if ($links_category_db[$this_num-1]['id'])
	{
	SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_SORT=".$links_category_db[$this_num-1]['FIELD_SORT']." WHERE id='$subid'");
	SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_SORT=".$links_category_db[$this_num]['FIELD_SORT']." WHERE id=".$links_category_db[$this_num-1]['id']);
	}
   break;
   case 'down': 
	//опускаем элемент $subid вниз
	$this_num='';
	for ($i=0; $i<count ($links_category_db); $i++) if ($links_category_db[$i]['id']==$subid) $this_num=$i;
	if ($links_category_db[$this_num+1]['id'])
	{
	SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_SORT=".$links_category_db[$this_num+1]['FIELD_SORT']." WHERE id='$subid'");
	SI_sql_query("UPDATE ".Base_Prefix."links_category SET FIELD_SORT=".$links_category_db[$this_num]['FIELD_SORT']." WHERE id=".$links_category_db[$this_num+1]['id']);
	}   
   break;
} 

$links_category_db=SI_sql_query("select 
	".Base_Prefix."links_category.id,
	".Base_Prefix."links_category.FIELD_NAME,
	".Base_Prefix."links_category.FIELD_ENABLE,
	count(".Base_Prefix."links_items.id) AS LCNT
	from ".Base_Prefix."links_category 
	LEFT JOIN ".Base_Prefix."links_items ON ".Base_Prefix."links_items.FIELD_LINK_CAT=".Base_Prefix."links_category.id
	GROUP BY ".Base_Prefix."links_category.id
	ORDER BY ".Base_Prefix."links_category.FIELD_SORT ASC 
	");


?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Список категорий </div>

<table width="100%" cellspacing="1" cellpadding="2" class="maintable">
<tr height="30">
 <td colspan="100" align="left" class="maintitle">Список категорий</td>
</tr>
<tr align="center" class="middletitle">
 <td width="5%"><b>№</td>
 <td><b>Название</td> 
 <td><b>Ссылок</td> 
 <td><b>Актив.</td> 
 <td width="5%">&nbsp;</td>
 <td width="5%">&nbsp;</td>
 <td width="10%"><b>Ред.</td>
</tr>
<?
for ($i=0;$i<count ($links_category_db); $i++)
{
$tab_bg='rowlight';
if (($i/2)<>floor($i/2)) $tab_bg='rowdark';

?>
<tr align="center" class="<?=$tab_bg ?>" onclick="this.className='rowyellow';">
 <td><?=($i+1) ?></td>
 <td align="left"><?=lecho ($links_category_db[$i]['FIELD_NAME']) ?></td>
 <td><?=intval ($links_category_db[$i]['LCNT']) ?></td>
 <td><?=si_admin_get_enable ($links_category_db[$i]['FIELD_ENABLE'], 0) ?></td>
 <td><? 
 if ($i>0)
 {
 $pic='icons/up.gif';
 echo '<a href="'.$PHP_SELF.'?actions='.$actions.'&subactions=up&subid='.$links_category_db[$i]['id'].'"><img src="'.$pic.'" border="0" '.get_pic_normal_size ($pic).' alt="Переместить вверх"></a>';
 }
 else echo '&nbsp';
 ?></td>
 <td><? 
 if ($links_category_db[$i+1]['id'])
 {
 $pic='icons/down.gif';
 echo '<a href="'.$PHP_SELF.'?actions='.$actions.'&subactions=down&subid='.$links_category_db[$i]['id'].'"><img src="'.$pic.'" border="0" '.get_pic_normal_size ($pic).' alt="Переместить вниз"></a>';
 }
 else echo '&nbsp';
 ?></td>

 <td><a href="<?=$_SERVER['PHP_SELF'] ?>?actions=edit_links_cat&id=<?=$links_category_db[$i]['id'] ?>">Edit</a></td>
</tr>
<?
}
?>
</table>
<p align="right">
<a href="<?=$_SERVER['PHP_SELF'] ?>?actions=add_links_cat" title="Добавить новую категорию"><b>Добавить новую категорию</b></a>
&nbsp;&nbsp;&nbsp;
</p>

