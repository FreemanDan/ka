<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//#####################################################################
//	Основные функции системы.
//#####################################################################

//=====================================================
function warn ($str) 
{
//фильтр на приходящие переменные
  $f=ereg_replace("[^a-zA-Z0-9._-]",'', $str);
  return $f;
}
//=====================================================

function adds($str) 
{
//функция экранирует спец символы в SQL запросах, где переменные не заключенны в кавычки (только для строковых переменных!)
//для числовых переменных надо использовать warn_number
//если кто спросить Нафиг? отвечу - задолбало писать полное слово addslashes
return addslashes ($str);
}
//=====================================================

function adds_array ($arr) 
{
//функция рекурсивно разбирает массив и к каждому элементу применяет adds
$key_array=array_keys ($arr);
for($i=0; $i<count ($key_array); $i++)
if (is_array ($arr[$key_array[$i]])) $arr[$key_array[$i]]=adds_array ($arr[$key_array[$i]]);
else    $arr[$key_array[$i]] = adds ($arr[$key_array[$i]]);

return $arr;
}
//=====================================================

function filters_to_data ($arr, $filters_str) 
{
//функция применят фильтер с данным.. данные могут быть массивом или просто переменной..
//если это массив, то функция рекурсивно разбирает массив и к каждому элементу применяет фильтры $filters_str

if (!is_array($arr)) return si_filters ($arr, $filters_str);	//это не массив.

//понали по массиву.
$key_array=array_keys ($arr);
for($i=0; $i<count ($key_array); $i++)
if (is_array ($arr[$key_array[$i]])) $arr[$key_array[$i]]=filters_to_data ($arr[$key_array[$i]], $filters_str);
else    $arr[$key_array[$i]] =  si_filters ($arr[$key_array[$i]], $filters_str);

return $arr;
}
//=====================================================


function getmicrotime()
{
//получаем время в микросекундах.
//функция нужна для замеров времени выполнения.
//если параметр не задан, то возвращает вермя "сейчас"
$mc=microtime();
if (func_num_args()==1) $mc=func_get_arg (0);
   list($usec, $sec) = explode(" ",$mc); 
   return ((float)$usec + (float)$sec); 
}

//=====================================================

function microtime_exec($start_microtime, $end_microtime)
{
//функция возвращает разницу между замерами микровремени. т.е. время выполнения
//третьим параметром моет передаваться степень округления числа.
//если округление не передано, значит высталяем 5
$round_number=5;
if (func_num_args()==3) $round_number=func_get_arg (2);
return number_format($end_microtime - $start_microtime, $round_number, '.', '');
}
//=====================================================


function ReadBase($base) 
{
//функция преобразует результат MySQL запроса в массив
$f=array ();
if ($base)
{
while($row=mysql_fetch_assoc($base)) $f[]=$row;
mysql_free_result($base);
}
return $f;
}
//=====================================================

function SI_sql_query ($query)
{
//функция делает запрос к базе данных. Возвращает массив с результатами.
//в переменной $system_db['LAST_QUERY_ERROR'] - будет сообщение об ошибке запроса (если был) или путая срока (еслиошибки небыло.)
//также записывается время выполнения запроса в массив $system_db['SQL_EXEC_QUERY_DB']
global $system_db;

$st_time=getmicrotime(); 	//время старта запроса.

mysql_query('SET NAMES utf8');	//эту строчку надо расскомментировать, если кодировка у хостера кривая и отличатся.
$r=mysql_query($query);
$system_db['SQL_EXEC_QUERY_DB'][]=microtime_exec($st_time, getmicrotime());	//записываем время запроса.

if (mysql_errno()) $system_db['LAST_QUERY_ERROR'][]=mysql_errno().': '.mysql_error()."\r\n".' Запрос: '.$query;

//елси запрос - ресурс. то перетаскиваем его в массив и освобожаем память на муське.
if (is_resource($r) || !eto_ne_pusto ($r)) return ReadBase ($r);	//возвращаем результат.
return $r;
}
//=====================================================

function my_in_array ($arr,$fnd) 
{
//Функция производит поиск переменной $fnd в массиве $arr  и если такая переменная есть, то возвращает YES
$f='';
if (count ($arr)>0)
{
$key_array=@array_keys ($arr);
for($i=0; $i<count ($key_array); $i++)
if (strtoupper ($arr[$key_array[$i]])==strtoupper ($fnd)) $f='YES';
}
return $f;
}
//=====================================================


function l_text ($param_str, $value)
{
//возвращает объект text
//$param_str - строка с параметрами объекта
//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="atext"
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="atext" ';
$ex='<input type="text" '.$param_str.' value="'.srp_lecho ($value).'" >';
return $ex;
}
//=====================================================

