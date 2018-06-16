<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//парсер переданных параметров.
//при использоватении mod_rewrite параметры передаются по правилу /actions/id/page  например /cat/3/20
//при отключенном mod_rewrite параметры передаются в виде явных переменных, например ?actions=cat&id=3&page=20
//данный скрипт при включенном mod_rewrite разбираем переданные параметры.

$mod_rewrite_path_db=array();	//в этом массиве все вложенные каталоги при включенном модреврайте.


if (Use_Mod_Rewrite=='On')
{
//включен мод реврайт. раскладываем переменные.
$actions='';
$id='';
$page='';

$mod_rewrite_path=trim ($_REQUEST['mod_rewrite_path']);
if (!eto_ne_pusto($mod_rewrite_path)) $mod_rewrite_path='/';

$mod_rewrite_path_db=explode ('/', $mod_rewrite_path);

$mod_rewrite_path_db=filters_to_data ($mod_rewrite_path_db, '6, 5');	//фильтруем паременные. Вообще, к этим переменным оч. жёсткие правила.

//зановим в переменные значения.
$actions=$mod_rewrite_path_db[0];
$id=$mod_rewrite_path_db[1];
$page=$mod_rewrite_path_db[2];

}

?>