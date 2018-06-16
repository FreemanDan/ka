<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//комплект дополнительных функций

//=======================================================
function get_link_settings ()
{
//функция возвращает настройки для каталога ссылок
//настройки передаются в ОДНОМЕРНОМ МАССИВЕ
global $system_db;

//получаем настройки списка.
$links_settings_db=SI_sql_query("select * from ".Base_Prefix."links_settings");
if (!$links_settings_db) $links_settings_db=array();
	else $links_settings_db=$links_settings_db[0];

if ($links_settings_db['FIELD_SETTINGS']) $links_settings_db=@unserialize (base64_decode($links_settings_db['FIELD_SETTINGS']));

$links_settings_db['REC_ON_PAGE']=intval($links_settings_db['REC_ON_PAGE']);

//проверяем значения.. и если не определены, то выставляем по умолчанию.
//-------
if ($links_settings_db['REC_ON_PAGE']<=0) $links_settings_db['REC_ON_PAGE']=10;
if ($links_settings_db['REC_ON_PAGE_ADMIN']<=0) $links_settings_db['REC_ON_PAGE_ADMIN']=$links_settings_db['REC_ON_PAGE'];
//-------

return $links_settings_db;
}
//=======================================================


function get_link_autobot_settings ()
{
//функция возвращает настройки  для авторобота
//настройки передаются в ОДНОМЕРНОМ МАССИВЕ, но поле FIELD_SETTINGS предстаdляет собой данные по форме наcтроек.
global $system_db;

//получаем настройки списка.
$autobot_settings_db=SI_sql_query("select * from ".Base_Prefix."links_auto_bot_settings");
if (!$autobot_settings_db) $autobot_settings_db=array();
	else $autobot_settings_db=$autobot_settings_db[0];

$ab_enable=$autobot_settings_db['FIELD_ENABLE'];
if ($autobot_settings_db['FIELD_SETTINGS']) $autobot_settings_db=@unserialize (base64_decode($autobot_settings_db['FIELD_SETTINGS']));

$autobot_settings_db['FIELD_ENABLE']=$ab_enable;	//специальный финт ушами, чтобы вытащить флаг проверки не из поля настроек.

return $autobot_settings_db;
}
//=======================================================

function links_get_clear_domain ($domain)
{
//функция возвращает "чистый домен" сайта.
//например было задано http://any-site.ru/page.php
//функция возвратит только any-site.ru
if (!eto_ne_pusto(strpos($domain, 'http://'))) $domain='http://'.$domain;
$domain_db=@parse_url($domain);
return trim(strtolower ($domain_db['host']));
}
//=======================================================

