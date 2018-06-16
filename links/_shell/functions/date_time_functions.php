<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//###############################################################################
//#		фукнции для работы с временем и датами.				#
//###############################################################################


//список месяцем по рюсски. 
$mounth_rus_db=array();
$mounth_rus_db[1]="Января";
$mounth_rus_db[2]="Февраля";
$mounth_rus_db[3]="Марта";
$mounth_rus_db[4]="Апреля";
$mounth_rus_db[5]="Мая";
$mounth_rus_db[6]="Июня";
$mounth_rus_db[7]="Июля";
$mounth_rus_db[8]="Августа";
$mounth_rus_db[9]="Сентября";
$mounth_rus_db[10]="Октября";
$mounth_rus_db[11]="Ноября";
$mounth_rus_db[12]="Декабря";

//список месяцем по рюсски в именительном падеже. 
$mounth_rus_2_db=array();
$mounth_rus_2_db[1]="Январь";
$mounth_rus_2_db[2]="Февраль";
$mounth_rus_2_db[3]="Март";
$mounth_rus_2_db[4]="Апрель";
$mounth_rus_2_db[5]="Май";
$mounth_rus_2_db[6]="Июнь";
$mounth_rus_2_db[7]="Июль";
$mounth_rus_2_db[8]="Август";
$mounth_rus_2_db[9]="Сентябрь";
$mounth_rus_2_db[10]="Октябрь";
$mounth_rus_2_db[11]="Ноябрь";
$mounth_rus_2_db[12]="Декабрь";

//==============================================

function get_date_from_array ($date_array)
{
//функция по массиву возварщает метку времени для заданной в массиве даты
//формат массива  
//$date_array['y'] - год в формате Y
//$date_array['m'] - месяц в формате m
//$date_array['d'] - день в формате d
//$date_array['h'] - час в формате H
//$date_array['i'] - минута в формате i
//$date_array['s'] - секунда в формате s

return mktime(intval ($date_array['h']), intval ($date_array['i']), intval ($date_array['s']), intval ($date_array['m']), intval ($date_array['d']), intval ($date_array['y']));
}
//==============================================

function get_normal_date_number ($num)
{
//функция возвращает номер в виде 01 .. 02 .. 10 ..
if ($num<10) 	return '0'.intval ($num);
	else 	return $num;
}
//==============================================

function get_normal_date_db ($start, $end)
{
//функция возвращает массив c $start по $end в нормальном виде.. т.е. 01 02 15 и тд.
$ex=array();
for ($i=$start; $i<=$end; $i++) $ex[$i]=get_normal_date_number ($i);
return $ex;
}
//==============================================

function date_selected_box ($box_name, $active_date, $block_template)
{
//функция выводит активный блок в массиве $box_name для выбора даты и выбирает значения по времени $active_date
//$block_template - шаблон в который всё это грузится.
//$block_template - парематры {y} {m} {d} {h} {i} {s}
global $mounth_rus_db;

$data_db=array();
$data_db['y']=l_select ('name='.$box_name.'[y]', get_normal_date_db (date ('Y')-5, date ('Y')+5), date ('Y',$active_date));
$data_db['m']=l_select ('name='.$box_name.'[m]', $mounth_rus_db, intval (date ('m',$active_date)));
$data_db['d']=l_select ('name='.$box_name.'[d]', get_normal_date_db (1, 31),  intval (date ('d',$active_date)));

$data_db['h']=l_select ('name='.$box_name.'[h]', get_normal_date_db (0, 23), date ('H',$active_date));
$data_db['i']=l_select ('name='.$box_name.'[i]', get_normal_date_db (0, 59), date ('i',$active_date));
$data_db['s']=l_select ('name='.$box_name.'[s]', get_normal_date_db (0, 59), date ('s',$active_date));

return si_field_replace ($data_db, $block_template);
}
//==============================================

function my_rus_date ($templ, $source_date)
{
//функция возвращает дату в спец. виде типа 01 Январа 2004  15:45:44
//$templ - шаблон типа {d} {m} {y} {h}:{i}:{s}
//$source_date - метка времени на котрую вычисляем дату.
global $mounth_rus_db;

$tmp_db=array();
$tmp_db['d']=get_normal_date_number (date ('d', $source_date));
$tmp_db['m']=$mounth_rus_db[intval (date ('m', $source_date))];
$tmp_db['y']=date ('Y', $source_date);

$tmp_db['h']=get_normal_date_number (date ('H', $source_date));
$tmp_db['i']=get_normal_date_number (date ('i', $source_date));
$tmp_db['s']=get_normal_date_number (date ('s', $source_date));

return si_field_replace ($tmp_db, $templ);
}
//==============================================


?>