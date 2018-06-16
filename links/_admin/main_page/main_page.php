<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.


//формируем описание страницы-меню
$menun_db=array();
$menun_db['name']='Основное меню';
$menun_db['items'][0]['name']='Категории';
$menun_db['items'][0]['desc']='Список категорий ссылок. Просмотр, редактирование, добавление, удаление.';
$menun_db['items'][0]['link']=$_SERVER['PHP_SELF'].'?actions=links_cat';
$menun_db['items'][0]['img']='icons/links_cat.jpg';

//вычисляем сколько у нас новых.
$tmp_db=SI_sql_query("select 
	count(".Base_Prefix."links_items.id) AS TCNT 
	from ".Base_Prefix."links_items 
	LEFT JOIN ".Base_Prefix."links_category ON ".Base_Prefix."links_category.id=".Base_Prefix."links_items.FIELD_LINK_CAT
	WHERE 	".Base_Prefix."links_items.FIELD_IS_NEW='1' 
	");

//собираем ссылку для перехода к новым ссылкам.
$for_new_link=$_SERVER['PHP_SELF'].'?actions=links_item&ok=ok&enable=all&is_new=1';
$for_add_link=$_SERVER['PHP_SELF'].'?actions=add_links_item';

$menun_db['items'][1]['name']='Ссылки';
$menun_db['items'][1]['desc']='Список ссылок. Просмотр, редактирование, добавление, удаление. 
<div style="margin-top:10px;">
<span class="normal_green"><a href="'.$for_add_link.'" title="Добавить новую ссылку в каталог"> Добавить новую </a></span>
&nbsp;&nbsp;&nbsp;
<span class="normal_red">Новых: <b>'.$tmp_db[0]['TCNT'].'</b> <a href="'.$for_new_link.'" title="Перейти к новым ссылкам"> >>> </a></span>
</div>
';
$menun_db['items'][1]['link']=$_SERVER['PHP_SELF'].'?actions=links_item&page=clear';
$menun_db['items'][1]['img']='icons/links_item.jpg';

$menun_db['items'][2]['name']='Ссылки для обмена';
$menun_db['items'][2]['desc']='Список вариантов наших ссылок для обмена';
$menun_db['items'][2]['link']=$_SERVER['PHP_SELF'].'?actions=my_links';
$menun_db['items'][2]['img']='icons/my_links.jpg';

$menun_db['items'][3]['name']='Проверка ссылок';
$menun_db['items'][3]['desc']='Полуавтоматическая проверка ссылок и рассылка информационных сообщений';
$menun_db['items'][3]['link']=$_SERVER['PHP_SELF'].'?actions=links_find_retry&page=clear';
$menun_db['items'][3]['img']='icons/links_find_retry.jpg';


//получаем всякие разные настройки и пр. инфу.
$inf_str='<div style="margin-top:5px;">';
//вычисляем сколько у нас новых.
$tmp_db=SI_sql_query("select FIELD_ENABLE from ".Base_Prefix."links_auto_bot_settings LIMIT 1");
if ($tmp_db[0]['FIELD_ENABLE']==1) $inf_str.='<span class="normal_green">Проверка включена</span> ';
	else $inf_str.='<span class="normal_red">Проверка выключена</span> ';


//получаем количество ссылок, проверенных за последние 24 часа в этом каталоге
$tmp_db=SI_sql_query("select SUM(FIELD_VERIFY_CNT) AS TSUM from ".Base_Prefix."links_auto_bot_log WHERE FIELD_DATE>='".($system_db['THIS_TIME']-60*60*24)."'");
$inf_str.='&nbsp;&nbsp;&nbsp;За последние 24 часа проверено <b>'.intval ($tmp_db[0]['TSUM']).'</b> ссылок в каталоге. <a href="'.$_SERVER['PHP_SELF'].'?actions=auto_bot_log&page=clear" title="Лог файл проверки автороботом">Подробнее >> </a>';

$inf_str.='</div>';

//формируем ссылку на запуск авторобота
$inf_str.='<div style="margin-top:5px;"><a href="'.Global_WWW_Path.'autobot/links_autobot.php" target="_blank" title="Ручной запуск авторобота">Запустить авторобота</div>';


$menun_db['items'][4]['name']='Настройки "Авторобота"';
$menun_db['items'][4]['desc']='Настройки и установки автоматической постоянной проверки базы ссылок.'.$inf_str;
$menun_db['items'][4]['link']=$_SERVER['PHP_SELF'].'?actions=auto_bot_settings';
$menun_db['items'][4]['img']='icons/auto_bot.jpg';

$menun_db['items'][5]['name']='Настройки каталога';
$menun_db['items'][5]['desc']='Настройки и установки каталога ссылок.';
$menun_db['items'][5]['link']=$_SERVER['PHP_SELF'].'?actions=links_settings';
$menun_db['items'][5]['img']='icons/links_settings.jpg';


?>
<div class="catalog_path">Действия: Главное меню "Каталог ссылок" </div>

<?=get_admin_menun ($menun_db);	//формируем меню  ?>