function links_verify_unic_domain ($domain, $disable_id)
{
//функция проверяет, есть ли в ссылка на сайт $domain
//елси такого сайта нет - false .. ну или true в другом случае.
//$disable_id - исключаемый id сссылки.
global $system_db;

$domain=links_get_clear_domain ($domain);
$no_www_domain=str_replace ('www.', '', strtolower ($domain));	//домен без www.

$tmp_db=SI_sql_query("select 
	".Base_Prefix."links_items.id
	from ".Base_Prefix."links_items 
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE 	".Base_Prefix."links_category.id>'0' 
	AND	(".Base_Prefix."links_items.FIELD_DOMAIN='".adds ($domain)."' 
		OR ".Base_Prefix."links_items.FIELD_DOMAIN='www.".adds ($domain)."'
		OR ".Base_Prefix."links_items.FIELD_DOMAIN='".adds ($no_www_domain)."')
	AND	".Base_Prefix."links_items.id<>'$disable_id'
	LIMIT 1");


if ($tmp_db) return true;
return false;
}
//=======================================================

function links_get_all_href_from_html ($html)
{
//функция возвращает массив всех ссылок из хтмл-я $html
$links = array();

$count = preg_match_all("/<a[^>]+href=([\"']?)([^\\s\"'>]+)\\1/is", $html, $matches, PREG_SET_ORDER);
for($i=0; $i < count($matches); $i++) { $links[] = $matches[$i][2]; }

$count = preg_match_all("/<frame[^>]+src=([\"']?)([^\\s\"'>]+)\\1/is", $html, $matches, PREG_SET_ORDER);
for($i=0; $i < count($matches); $i++) {$links[] = $matches[$i][2]; }

$count = preg_match_all("/<area[^>]+href=([\"']?)([^\\s\"'>]+)\\1/is", $html, $matches, PREG_SET_ORDER);
for($i=0; $i < count($matches); $i++) { $links[] = $matches[$i][2]; }

return $links;
}
//=======================================================

function links_get_remote_page ($url)
{
//функция читает страницу по адресу $url
//возвращает массив
//$ret_db['err'] - ошибки
//$ret_db['html'] - полученный хтмл


$ret_db=array();
$ret_db['err']='';
$ret_db['html']='';

if (!$url) return $ret_db;


$url_db=@parse_url($url);

//от урла отрезаем http://
$url=str_replace ('http://', '', $url);

$host=$url_db['host'];

$path=$url_db['path'];
if (eto_ne_pusto($url_db['query'])) $path.='?'.$url_db['query'];

if (!$path) $path='/';

//echo '>>'.$path.'<<';
$fp = @fsockopen($host, 80, $errno, $errstr, 30);
if (!$fp) $ret_db['err']='Ошибка'; 
else 
{
    //echo $url;
    $out = "GET $path HTTP/1.1\r\n";
    $out .= "Referer: ".$_SERVER['HTTP_HOST']."\r\n";
    $out .= "Host: $host\r\n";
    // DanFreeman пока не пойму, зачем светить linkcheker, не уберу то, что сейчас исправлю
	// было:
	// $out .= "User-Agent: Link checker\r\n";
	//стало:
	$out .= "User-Agent: Cat\r\n";
	// eof DanFreeman
    $out .= "Connection: Close\r\n\r\n";
    //echo '<hr>'.$out.'<hr>';
    fwrite($fp, $out);
    while (!feof($fp)) 
    {
        $ret_db['html'].=fgets($fp, 128);
    }
    fclose($fp);
}


return $ret_db;
}
//=======================================================

function link_verify_set_link ($link_db, $url)
{
//функция проверяет наличие ссылки из массива $link_db по адресу $url
//елси найдена хоть одна ссылка из массива, то функция возвратит true.. ну и false если ссылки не найдены или страница недоступна.
//в качестве доп. опции функция заполняет массив $_GET['href_db'] всеми найденными ссылками на странице с обраткой

$html=links_get_remote_page ($url);
if ($html['err']) return false;		//при запросе возникли ошибки.

//echo lecho ($html['html']);

$html=$html['html'];
if (!eto_ne_pusto($html)) return false;		//страница пуста

//вырезаем из хтмл куски с noindex
$html=links_cut_noindex ($html);

//получаем все ссылки со страницы
$ret_page_href_db=links_get_all_href_from_html ($html);

//вот, собственно, костыль... в однмо месте, чтобы не читать файл с обраткой дважды приходтся так извращаеться.
//вообще, в гет пихаю только потому, что этот массив суперглобальный и не более того.. global делать нехочется.. воти и извращаюсь.
$_GET['href_db']=$ret_page_href_db;

//перебираем ссылки и смотрим.. есть ли совпадения.
for ($i=0; $i<count($link_db);$i++) 
for ($z=0; $z<count($ret_page_href_db);$z++) 
if (links_ravno_links ($link_db[$i], $ret_page_href_db[$z])) return true;

//ссылки не нашёл.
return false;
}
//=======================================================

function links_ravno_links ($link1, $link2)
{
//функция проверяет - равны ли ссылки.
//если равны, то возвращает true елси нет, то false
//если хоть одна ссылка пустая, то возвращаем false
if (!eto_ne_pusto(trim($link1)) || !eto_ne_pusto(trim($link2))) return false;

if ($link1==$link2) return true;

//отрезаем конечные слэши (если есть) и сравниваем.
$tmp1=$link1;
$tmp2=$link2;
if ($link1[strlen($link1)-1]=='/') $link1=substr ($link1, 0, strlen($link1)-1);
if ($link2[strlen($link2)-1]=='/') $link2=substr ($link2, 0, strlen($link2)-1);

if ($link1==$link2) return true;

//дальше отрезаем www. и сравниваем.
$link1=str_replace ('http://www.', 'http://', $link1);
$link2=str_replace ('http://www.', 'http://', $link2);

if ($link1==$link2) return true;

//ссылки несходятся.
return false;
}
//=======================================================

function links_mod_href_db ($ret_page_href_db)
{
//функция раскладывает массив ссылок на 2 параметра domain и total
//$ret_db['domain'] - на сколько разных сайтов ведут ссылки
//$ret_db['total'] - сколько всего исходящих ссылок

$ret_db=array();
$ret_db['domain']=0;
$ret_db['total']=0;

$tmp_domain=array();	//служебный массив для подсчёта уникальных доменов

for ($i=0; $i<count($ret_page_href_db);$i++)
if ($ret_page_href_db[$i])
{
$pos=strpos ($ret_page_href_db[$i], 'http://');
if (eto_ne_pusto($pos) && $pos==0)
{
 $tmp_domain[links_get_clear_domain ($ret_page_href_db[$i])]='ok';
 $ret_db['total']++;
}

}

$ret_db['domain']=count($tmp_domain);

return $ret_db;
}
//=======================================================

function link_verify_any_ret_link ($our_link, $url)
{
//функция проверяет на удалённой странице $url наличие хотябы одной ссылки на сайт укзаанный в $our_link
//возвратит true если наёдет там ссылку на домен $our_link и false если не найдёт или ещё чего...

//проверяем роботс.
if (!links_in_open_robots ($url)) return false;


//генерим массив двух вариантов ссылок.
$our_link_db=array();
$our_link_db[]=links_get_clear_domain ($our_link);
$our_link_db[]='www.'.str_replace ('www.', '', links_get_clear_domain ($our_link));

//получаем все ссылки на удалённой странице.
$html=links_get_remote_page ($url);
if ($html['err']) return false;		//были ошибки при запросе страницы.

//вырезаем из хтмл куски с noindex
$html['html']=links_cut_noindex ($html['html']);

$html_link_db=links_get_all_href_from_html ($html['html']);	//получаем все ссылки из полученного хмл

//прогоняем и проверяем - естьли хоть одна ссылка на сайт $our_link
for ($i=0; $i<count($html_link_db); $i++)
if ($html_link_db[$i])
{
$clear_link=links_get_clear_domain ($html_link_db[$i]);

if (my_in_array($our_link_db, $clear_link)) return true;	//ссылка есть. уряяя.
}

//ссылку ненашёл
return false;
}
//=======================================================

function links_get_item_full_data ($item_id)
{
//функция возвращает массив с полной инфой о ссылке.
//$item_id - id ссылки.

$item_db=SI_sql_query("select 
	".Base_Prefix."links_items.*,
	".Base_Prefix."links_category.FIELD_NAME AS KNAME
	from ".Base_Prefix."links_items
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE ".Base_Prefix."links_items.id='".intval($item_id)."'
	");

return $item_db[0];
}
//=======================================================

function links_get_link_addr ($item_db, $links_settings_db)
{
//функция возвращает полный адрес на ссылку $item_db
//$links_settings_db - настройки списка.

//получаем сколько включенных ссылок в этом каталоге ПЕРЕД этой ссылкой.
$cnt_db=SI_sql_query("select 
	count(id) AS TCNT
	from ".Base_Prefix."links_items
	WHERE 	FIELD_LINK_CAT='".intval($item_db['FIELD_LINK_CAT'])."'
	AND	FIELD_SORT<='".intval($item_db['FIELD_SORT'])."'
	AND	FIELD_ENABLE='1'	
	");

$addr_to_link='';


//в зависимости от включенного/выключенного модреврайта формируем ссылку на страницу с добавленной ссылкой.
//также если страниц больше, чем 1, то добавляем номер со страницей.
$page='';
if ($cnt_db[0]['TCNT']>$links_settings_db['REC_ON_PAGE']) $page.=ceil($cnt_db[0]['TCNT']/$links_settings_db['REC_ON_PAGE']);

return 'http://'.$_SERVER['HTTP_HOST'].links_get_url ('cat', $item_db['FIELD_LINK_CAT'], $page);
}
//=======================================================

function links_verify_valid_href_code ($html, $site)
{
//функция проверяет, нетли "левых" ссылок в загружаемом хтмле
//т.е. все ссылки в коде $html должны вести на одну из страниц сайта $site
//если левых ссылок нет, то true иначе false

$site=links_get_clear_domain ($site);	//подготавливаем урл

$links_db=links_get_all_href_from_html ($html);		//получаем все ссылки

//print_ar ($links_db);

for ($i=0; $i<count($links_db); $i++)
{
$tmp_host=links_get_clear_domain ($links_db[$i]);
if ($site!=$tmp_host) return false;
}

return true;
}
//=======================================================

function links_add_bad_ball ($item_db, $links_settings_db)
{
//функа увеличивает штрафф. баллы 
//и если штрафоф больше чем $links_settings_db['MAX_BAD_BALLS'] то выполняем действие $links_settings_db['BAD_BALLS_ACT']
//0 - выставляем ссылке флаг "автоперенос", 1 - выключаем ссылку и переносим в $links_settings_db['BLACK_LIST_ID']
//$item_db - инфа по ссылке полученные функой links_get_item_full_data
//$links_settings_db - настройки этого каталога

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_BAD_BALLS=FIELD_BAD_BALLS+1 WHERE id='".intval($item_db['id'])."'");

if ($item_db['FIELD_BAD_BALLS']>=$links_settings_db['MAX_BAD_BALLS'])
{
//штрафные баллы превысили максимальный предел.
$is_modify=0;	//флаг изменения

if ($links_settings_db['BAD_BALLS_ACT']==1)
{
//--------
//выполняем действия 1 (отрубаем и в чёрный лист)
//если ссылка включена - выключаем её.
if ($item_db['FIELD_ENABLE']) 
{
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='0' WHERE id='".intval($item_db['id'])."'");
$is_modify=1;	//1 - была отключена
}

//если есть каталог определённый как "чёрный лист", то переносим туда ссылку
if ($links_settings_db['BLACK_LIST_ID'] && $item_db['FIELD_LINK_CAT']!=$links_settings_db['BLACK_LIST_ID'])
{
//переосим в чёрный список
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_LINK_CAT='".intval($links_settings_db['BLACK_LIST_ID'])."' WHERE id='".intval($item_db['id'])."'");
$is_modify=2;	//2 - была перенесена
}
//--------
}

if ($links_settings_db['BAD_BALLS_ACT']==0)
if ($item_db['FIELD_FOR_DEL']==0)
{
//выполняем действие 0 - выставляем флаг "автоперенос"
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_FOR_DEL='1' WHERE id='".intval($item_db['id'])."'");
$is_modify=3;	//3 - получила автоперенос
}

//если стоит флаг "уведомлять при быстрых операциях"
if ($links_settings_db['FAST_MAIL_CREATE'])
{
//в зависимости от $is_modify формируем уведомление.
if ($is_modify==1) links_send_message ('off', $item_db['id'], $links_settings_db);
if ($is_modify==2) links_send_message ('black_list', $item_db['id'], $links_settings_db);
if ($is_modify==3) links_send_message ('autoperenos', $item_db['id'], $links_settings_db);
}

}

}
//=======================================================

function links_verify_mirrors_domain ($domain)
{
//функция проверки зеркал возвратит сообщение о зеркалах.
//или возвратит пустую строку, если всё ок.
//$domain - домен сайта.
$domain=strtolower (links_get_clear_domain ($domain));

//проверяем через яндекс.
$ya_url='http://www.yandex.ru/yandsearch?serverurl='.lecho ($domain);

$html=links_get_remote_page ($ya_url);	//получаем хтмл.
if ($html['err']) return 'При обращении к Yandex.ru произошли ошибки. Попробуйте занести ссылку позже.<br>';

//получаем все ссылки.
$tmp_db=links_get_all_href_from_html ($html['html']);

//print_ar ($tmp_db);

//теперь вырезаем все ссылки из списка, которые ведут на yandex.ru
$all_links_db=array();
for ($i=0; $i<count($tmp_db); $i++)
if (!eto_ne_pusto(strpos(strtolower ($tmp_db[$i]),'yandex.ru')) && eto_ne_pusto(strpos(strtolower ($tmp_db[$i]),'http://')))
if (trim($tmp_db[$i]))
$all_links_db[]=links_get_clear_domain (strtolower ($tmp_db[$i]));

//print_ar ($all_links_db);

if ($all_links_db[0]!=$domain) return 'Сайт "'.lecho ($domain).'" является зеркалом сайта "'.$all_links_db[0].'"! Будет лучше и мне и Вам, если Вы будете ставить ссылки на свой основной сайт.<br>';

//ну типа всё ок.
return;
}
//=======================================================

function links_verify_yandex_index ($domain)
{
//функция проверит наличие сайт в индексе яндекса и если сайт не индексирован, то возвратит тект ошибки.
//или возвратит пустую строку, если всё ок.
//$domain - домен сайта.
$domain=strtolower (links_get_clear_domain ($domain));

//проверяем через яндекс.
$ya_url='http://www.yandex.ru/yandsearch?serverurl='.lecho ($domain);

$html=links_get_remote_page ($ya_url);	//получаем хтмл.
if ($html['err']) return 'При обращении к Yandex.ru произошли ошибки. Попробуйте занести ссылку позже.<br>';

//получаем все ссылки.
$tmp_db=links_get_all_href_from_html ($html['html']);

//print_ar ($tmp_db);

//теперь вырезаем все ссылки из списка, которые ведут на yandex.ru
$all_links_db=array();
for ($i=0; $i<count($tmp_db); $i++)
if (!eto_ne_pusto(strpos(strtolower ($tmp_db[$i]),'yandex.ru')) && eto_ne_pusto(strpos(strtolower ($tmp_db[$i]),'http://')))
if (trim($tmp_db[$i]))
$all_links_db[]=links_get_clear_domain (strtolower ($tmp_db[$i]));

if (!$all_links_db) return 'Сайт не зарегистрирован в Yandex.ru и скрипт проверки не смог получить данных о зеркалах Вашего сайта. Попробуйте разобраться лично перейдя по <a href="'.$ya_url.'" target="_blank" title="Проверить сайт в Yandex.ru">ссылке</a>.<br>';

//ну типа всё ок.
return;
}
//=======================================================

function links_getTIC($__URL)
{
//функа определения тицки.
//небуду изобретать велосипед и воспользуюсь взятой отсюда: http://forum.searchengines.ru/showpost.php?p=1175233&postcount=3

 $__CY   = 0;
 $__NURL = str_replace("www.", "", $__URL);
 $__NURL = str_replace("http://", "", $__NURL);
 $__CCY  = "http://search.yaca.yandex.ru/yca/cy/ch/".$__NURL."/";
 $__IND  = join("", file("$__CCY"));

 $__POS   = strpos($__IND, str_replace("http://www.", "", $__NURL).
                          "&numdoc=10&viddoc=full&sserver=0&ci=");
 $__CUT  = substr($__IND, $__POS);
 ereg('[[:digit:]]+</a>', $__CUT, $__POS);

 if(isset($__POS[0]))
   $__CY = str_replace("</a>", "", $__POS[0]);
 else
   $__CY = 0;

 return($__CY);
}
//=======================================================

//комплект функций для определения PR
//unsigned shift right
function links_PR_zeroFill($a, $b)
{
   $z = hexdec(80000000);
       if ($z & $a)
       {
           $a = ($a>>1);
           $a &= (~$z);
           $a |= 0x40000000;
           $a = ($a>>($b-1));
       }
       else
       {
           $a = ($a>>$b);
       }
       return $a;
}


function links_PR_mix($a,$b,$c) 
{
 $a -= $b; $a -= $c; $a ^= (links_PR_zeroFill($c,13));
 $b -= $c; $b -= $a; $b ^= ($a<<8);
 $c -= $a; $c -= $b; $c ^= (links_PR_zeroFill($b,13));
 $a -= $b; $a -= $c; $a ^= (links_PR_zeroFill($c,12));
 $b -= $c; $b -= $a; $b ^= ($a<<16);
 $c -= $a; $c -= $b; $c ^= (links_PR_zeroFill($b,5));
 $a -= $b; $a -= $c; $a ^= (links_PR_zeroFill($c,3));  
 $b -= $c; $b -= $a; $b ^= ($a<<10);
 $c -= $a; $c -= $b; $c ^= (links_PR_zeroFill($b,15));
 
 return array($a,$b,$c);
}

function links_PR_GCH($url, $length=null, $init=0xE6359A60) {
   if(is_null($length)) {
       $length = sizeof($url);
   }
   $a = $b = 0x9E3779B9;
   $c = $init;
   $k = 0;
   $len = $length;
   while($len >= 12) {
       $a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24));
       $b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24));
       $c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24));
       $mix = links_PR_mix($a,$b,$c);
       $a = $mix[0]; $b = $mix[1]; $c = $mix[2];
       $k += 12;
       $len -= 12;
   }

   $c += $length;
   switch($len)              /* all the case statements fall through */
   {
       case 11: $c+=($url[$k+10]<<24);
       case 10: $c+=($url[$k+9]<<16);
       case 9 : $c+=($url[$k+8]<<8);
         /* the first byte of c is reserved for the length */
       case 8 : $b+=($url[$k+7]<<24);
       case 7 : $b+=($url[$k+6]<<16);
       case 6 : $b+=($url[$k+5]<<8);
       case 5 : $b+=($url[$k+4]);
       case 4 : $a+=($url[$k+3]<<24);
       case 3 : $a+=($url[$k+2]<<16);
       case 2 : $a+=($url[$k+1]<<8);
       case 1 : $a+=($url[$k+0]);
        /* case 0: nothing left to add */
   }
   $mix = links_PR_mix($a,$b,$c);
   /*-------------------------------------------- report the result */
   return $mix[2];
}