function l_password ($param_str, $value)
{
//возвращает объект text
//$param_str - строка с параметрами объекта
//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="atext"
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="atext" ';
$ex='<input type="password" '.$param_str.' value="'.srp_lecho ($value).'" >';
return $ex;
}
//=====================================================

function l_textarea ($param_str, $value)
{
//возвращает объект textarea
//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="atextarea"
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="atextarea" ';
$ex='<textarea '.$param_str.' >'.srp_lecho ($value).'</textarea>';
return $ex;
}
//=====================================================

function l_buttion ($param_str, $value)
{
//возвращает код для кнопочки 
//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="asubmit"
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="asubmit" ';
$ex='<input type="submit" '.$param_str.' value="'.srp_lecho ($value).'" >';
return $ex;
}
//=====================================================

function l_file ($param_str)
{
//возвращает код для кнопочки 
//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="afile"
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="afile" ';
$ex='<input type="file" '.$param_str.' >';
return $ex;
}
//=====================================================

function l_checkbox ($param_str, $value)
{
//возвращает код для чекбоксины.
//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="acheckbox"
 
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="acheckbox" ';
$ex='<input type="checkbox" '.$param_str.' value="'.srp_lecho ($value).'" >';
return $ex;
}
//=====================================================

function l_select ($param_str, $data, $sel_value)
{
//возвращает код для выпадающего списка select
//$data - одномерный массив значений тпа $data['1']='вариант 1'; $data['2']='вариант 2';
//в value будет записан ключ значения в массиве $data

@reset ($data); 
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="aselect" ';
$ex='<select '.$param_str.'>';
if ($data)
{
$key_array=array_keys ($data);
for($i=0; $i<count ($key_array); $i++)
{
$ex.='<option  value="'.$key_array[$i].'"';
if ('a'.$key_array[$i]=='a'.$sel_value) $ex.=' selected ';
$ex.='>'.$data[$key_array[$i]].'</option>';
}
}
$ex.='</select>';
return $ex;
}
//=====================================================

function l_radio ($param_str, $data, $sel_value)
{
//возвращает код для группы кнопок radiobutton
//$data - одномерный массив значений тпа $data['1']='вариант 1'; $data['2']='вариант 2';
//в value будет записан ключ значения в массиве $data
$ex='';
@reset ($data); 
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="aradio" ';
if ($data)
{
$key_array=array_keys ($data);
for($i=0; $i<count ($key_array); $i++)
{
$ex.='<input type="radio" '.$param_str.' value="'.$key_array[$i].'"';
if ('a'.$key_array[$i]=='a'.$sel_value) $ex.=' checked ';
$ex.='>'.$data[$key_array[$i]];
}
}
return $ex;
}
//=====================================================

function l_spec_buttion ($name, $param_str, $value, $form_name)
{
//возвращает код кнопочки с подтвержением $message (червёртый параметр параметр)
// пример l_spec_buttion ('submit_ok_delet', 'style="background: #fedada;"', 'Удалить', $form_name, 'Вы согласны?')

//Если стили не передаются в параметрах, то подключаем стиль по умолчанию class="asubmit"
if (!strpos (strtolower ($param_str),'class=') && !strpos (strtolower ($param_str),'id=')) $param_str.=' class="asubmit" ';

if (func_num_args()==5) $message=func_get_arg (4);
if (!$message) $message="Вы уверены в этом?";
$ex='<input name="'.$name.'" type="hidden" value="">';
$ex.='<input type="button" '.$param_str.' value="'.srp_lecho ($value).'" onclick="if(confirm('."'".$message."'".')) {document.forms['."'".$form_name."'".'].elements['."'".$name."'".'].value='."'".srp_lecho ($value)."'".'; document.forms['."'".$form_name."'".'].submit();}" >';
return $ex;
}
//=====================================================


function lecho ($str) 
{
//Функция выводит строку. Если задан пареметр $len , то вводиьтся будет указанное количество символов (в $str)
  $len=0;
  if (func_num_args()==2) $len=func_get_arg (1);
  if ($len>0) if (strlen ($str)>$len)  $str=substr ($str,0,$len).'...';
  $str=HTMLSpecialChars ($str);
  return $str;  
}
//=====================================================

function srp_lecho ($str) 
{
//тоже самое что и lecho, но функция убивает все слэши перед слэшованными символами.
  $len=0;
  if (func_num_args()==2) $len=func_get_arg (1);
  $str=stripslashes ($str);
  $str=lecho ($str, $len);
  return $str;  
}
//=====================================================

