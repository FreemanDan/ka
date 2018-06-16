<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//комплект функций для авторобота проверки ссылок.


function links_autobot_get_next_links_db ($links_settings_db, $autobot_settings_db)
{
//функция возвращает массив со списком id ссылок на проверку.
global $system_db;
//формируем строку условий запроса
$adv_query='';

//условие "Проверять каждую ссылку не чаще чем 1 раз в ... часов"
$adv_query.="
	AND 	".Base_Prefix."links_items.FIELD_VERIFY_DATE<='".($system_db['THIS_TIME']-intval($autobot_settings_db['LIM_HOURS'])*3600)."' 
	";


//условие "Брать ссылки только добавленные за последние ... дней"
$adv_query.="
	AND 	".Base_Prefix."links_items.FIELD_CREATE_DATE>='".($system_db['THIS_TIME']-intval($autobot_settings_db['LIM_DAY'])*86400)."' 
	";

//исключаем проверку ссылок в блэклисте.
if ($links_settings_db['BLACK_LIST_ID'])
{
 //блэклист определён. исключаем из него выборку.
 $adv_query.="
	AND 	".Base_Prefix."links_items.FIELD_LINK_CAT<>'".intval($links_settings_db['BLACK_LIST_ID'])."' 
	";
}

$next_links_db=SI_sql_query("select 
	".Base_Prefix."links_items.*,
	".Base_Prefix."links_category.FIELD_NAME AS KNAME
	from ".Base_Prefix."links_items
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE 	".Base_Prefix."links_category.FIELD_ENABLE='1'
	AND	".Base_Prefix."links_items.FIELD_DISABLE_AUTOBOT='0'
	".$adv_query."
	GROUP BY ".Base_Prefix."links_items.id
	ORDER BY ".Base_Prefix."links_items.FIELD_CREATE_DATE ASC
	LIMIT ".intval($autobot_settings_db['LIM_COUNT'])."
	");

return $next_links_db;
}

//========================================================
function links_autobot_get_next_links_count ($links_settings_db, $autobot_settings_db)
{
//функция вычисляет оставшееся количество ссылок для проверки.
global $system_db;
//формируем строку условий запроса
$adv_query='';

//условие "Проверять каждую ссылку не чаще чем 1 раз в ... часов"
$adv_query.="
	AND 	".Base_Prefix."links_items.FIELD_VERIFY_DATE<='".($system_db['THIS_TIME']-intval($autobot_settings_db['LIM_HOURS'])*3600)."' 
	";


//условие "Брать ссылки только добавленные за последние ... дней"
$adv_query.="
	AND 	".Base_Prefix."links_items.FIELD_CREATE_DATE>='".($system_db['THIS_TIME']-intval($autobot_settings_db['LIM_DAY'])*86400)."' 
	";

//исключаем проверку ссылок в блэклисте.
if ($links_settings_db['BLACK_LIST_ID'])
{
 //блэклист определён. исключаем из него выборку.
 $adv_query.="
	AND 	".Base_Prefix."links_items.FIELD_LINK_CAT<>'".intval($links_settings_db['BLACK_LIST_ID'])."' 
	";
}

$next_links_db=SI_sql_query("select 
	count(distinct ".Base_Prefix."links_items.id) AS TCNT
	from ".Base_Prefix."links_items
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE 	".Base_Prefix."links_category.FIELD_ENABLE='1'
	AND	".Base_Prefix."links_items.FIELD_DISABLE_AUTOBOT='0'
	".$adv_query."
	");

return $next_links_db[0]['TCNT'];

}
//========================================================

function links_autobot_verify_links ($next_link_db, $links_settings_db, $autobot_settings_db)
{
//функция выполняет проверку сслыки и возвращает лог выполнения проверки.
//также применяет действия в зависимости от результатов проверки.
//$next_link_db - полная инфа по ссылке полученная в функе links_autobot_get_next_links_db
//$links_settings_db, $autobot_settings_db - соотв. натсройки авторобота в этом каталоге. где и ссылка и общие настройки этого каталога.

//если нет обратки
if (!$next_link_db['FIELD_RET_LINK_ADDR']) return lecho ($next_link_db['FIELD_DOMAIN']).' <span class="normal_red">Обратный адрес не указан! Проверка отменена!</span><br>';

//проверяем robots.txt
if (!links_in_open_robots ($next_link_db['FIELD_RET_LINK_ADDR']))
{
//обратка закрыта в роботсе..
//применяем санкции и возвращаем сообщение в логе.

$ex='
<fieldset class="alert_fieldset"><legend>Ошибка</legend>
Обратая ссылка закрыта в robots.txt. Адрес <a href="'.lecho ($next_link_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($next_link_db['FIELD_RET_LINK_ADDR']).'</a><br><br>';
for ($i=0; $i<count($autobot_settings_db['ACT_NO_FIND']); $i++) $ex.=link_exec_act ($autobot_settings_db['ACT_NO_FIND'][$i], $next_link_db['id'], $links_settings_db, $autobot_settings_db['MAIL_CREATE']).'<br>';
$ex.='</fieldset>';

return $ex;
}

$link_list_db=get_all_my_banner_links ();		//выбираем все ссылки из включенных баннеров для обмена.

//проверяем. установленнали одна из ссылок $link_list_db на указанной странице $RET_LINK_ADDR
//в качество дополнительной функции функция заполняет массив $_GET['href_db'] (костыль, бля.. но помоему лучше такой костыль, чем дважды читать страницу с обраткой... и так уже перегружено всё всякими чтениями.)
if (!link_verify_set_link ($link_list_db, $next_link_db['FIELD_RET_LINK_ADDR'])) 
{
//обратки нет. Ну чтож.. применяем санкции
$ex='
<fieldset class="alert_fieldset"><legend>Ошибка</legend>
Обратая ссылка по адресу <a href="'.lecho ($next_link_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($next_link_db['FIELD_RET_LINK_ADDR']).'</a> не найдена!<br><br>';
for ($i=0; $i<count($autobot_settings_db['ACT_NO_FIND']); $i++) $ex.=link_exec_act ($autobot_settings_db['ACT_NO_FIND'][$i], $next_link_db['id'], $links_settings_db, $autobot_settings_db['MAIL_CREATE']).'<br>';
$ex.='</fieldset>';

return $ex;
}
else
{
//обратка есть. Отлично. выполняем действия.

$ex='
<fieldset class="good_fieldset"><legend>Успешно</legend>
Обратая ссылка по адресу <a href="'.lecho ($next_link_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($next_link_db['FIELD_RET_LINK_ADDR']).'</a> найдена!<br><br>';
for ($i=0; $i<count($autobot_settings_db['ACT_FIND']); $i++) $ex.=link_exec_act ($autobot_settings_db['ACT_FIND'][$i], $next_link_db['id'], $links_settings_db, $autobot_settings_db['MAIL_CREATE']).'<br>';
$ex.='</fieldset>';

return $ex;
}


}
//========================================================

function links_autobot_save_log ($autobot_settings_db, $save_log_text, $next_link_db)
{
//функция сохраняет лог проверки ссылок $next_link_db.
//$autobot_settings_db - настройки быстроробота для этого каталога.
//$save_log_text - текст выполнения
//$next_link_db - база ссылок которые перебираем.
global $system_db;

//для начала удаляем все записи по этому каталогу, которые просрочились.
SI_sql_query ("DELETE FROM ".Base_Prefix."links_auto_bot_log WHERE FIELD_DATE<'".($system_db['THIS_TIME']-intval ($autobot_settings_db['LOG_SAVE_DAY'])*86400)."'");

//добавляем новую запись.
mysql_query("insert into ".Base_Prefix."links_auto_bot_log 
	(
	FIELD_DATE,
	FIELD_VERIFY_CNT,
	FIELD_VERIFY_TEXT
	) 
	values
	(
	'".intval ($system_db['THIS_TIME'])."',
	'".count($next_link_db)."',
	'".adds ($save_log_text)."'
	)");


}

//========================================================

?>