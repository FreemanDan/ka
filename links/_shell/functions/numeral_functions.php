<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//#############################################################
//
//		Функции для работы с числами
//
//                      (c)  Kelkos. 2006
//#############################################################


//=====================================================
function warn_number ($str) 
{
//фильтр на приходящие переменные. Возваращает число.
//дробная часть разделена ТОЧКОЙ
  $str=str_replace(",",'.', $str);
  $str=ereg_replace('[^eE0-9.-]','', $str);
  $str=floatval ($str);
  $str=str_replace(",",'.', $str);
  return $str;
}
//=====================================================

?>