function get_pic_normal_size ($pic)
{
//функция возвращает  в строке нормальные размеры картинки width="20" height="20" если она существует
if (file_exists ($pic))
{
$pic_arr=@getimagesize ($pic);
if ($pic_arr[3]) return $pic_arr[3];
}
}
//=====================================================

function print_ar ($arr)
{
//функция выводит на экран массив.
echo '<pre>';
print_r ($arr);
echo '</pre>';
}
//=====================================================

function my_exit ()
{
//функция завершения работы скрипта.
include (Root_Dir.'_shell/end_site.php'); //завершение работы сайта
exit;
}
//=====================================================

function si_set_session ($name, $data)
{
//функция работает с сессий.
//в частности добавляет значение в сессию, Если сессия не запущена, то запускает её, после чего добавляет параметр.
//$name - название записи в сессии
//$data - что пишем в эту запись.
if (!is_array ($_SESSION)) session_start();
$_SESSION[$name]=$data;
}
//=====================================================

function si_get_session ($name)
{
//функция работает с сессий.
//в частности читает значение в сессию, Если сессия не запущена, то запускает её, после чего читает параметр.
//$name - название записи в сессии
if (!is_array ($_SESSION)) session_start();
return $_SESSION[$name];
}
//=====================================================

function si_chet_nechet ($num)
{
//функция возвратит 0 - если число $num нечётное, и 1 если чётное.
if (is_int ($num/2)) return 1;
return 0;
}

//=====================================================

function get_string_for_like($str)
{
//функция подготавливает строку к запросу LIKE
//экранируются спец. символы.
$str=str_replace ('%', '\%', $str);
$str=str_replace ('_', '\_', $str);
return $str;
}
//==============================================

function get_only_text ($str)
{
//функция вырезает все тэги, отрезает слэширование и применяет htmlspecialchars к строке $str
$str=srp_lecho (strip_tags ($str));
$str=strip_amp ($str);	//возвращаем "на место" все &
return $str;
}
//==============================================

function long_string_maniak ($str, $max_word_len, $penetrator)
{
//функция разбивает в строке все слова длинее $max_word_len разделителем $penetrator
$all_words_db=explode (' ', $str);
$ex='';
for ($i=0; $i<count($all_words_db); $i++) 
{
$tmp_db=si_str_split ($all_words_db[$i], $max_word_len);	//разбиваем сслово на части размером $max_word_len
$ex.=implode ($penetrator, $tmp_db).' ';
}

return trim ($ex);
}
//==============================================

function eto_ne_pusto ($str)
{
//функция возвратит false если строка = ''; 
//зачем жта функа.. хм.. для централизации верификации принятых данных.. во.. и никак иначе. 
if ('a'.$str=='a') return false;	//вот такие пироги с котятками..
return true;
}
//==============================================

function si_key_exists ($key, $arr)
{
//функция возвратит false если в массиве $arr нет элемента с ключом $key
//аналог array_key_exists только с дополнениями..
if (!is_array($arr)) return false;
if (!eto_ne_pusto ($key)) return false;
if (array_key_exists ($key, $arr)) return true;
return false;
}
//==============================================

function si_get_in_string ($arr)
{
//функция генерит строку для SQL запроса.
//$arr - одномерный массив.
//действие аналогично implode, но каждое значение массива "заворачивается" в одиночные кавычки '
//это нужно. для выборок IN, когда значение строка.. без кавычек это работать НЕБУДЕТ.
//например результат функци будет выглядеть так: "'a', 'b', 'c'"
if (!is_array($arr)) return '';
$ex="'".implode ("', '", $arr)."'";
return $ex;
}
//==============================================

function si_generate_afa ($arr, $key1, $key2)
{
//функция генерит из двумерного массива одномерный.
//в получившемся массиве ключами будут значения $key1, а значениями - значения $key2
//т.е. например. из:
//$key1='id'; $key2='name';
//$source_db[0]['id']='5'
//$source_db[0]['name']='a'
//$source_db[1]['id']='6'
//$source_db[1]['name']='b'
//$source_db[2]['id']='7'
//$source_db[2]['name']='c'
//возаратит:
//$ex_db['5']='a';
//$ex_db['6']='b';
//$ex_db['7']='c';
$ex_db=array();

if (is_array($arr))
foreach ($arr as $key => $value) 
$ex_db[$value[$key1]]=$value[$key2];

return $ex_db;
}
//==============================================

