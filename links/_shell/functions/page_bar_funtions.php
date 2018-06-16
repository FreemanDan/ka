<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//набор функций для работой со страницыами и "плашкой" доступных страниц.

function pages_get_lim_str ($max, $page)
{
//функция возвращает ограничение в запрос: LIMIT 0,49
$page=intval ($page);
$max=intval ($max);

if ($page<0) $page=0;
if ($page>0) $page=$page-1;
$start=$page*$max; //стартовая запись
$ex=' LIMIT '.$start.','.$max.' ';
return $ex;
}
//===============================================


function calculate_this_page ($position, $max_on_page)
{
//функция рассчитывает к каой странице принадлежит запись с порядковым номером $position, если на одной странице $max_on_page записей
//подразумевается, что позиция отсчитывается с 1, а не с нуля.
$page=ceil ($position/$max_on_page);
return $page;
}
//===============================================


function get_pages_bar ($total, $max, $page, $href_tpl)
{
//процедура возвращает список доступных страниц
//если плагка не нужна, то возвращает пустую строку.
//$total - всего элементов
//$max - элементов на одной странице
//$page - текущая страница
//$href_tpl - шаблон ссылки
//пример вызова
//$page_bar=get_pages_bar ($total_count, $max_on_page, $page, '/123/{page_num}/');
//вместо {page_num} будет подствлен номер нужной страницы.
//5-ым параметром может передаваться массив $view_bar_params_db в котором вся информация о отображении.
//если он не передан, то используем настройки "по умолчанию"
//==============

if (func_num_args()==5) $view_bar_params_db=func_get_arg (4);	//если есть 5-ый параметр, то грузим настройки из него.
		else	$view_bar_params_db=get_default_page_bar_settings ();	//получаем настройки "по умолчанию"


$page_count=ceil ($total/$max);	//всего страниц
//страницы не нужны.
if ($page_count<=1 && $view_bar_params_db['return_empty']) return;

$page=intval ($page);
if ($page<=0) $page=1;

//страницы для кнопочек-картиночек.. тля.
$first_page=1;		//первая страница
$preview_page=$page-1; 
if ($preview_page<1) $preview_page=$first_page;	//предыдущая страница от выбранной

$next_page=$page+1; 
if (($next_page-1)*$max>=$total || $total<=$max) $next_page=$page_count;	//следующая страница от выбранной

$last_page=$page_count;	//последняя страница

//вычисляем начальные страницы предыдущей и следующей группы страниц
$start_this_group=floor (($page-1)/$view_bar_params_db['numbers_in_bar'])*$view_bar_params_db['numbers_in_bar']+1;

$preview_group=$start_this_group-$view_bar_params_db['numbers_in_bar']; 
if ($preview_group<=0) $preview_group=1;	//первая стрица в предыдущей группе страниц.

$next_group=$start_this_group+$view_bar_params_db['numbers_in_bar']; 
if (($next_group-1)*$max>=$total || $total<=$max) $next_group=$page_count;

//заполняем значениями массив.
$page_bar_db=array();
$page_bar_db['first_page']=	'<a href="'.si_field_replace(array('page_num' => 1), 		$href_tpl).'">'.$view_bar_params_db['buttons']['first_page'].'</a>';
$page_bar_db['preview_group']=	'<a href="'.si_field_replace(array('page_num' => $preview_group),$href_tpl).'">'.$view_bar_params_db['buttons']['preview_group'].'</a>';
$page_bar_db['preview_page']=	'<a href="'.si_field_replace(array('page_num' => $preview_page),$href_tpl).'">'.$view_bar_params_db['buttons']['preview_page'].'</a>';
$page_bar_db['next_page']=	'<a href="'.si_field_replace(array('page_num' => $next_page), 	$href_tpl).'">'.$view_bar_params_db['buttons']['next_page'].'</a>';
$page_bar_db['next_group']=	'<a href="'.si_field_replace(array('page_num' => $next_group), 	$href_tpl).'">'.$view_bar_params_db['buttons']['next_group'].'</a>';
$page_bar_db['last_page']=	'<a href="'.si_field_replace(array('page_num' => $last_page), 	$href_tpl).'">'.$view_bar_params_db['buttons']['last_page'].'</a>';
$page_bar_db['page_bar']=	'';	//сюда соберётся список страниц.

//-----
//расчитываем начало и конец диапазона для показа номеров страниц.
//$start_num - начало отсчёта (включительно)
//$end_num - конец отсчёта (включительно)
$view_bar_params_db['numbers_in_bar']--;
$start_num=$start_this_group;
if ($start_num<=0) $start_num=1;

$end_num=$start_num+$view_bar_params_db['numbers_in_bar'];
if ($end_num>$page_count) $end_num=$page_count;

//-----

//перебираем номера в диапазоне.
for($i=$start_num; $i<=$end_num; $i++)
{
//собираем значени для номера.
$number_data_db=array();
$number_data_db['num']=$i;			//номер страницы.
$number_data_db['start']=($i-1)*$max+1;		//номер первой записи на этой странице
$number_data_db['end']=$number_data_db['start']+$max-1;	//номер последней записи на этой странице
if ($number_data_db['end']>$total)  $number_data_db['end']=$total; //если страница последняя, то выводим номер последней записи

$tmp_db=array();
$tmp_db['link_to_page']=si_field_replace (array('page_num' => $i), $href_tpl);	//записываем номер страницы в адрес через шаблон $href_tpl
$tmp_db['number']=	si_field_replace ($number_data_db, $view_bar_params_db['templates']['number_page']);	//собираем номер страницы

//добавляем номер к полю через один из шаблонов (выбрана станиуа или нет)
if ($i==$page) 		$page_bar_db['page_bar'].=si_field_replace ($tmp_db, $view_bar_params_db['templates']['select_page']);		//в выбранный шаблон
		else 	$page_bar_db['page_bar'].=si_field_replace ($tmp_db, $view_bar_params_db['templates']['no_select_page']);	//в НЕ выбранный шаблон
}

//выставляем в дополнительную информацию
//её можно использовать в шаблонах.
$page_bar_db['total_pages']=$page_count;	//всего страниц
$page_bar_db['total_records']=$total;		//всего записей
$page_bar_db['total_pages_group']=ceil ($total/$max);		//всего групп страниц
$page_bar_db['current_pages_group']=ceil ($start_this_group/$max);		//текущая группа страниц.
$page_bar_db['max_on_page']=$max;		//максимум записей на странице

//если страниц нет, то выводим сообщение.
//вообще если страниц нет, то функа вылетает если $view_bar_params_db['return_empty']=true
if (!$page_bar_db['page_bar']) $page_bar_db['page_bar']=$view_bar_params_db['empty_mesage'];

return si_field_replace ($page_bar_db, $view_bar_params_db['templates']['bar']);	//возвращаем результат.
}
//===============================================