//converts a string into an array of integers containing the numeric value of the char
function links_PR_strord($string) {
   for($i=0;$i<strlen($string);$i++) {
       $result[$i] = ord($string{$i});
   }
   return $result;
}


function links_PR_getPR($_url) 
{
//собственно сама функа получения ПР.
//$_url - урл.. соотвественно.
   $url = 'info:'.$_url;
   $ch = links_PR_GCH(links_PR_strord($url));
   $url='info:'.urlencode($_url);
   $pr = file("http://www.google.com/search?client=navclient-auto&ch=6$ch&ie=UTF-8&oe=UTF-8&features=Rank&q=$url");
   $pr_str = implode("", $pr);
   return substr($pr_str,strrpos($pr_str, ":")+1);
}

//=======================================================

function links_cut_noindex ($html)
{
//вырезает из хтмла участки кода с noindex
$html = preg_replace('/noindex.*?\/noindex/s', '',strtolower ($html));	
return $html;
}
//=======================================================

function link_perenos ($item_id, $links_settings_db)
{
//функция проверяет, может ли быть ссылка $item_id вставлена вместо любой другой в тойже категории
//но с флагом "автоперенос".
//если такая ссылка есть, то её выключаем и кидаем в "чёрный список", а новую ставим вместо неё.
//$item_id - ид ссылки которую пытаемся воткнуть вместо ссылки с автопереносом.
//$links_settings_db - настройки

$item_db=links_get_item_full_data ($item_id);	//получаем полную инфу о ссылке.

if (!$item_db) return;		//вот хня...

//берём первую ссылку с флагом "FIELD_FOR_DEL" из той же категории.

$tmp_db=SI_sql_query("select 
	id, 
	FIELD_SORT
	from ".Base_Prefix."links_items
	WHERE 	FIELD_FOR_DEL='1'
	AND 	FIELD_ENABLE='1'
	AND 	FIELD_LINK_CAT='".intval($item_db['FIELD_LINK_CAT'])."'
	LIMIT 1
	");

if (!$tmp_db) return;	//нет ссылок для автопереноса

//выполняем автоперенос

//сначала переносим плохую ссылку
if ($links_settings_db['BLACK_LIST_ID']) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_LINK_CAT='".intval($links_settings_db['BLACK_LIST_ID'])."' WHERE id='".intval($tmp_db[0]['id'])."'");
SI_sql_query("UPDATE ".Base_Prefix."links_items 
	SET 
	FIELD_ENABLE='0', 
	FIELD_FOR_DEL='0', 
	FIELD_SORT='".intval($item_db['FIELD_SORT'])."' 
	WHERE id='".intval($tmp_db[0]['id'])."'
	");
	
//теперь пишем на её место новую.
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_SORT='".intval($tmp_db[0]['FIELD_SORT'])."' WHERE id='$item_id'");

//уведомляем владельца плохой ссылки о том, что пиздец пришёл его ссылке.
//если выстален соотв. параметр в настройках
if ($links_settings_db['DEF_FAST_MAIL']) links_send_off (links_get_item_full_data ($tmp_db[0]['id']), $links_settings_db);

}
//=======================================================

function links_generate_edit_key ()
{
//функция генерит ключ для редактирования ссылки.

$str='';

$symb_str='123456789123456789123456789ABCDEF123456789QWERT123456789YUPZX123456789CVB123456789NMLK123456789JHGFD123456789SA123456789123456789';
for ($i=0; $i<3; $i++) 
{
 for ($z=0; $z<4; $z++) 
 {
  $str.=$symb_str[rand(0, strlen($symb_str)-1)];
 }
if ($i<2) $str.='-';
}

return $str;
}
//=======================================================


function links_in_open_robots ($link_addr)
{
//функция проверяет, не закрытли адрес $link_addr в robots.txt..
//возвратит false, если ссылка закрыта в роботсе и true если открыта.

if (!eto_ne_pusto($link_addr)) return true;		//адрес пустой.. шоб небыло непоняток..

$url_db=parse_url($link_addr);

$robots_url='http://'.$url_db['host'].'/robots.txt';

//формируем урл для проверки. урл без домена и фрагментов.
$verify_link=$url_db['path'];
if (eto_ne_pusto($url_db['query'])) $verify_link.='?'.$url_db['query']; 

$html=links_get_remote_page ($robots_url);
if ($html['err']) return true;		//при запросе возникли ошибки. если роботс получить нельзя - значит ссылка разрешена.


$disabled_db=array();		//массив с отключенными путями.

$tmp_db=@explode("\r\n", $html['html']);
for ($i=0; $i<count ($tmp_db); $i++)
if (eto_ne_pusto(strpos(strtolower ($tmp_db[$i]), 'disalow'))) 
{
 $tmp2_db=explode (':',$tmp_db[$i]);
 if (eto_ne_pusto (trim ($tmp2_db[1]))) $disabled_db[]=trim ($tmp2_db[1]);	//добавляем закрытый путь.
}

//теперь смотрим, есть ли вхождения закрытых путей в указанном адресе.
for ($i=0; $i<count($disabled_db); $i++) if (eto_ne_pusto(strpos($verify_link, $disabled_db[$i]))) return false;

return true;
}
//=================================================

function get_all_my_banner_links ()
{
//функция возвращает в массиве все ссылки из включенных баннеров обраток.

$tmp_str='';
$links_variants_db=SI_sql_query("select FIELD_CODE from ".Base_Prefix."links_variants WHERE FIELD_ENABLE='1' ORDER BY FIELD_SORT ASC");
for ($i=0; $i<count($links_variants_db); $i++)  $tmp_str.=$links_variants_db[$i]['FIELD_CODE'];


//для начала получаем все ссылки из кодов для обмена.
$link_list_db=links_get_all_href_from_html ($tmp_str);		//получаем все ссылки из блоков ссылок для обмена.


//добавляем в список проверяемых ссылок этот сайт с www и без..
$this_tmp_host=str_replace ('www.', '', strtolower ($_SERVER['HTTP_HOST']));	//домен без www.
$link_list_db[]='http://'.$this_tmp_host;	//добавляем просто ссылку на этот ресурс (на всякий пожарный..).
$link_list_db[]='http://www.'.$this_tmp_host;	//добавляем просто ссылку на этот ресурс (на всякий пожарный..).

return $link_list_db;
}
//=================================================

?>