function si_generate_aka ($arr, $key)
{
//функция генерит из двумерного массива одномерный.
//в получившемся массиве ключами будут ключи массив, а значениями - значения $key
//т.е. например. из:
//$key='name';
//$source_db[0]['id']='5'
//$source_db[0]['name']='a'
//$source_db[1]['id']='6'
//$source_db[1]['name']='b'
//$source_db[2]['id']='7'
//$source_db[2]['name']='c'
//возаратит:
//$ex_db['0']='a';
//$ex_db['1']='b';
//$ex_db['2']='c';
$ex_db=array();

if (is_array($arr))
foreach ($arr as $arr_key => $value) 
$ex_db[$arr_key]=$value[$key];

return $ex_db;
}
//==============================================

function si_array_push ($arr1, $arr2)
{
//функция в массив $arr1 добавляет массив $arr2 .. И в отличие от array_merge,
//.....шучу..

if (!is_array($arr2)) return $arr1;
if (!is_array($arr1)) $arr1=array();

foreach ($arr2 as $key => $value) $arr1[$key]=$value;

return $arr1;
}
//==============================================

function si_filters ($str, $filters_str)
{
//функция применяет к строке $str набор "фильтров" $filters_str
//$filters_str - строка с перечислением фильтров через запятую.
//можно задавать как полное название фильтров, так и номера.
//0 - addslashes, 1 - stripslashes, 2 - htmlspecialchars, 3 - htmlspecialchars_decode, 4 - strip_tags, 5 - trim, 6 - warn, 7 - warn_number
//оразец вызова si_fields_filters ($str, '4,2'));

$filters_db=explode(',', $filters_str);
for ($i=0; $i<count($filters_db); $i++) $filters_db[$i]=trim($filters_db[$i]);	//пробельчики нах.

for ($i=0; $i<count($filters_db); $i++)
if (!is_array($filters_db[$i]))
switch ($filters_db[$i])
{
 case '0':
 case 'addslashes':
 $str=addslashes($str);
 break;
 
 case '1':
 case 'stripslashes':
 $str=stripslashes($str);
 break;
 
 case '2':
 case 'htmlspecialchars':
 $str=htmlspecialchars($str);
 break;

 case '3':
 case 'htmlspecialchars_decode':
 $str=htmlspecialchars_decode($str);
 break;

 case '4':
 case 'strip_tags':
 $str=strip_tags($str);
 break;

 case '5':
 case 'trim':
 $str=trim($str);
 break;

 case '6':
 case 'warn':
 $str=warn($str);
 break;

 case '7':
 case 'warn_number':
 $str=warn_number($str);
 break;
}

return $str;
}

//========================================

function si_generate_error_list ($err)
{
//функция генерит список ошибок из ошибок, разделённых <br>
if (!$err) return '';	//ошибок нет.

$err_db=explode ('<br>', $err);

$err_string_tpl=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/error_string.tpl'));	//получаем шаблон на строку.

$err_strings='';	//сюда собираем строки ошибок.
for ($i=0; $i<count($err_db); $i++)
if (eto_ne_pusto ($err_db[$i])) $err_strings.=si_field_replace (array('CONT'=>$err_db[$i]), $err_string_tpl);

//возвращаем текст ошибок через общий шаблон сообщения.
return si_ff_replace (array('STRINGS'=>$err_strings), Root_Dir.'tpl/'.Use_Template.'/error_message.tpl', 1);
}
//=============================================


function si_verify_valid_email ($email)
{
//функция возратит true , если введён правильный почтовый адрес. и false если неправильный.
//$email - почтовый адрес
if (!strpos($email, '@')) return false;
if (strlen($email)<5) return false;
return true;
}
//=====================================================

function strip_amp ($str)
{
//функция преобразует символы &amp; в нормальные &
$str=str_replace ('&amp;', '&', $str);
return $str;
}
//=====================================================


function links_get_url ($actions, $id, $page)
{
//функция формирует урл с переданными параметрами.
//внимание! параметры для функции должны быть проверены ДО её вызова, т.к. в самой функции они выводятся "как есть"!

$ex=Global_WWW_Path;

if (Use_Mod_Rewrite=='Off') 
{
//формируем урл при выключенном модреврайте.
if (eto_ne_pusto ($actions)) $ex.='index.php?actions='.$actions;
if (eto_ne_pusto ($id)) $ex.='&id='.$id;
if (eto_ne_pusto ($page)) $ex.='&page='.$page;
}
else
{
//формируем урл при включенном модреврайте.
if (eto_ne_pusto ($actions)) $ex.=$actions.'/';
if (eto_ne_pusto ($id)) $ex.=$id.'/';
if (eto_ne_pusto ($page)) $ex.=$page.'/';
}

return $ex;
}
//=======================================================

?>