function get_numeric ($max_on_page, $page, $num)
{
//функция вычисляет порядковый номер записи $num на странице $page с учётом кличествао на странице $max_on_page
//т.е. если $num=5, $page=3, $max_on_page=10, то функция вовзратит 25 (пятая запись на 3-ей странице)
$page=intval ($page);
if ($page<0) $page=0;
if ($page>0) $page=$page-1;
$num=$max_on_page*$page+$num+1;
return $num;
}
//========================================

function get_default_page_bar_settings ()
{
//функция возвращает настройки для page bar "по умолчанию"
$view_bar_params_db=array();
//количество номеров страниц в группе
//т.е. если выбрана страница 1 то: [>1] [2] [3] [4] [5]
//т.е. если выбрана страница 4 то: [1] [2] [3] [>4] [5]
//т.е. если выбрана страница 7 то: [6] [>7] [8] [9] [10]
$view_bar_params_db['numbers_in_bar']=10;
//Если return_empty=true - если страница 1 или нет вообще, то функция возвартит '' 
//Если return_empty=false, то функция вовзарит пустой блок с надписью empty_mesage
$view_bar_params_db['return_empty']=true;	
$view_bar_params_db['empty_mesage']='';		//если return_empty=false и нет страниц то вместо страниц возвращаем это поле. (например "Нет записей")
//обозначения переходов по группам и страницам.
$view_bar_params_db['buttons']['first_page']='<img src="'.Global_WWW_Path.'tpl/'.Use_Template.'/img/page_bar_first.gif" width="50" height="17" alt="Первая страница" border="0">';
$view_bar_params_db['buttons']['preview_group']='<img src="'.Global_WWW_Path.'tpl/'.Use_Template.'/img/page_bar_preview_group.gif" width="17" height="17" alt="Предыдущие '.$view_bar_params_db['numbers_in_bar'].' страниц" border="0">';
$view_bar_params_db['buttons']['preview_page']='<img src="'.Global_WWW_Path.'tpl/'.Use_Template.'/img/page_bar_preview.gif" width="17" height="17" alt="Предыдущая страница" border="0">';
$view_bar_params_db['buttons']['next_page']='<img src="'.Global_WWW_Path.'tpl/'.Use_Template.'/img/page_bar_next.gif" width="17" height="17" alt="Следующая страница" border="0">';
$view_bar_params_db['buttons']['next_group']='<img src="'.Global_WWW_Path.'tpl/'.Use_Template.'/img/page_bar_next_group.gif" width="17" height="17" alt="Следующие '.$view_bar_params_db['numbers_in_bar'].' страниц" border="0">';
$view_bar_params_db['buttons']['last_page']='<img src="'.Global_WWW_Path.'tpl/'.Use_Template.'/img/page_bar_last.gif" 	width="50" height="17" alt="Последняя страница" border="0">';
//определяем шаблоны отображения.
$view_bar_params_db['templates']['bar']='
<div class="page_bar_outer"> 
<table width="40%" border="0" cellspacing="0" cellpadding="0" class="page_bar_inner" align="center"> 
<tr>
 <td width="50">{first_page}</a></td>
 <td width="17">{preview_group}</td>
 <td width="17">{preview_page}</td>
 <td nowrap><div class="page_bar_center" align="center" nowrap>{page_bar}</div></td>
 <td width="17">{next_page}</td>
 <td width="17">{next_group}</td>
 <td width="50">{last_page}</td>
</tr> 
</table> 
</div>
';
//шаблоны для номеров страниц
$view_bar_params_db['templates']['select_page']=' <span class="page_bar_select_page"><a href="{link_to_page}">{number}</a></span> ';	//выбранная
$view_bar_params_db['templates']['no_select_page']=' <span class="page_bar_no_select_page"><a href="{link_to_page}">{number}</a></span> ';	//невыбранная
//шаблон вывода номера
//{num} - номер страницы. (после передачи через шаблоны select_page или no_select_page)
//т.е. если надо вынести [] за ссылки, то их над перенести в шабоны select_page и no_select_page
//{start} - номер первой записи на этой странице
//{end} - номер последней записи на этой странице
$view_bar_params_db['templates']['number_page']='[{num}]';

return $view_bar_params_db;
}
//========================================
?>