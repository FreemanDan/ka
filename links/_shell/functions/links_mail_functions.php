<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//функции для формирования почтовых уведомлений о изменениях в ссылках.


function links_generate_links_block ()
{
//функция генерит блок с нашими ссылками для обмена
//$cid - id этого каталога ссылок в меню.

$text='<div style="font-family: Verdana; font-size:11px; margin-top:5px; margin-bottom:5px;">
Пожалуйста, разместите у себя на сайте одну из наших ссылок. 
После того, как Вы разместите нашу ссылку у себя сообщите нам адрес страницы с нашей ссылкой.
Варианты наших ссылок для обмена:';

$links_variants_db=SI_sql_query("select * from ".Base_Prefix."links_variants WHERE FIELD_ENABLE='1' ORDER BY RAND() LIMIT 2");
for ($i=0; $i<count($links_variants_db); $i++) {

$text.='<fieldset style="padding:3px;"><legend><b>Вариант '.($i+1).'</b></legend>'
.'<p><strong>Название:</strong><br>'
.lecho ($links_variants_db[$i]['FIELD_SHORT_NAME'])
.'</p>'
.'<p><strong>Html-код с подробным описанием:</strong><br>'
.lecho ($links_variants_db[$i]['FIELD_CODE'])
.'</p>'
.'<p><strong>Подробное описание:</strong><br>'
. lecho(strip_tags($links_variants_db[$i]['FIELD_CODE']))
.'</p>'
.'<p><strong>Html-код баннера:</strong><br>'
.lecho ($links_variants_db[$i]['FIELD_BANNER'])
.'</p></fieldset>';
}
$text.='</div>';
return $text;
}
//=====================================================================

function links_generate_item_full_info ($item_db)
{
//функа генерит блок подробной инфы о ссылке.
//$item_db - массив с инфой о сслке , полученный через функу links_get_item_full_data

$text='<div style="font-family: Verdana; font-size:11px; margin-top:5px; margin-bottom:5px;">'."\r\n";
$text.='<fieldset style="padding:3px;"><legend><b>Информация о Вашей ссылке:</b></legend>'."\r\n";
$text.='Ваш сайт: '.lecho ($item_db['FIELD_DOMAIN']).'<br>'."\r\n";
$text.='Категория: "'.$item_db['KNAME'].'"<br>'."\r\n";
$text.='Название Вашего сайта: '.lecho ($item_db['FIELD_NAME']).'<br>'."\r\n";
$text.='Адрес Вашего баннера: '.lecho ($item_db['FIELD_IMG_ADDR']).'<br>'."\r\n";
$text.='Адрес страницы на Вашем сайте с обратной ссылкой: '.lecho ($item_db['FIELD_RET_LINK_ADDR']).'<br>'."\r\n";
$text.='Ваш HTML код: <i>'.lecho ($item_db['FIELD_TEXT_HTML']).'</i><br>'."\r\n";
$text.='Дата добавления: '.date ('d-m-Y H:i', $item_db['FIELD_CREATE_DATE']).'<br>'."\r\n";
$text.='</fieldset></div>'."\r\n";

return $text;
}
//=====================================================================


function links_generate_moder_block ($item_db, $links_settings_db)
{
//функция генерит блок с инфой для связи с модератором каталога.
//$item_db - инфа о ссылке
//$links_settings_db - настройки каталога

$text='';

if ($links_settings_db['NEW_MAIL_NOTIC'])
{
$text.='<br><br><br>'."\r\n";
$text.='<div style="font-family: Verdana; font-size:11px; margin-top:5px; margin-bottom:5px; border:1px solid #a0a0a0;" align="center">'."\r\n";
$text.='<br><b>По всем вопросам обращайтесь к администратору каталога ссылок <a href="mailto:'.$links_settings_db['NEW_MAIL_NOTIC'].'">'.$links_settings_db['NEW_MAIL_NOTIC'].'</a></b><br><br>'."\r\n";
$text.='</div>'."\r\n";
}


return $text;
}
//=====================================================================

function links_generate_edit_block ($item_db, $links_settings_db)
{
//функция генерит блок с инфой для самостоятельно редактирования пользователем ссылки.
//$item_db - инфа о ссылке
//$links_settings_db - настройки каталога

$text='';

//если редактирование запрещенно, то возвращаем уведомление о невозможности редактирования.
if (!$links_settings_db['EDIT'])
{
$text.='<div style="font-family: Verdana; font-size:11px; margin:5px 0px 5px 0px; padding:10px; border:1px solid #a0a0a0;">'."\r\n";
$text.='Вы не можете самостоятельно изменять свои данные в нашем каталоге "'.$_SERVER['HTTP_HOST'].links_get_url ('edit', '', '').'"<br>'."\r\n";
$text.='Для любых изменений Вашей ссылки в нашем каталоге Вам нужно <a href="http://'.$_SERVER['HTTP_HOST'].links_get_url ('message', '', '').'" target="_blank">связяться с нашим модераторм</a>'."\r\n";
$text.='</div>'."\r\n";
}
else
{
$link_to_edit='http://'.$_SERVER['HTTP_HOST'].links_get_url ('edit', '', '');
$text.='<div style="font-family: Verdana; font-size:11px; margin:5px 0px 5px 0px; padding:10px; border:1px solid #a0a0a0;">'."\r\n";
$text.='Вы можете самостоятельно изменить свои данные в нашем каталоге "'.$_SERVER['HTTP_HOST'].links_get_url ('edit', '', '').'"<br>'."\r\n";
$text.='перейдя по ссылке: <a href="'.$link_to_edit.'" target="_blank">'.$link_to_edit.'</a> в форму редактирования.<br>'."\r\n";
$text.='Ваш код для редактирования ссылки: <b>'.lecho ($item_db['FIELD_KEY_FOR_EDIT']).'</b>';
$text.='</div>'."\r\n";
}

return $text;
}
//=====================================================================

function links_generate_link_addr_block ($item_db, $links_settings_db)
{
//функция генерит блок с информацией о адресе размещения ссылки партнёра в нашем каталоге.
$text.='<div style="font-family: Verdana; font-size:11px; margin-top:5px; margin-bottom:5px;">'."\r\n";

if ($item_db['FIELD_LINK_CAT']!=$links_settings_db['BLACK_LIST_ID'])
{
$addr_to_link=links_get_link_addr ($item_db, $links_settings_db);	//получаем полную ссылку на ссылку.. гыыыы... тафтология..
if ($item_db['FIELD_ENABLE']==1) $text.='Ваша ссылка сейчас доступна по адресу <a href="'.$addr_to_link.'">'.$addr_to_link.'</a>'."\r\n";
		else $text.='После проверки модератором Ваша ссылка может быть доступна по адресу <a href="'.$addr_to_link.'">'.$addr_to_link.'</a>'."\r\n";

//елси ссылка имеет флаг "автоперенос"
if ($item_db['FIELD_FOR_DEL']) $text.='<span style="color:#ff0000;">Ваша сылка в ближайшее время будет удалена из каталога.</span>'."\r\n";

}
else $text.='<span style="color:#ff0000;">Ваша сылка в данный момент находится в нашем "Чёрном списке" и не может публиковаться на сайте.</span>'."\r\n";

$text.='</div>'."\r\n";
return $text;
}
//=====================================================================


function links_send_create ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о создании ссылки через форму на сайте
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога
global $system_db;

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";

if ($item_db['FIELD_USER_MAIL'])
{
//если есть пользовательская почта, то отправляем ему уведомление.
$subj='Ссылка принята в каталог "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Ваша ссылка принята в "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'".
<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

$text.='<b>ВНИМАНИЕ!</b> Модератор каталога может изменить категорию вашей ссылки, 
если выбранная Вами категория не совсем точно соответствует тематике вашего сайта! 
В этом случае Вам придёт уведомление о изменении адреса на страницу с Вашей ссылкой.<br><br>'."\r\n";

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_edit_block ($item_db, $links_settings_db);	//добавляем блок к адресом для редактированием ссылки.
$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>';
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}

//проверяем настройки.. елси выставленно "уведомить админа", то отправляем письмо на почту админа.
if ($links_settings_db['NEW_MAIL_NOTIC'] && $links_settings_db['ADMIN_NOTIC'])
{
//отправляем письмо админу о новой ссылке.
$subj='Новая ссылка в "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'" '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Пользователь с IP '.$_SERVER['REMOTE_ADDR'].' добавил ссылку id='.intval($item_db['id']).'<br>'."\r\n";
$text.='Категория: "'.$item_db['KNAME'].'"<br>'."\r\n";
$text.='Адрес сайта: '.lecho ($item_db['FIELD_DOMAIN']).'<br>'."\r\n";
$text.='Название сайта: '.lecho ($item_db['FIELD_NAME']).'<br>'."\r\n";
$text.='Имя пользователя: '.lecho ($item_db['FIELD_USER_NAME']).'<br>'."\r\n";
$text.='Почта пользователя: '.lecho ($item_db['FIELD_USER_MAIL']).'<br>'."\r\n";
if ($item_db['FIELD_RET_LINK_ADDR']) $text.='Адрес с обраткой: <a href="'.lecho($item_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'</a><br>'."\r\n";
$text.='HTML ссылки: '.lecho ($item_db['FIELD_TEXT_HTML']).'<br>'."\r\n";
$text.='</div>'."\r\n";

SI_send_mail ($links_settings_db['NEW_MAIL_NOTIC'], $subj, $text);
}

}
//=====================================================================


function links_send_create_admin ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о создании ссылки через админку
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога
global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;

//если есть пользовательская почта, то отправляем ему уведомление.

$subj='Ваша ссылка добавлена в каталог '.$_SERVER['HTTP_HOST'].Global_WWW_Path.' '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Модератор сайта '.$_SERVER['HTTP_HOST'].' добавил Вашу ссылку в свой каталог сайтов.
<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.
$text.='</div>';

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================


function links_send_edit ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о изменении ссылки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Модератор каталога '.$_SERVER['HTTP_HOST'].Global_WWW_Path.' отредактировал Вашу ссылку. '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br><br>';

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>';

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_black_list ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о переносе ссылки в чёрный список
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Модератор каталога '.$_SERVER['HTTP_HOST'].Global_WWW_Path.' переместил Вашу ссылку в "Чёрный список". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Ваша ссылка была перенесена в "Чёрный лист" ссылок нашего сайта. <br>
Вы больше не сможете добавлять в наш каталог свой сайт "'.lecho($item_db['FIELD_DOMAIN']).'". <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Искренне сожалеем.<br>'."\r\n";


//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================


function links_send_on ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о включении ссылки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Ваша ссылка "включена" в каталоге "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);
$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br><br>';

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.
$text.='</div>';

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_off ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о перемещении ссылки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Ваша ссылка "выключена" в каталоге "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'" . '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
В данный момент Ваша ссылка <b>НЕ ОТОБРАЖАЕТСЯ</b> в нашем каталоге ссылок <a href="http://'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'" target="_blank">"'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'"</a>.
<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>';

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_move ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о переносе ссылки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Ваша ссылка перенесена в "'.$item_db['KNAME'].'" в каталоге ссылок "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'" '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Администратор переместил Вашу ссылку в раздел "'.$item_db['KNAME'].'"
<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>';
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================


function links_send_delete ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю о удалении ссылки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Ваша ссылка удалена из каталога "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Модератор удалил Вашу ссылку из <a href="http://'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'" target="_blank">"'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'"</a>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;Искренне сожалеем.
<br><br>'."\r\n";


//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================


function links_send_gde_obratka ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю с просьбой уточнить адрес обратки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Проверяющий бот не нашёл обратную ссылку на на Вашем сайте. '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Проверяющий бот не нашёл у Вас обратную ссылку по ранее 
указанному адресу <a href="'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'">'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'</a><br>
Вполне вероятно, что наша ссылка у Вас находится теперь по другому адресу в результате естественных флуктуаций ссылок в Вашем каталоге.<br>
Пожалуста, <a href="http://'.$_SERVER['HTTP_HOST'].links_get_url ('message', '', '').'" target="_blank">сообщите</a> нам новый адрес нашей ссылки или установите <a href="http://'.$_SERVER['HTTP_HOST'].links_get_url ('add', '', '').'" target="_blank">любой наш HTML для обмена</a>. <br>
Спасибо.<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_edit_block ($item_db, $links_settings_db);	//добавляем блок к адресом для редактированием ссылки.

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================


function links_send_please_set_retry ($item_db, $links_settings_db)
{
//функция отправляет письмо пользователю с просьбой на установку нашей ссылки
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Пожалуста установите нашу ссылку на Вашем сайте. '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Пожалуста, установите нашу ссылку на Вашем сайте и <a href="http://'.$_SERVER['HTTP_HOST'].links_get_url ('message', '', '').'" target="_blank">сообщите</a> нам адрес страницы с нашей ссылкой.<br>
Варианты наших ссылок ниже. (выбирайте любую)<br>
Спасибо.<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_edit_block ($item_db, $links_settings_db);	//добавляем блок к адресом для редактированием ссылки.

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_good_message ($item_db, $links_settings_db)
{
//функция отправляет уведомление партнёрам о расположении ссылки в нашем каталоге.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Ваша ссылка в каталоге сайта '.$_SERVER['HTTP_HOST'].Global_WWW_Path.'. '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Уведомляем Вас о том, Ваш сайт "'.lecho ($item_db['FIELD_DOMAIN']).'" 
находится в нашем каталоге "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'".<br>
Спасибо.<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_edit_block ($item_db, $links_settings_db);	//добавляем блок к адресом для редактированием ссылки.

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_send_sorry ($item_db, $links_settings_db)
{
//функция отправляет уведомление о несоответствии сайта тематике
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Ваша ссылка не соответвует тематике каталога "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'" '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Уведомляем Вас о том, Ваш сайт "'.lecho ($item_db['FIELD_DOMAIN']).'" не соответствует тематике нашего сайта
и не может быть принят в <a href="http://'.$_SERVER['HTTP_HOST'].links_get_url ('', '', '').'" target="_blank">наш каталог ссылок</a>.<br>
Приносим свои извинения за потраченное время. Спасибо.<br><br>'."\r\n";

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);


$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";

SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_nah ($item_db, $links_settings_db)
{
//функция посылает партнёра далеко и надолго.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Небольшая просьба от модератора "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";
$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Модератор нашего каталога ссылок настоятельно рекомендует Вам посетить сайт <a href="http://www.nah.ru">http://www.nah.ru</a><br>
&nbsp;&nbsp;&nbsp;Большое спасибо за внимание и понимание.'."\r\n";

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);
}
//=====================================================================

function links_send_autoperenos ($item_db, $links_settings_db)
{
//функция посылает партнёра далеко и надолго.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая


$subj='Ваша ссылка будет удалена из каталога "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";

$text.='Здравствуйте, '.lecho ($item_db['FIELD_USER_NAME']).'.<br>
Уведомляем Вас о том, Ваш сайт "'.lecho ($item_db['FIELD_DOMAIN']).'" 
находится в <a href="http://'.$_SERVER['HTTP_HOST'].links_get_url ('', '', '').'" target="_blank">нашем каталоге</a>.<br>
Но в ближайшее время будет удалён из каталога.<br>
Спасибо.<br><br>'."\r\n";

$text.=links_generate_link_addr_block ($item_db, $links_settings_db);	//информация о адресе расположения партнёрской ссылки у нас

//вставляем в письмо блок полной инфы о ссылке.
$text.=links_generate_item_full_info ($item_db);

//если в ссылке неуказан адрес обратки, то предлагаем пользователю разметить одну из наших ссылок.
//в тело письма пользователю добавляем код наших ссылок для обмена.
if (!$item_db['FIELD_RET_LINK_ADDR']) $text.=links_generate_links_block ();

$text.=links_generate_edit_block ($item_db, $links_settings_db);	//добавляем блок к адресом для редактированием ссылки.

$text.=links_generate_moder_block ($item_db, $links_settings_db);	//добавляем в конец блок с контактной инфой.

$text.='</div>'."\r\n";
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);

}
//=====================================================================


function links_send_bb_admin_notice ($item_db, $links_settings_db)
{
//функция уведомляет модреатора ссылок о том, что одна из ссылок превысила штраф баллы.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Превышены штраф. балы в "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";

$text.='Категория: "'.$item_db['KNAME'].'"<br>'."\r\n";
$text.='Адрес сайта: '.lecho ($item_db['FIELD_DOMAIN']).'<br>'."\r\n";
$text.='Название сайта: '.lecho ($item_db['FIELD_NAME']).'<br>'."\r\n";
$text.='Имя пользователя: '.lecho ($item_db['FIELD_USER_NAME']).'<br>'."\r\n";
$text.='Почта пользователя: '.lecho ($item_db['FIELD_USER_MAIL']).'<br>'."\r\n";
if ($item_db['FIELD_RET_LINK_ADDR']) $text.='Адрес с обраткой: <a href="'.lecho($item_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'</a><br>'."\r\n";
$text.='HTML ссылки: '.lecho ($item_db['FIELD_TEXT_HTML']).'<br>'."\r\n";
$text.='</div>'."\r\n";


//если указано доп. почта модератора, то отправляем уведомление и ему.
if ($links_settings_db['NEW_MAIL_NOTIC']) SI_send_mail ($links_settings_db['NEW_MAIL_NOTIC'], $subj, $text);

$text.='</div>'."\r\n";
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);

}
//=====================================================================


function links_send_edit_admin_notice ($item_db, $links_settings_db)
{
//функция отправляет уведомление модеру каталога о том, что пользователь отредактировал свою ссылку.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Пользователь изменил ссылку "'.lecho ($item_db['FIELD_NAME']).'" в каталоге "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";

$text.='Категория: "'.$item_db['KNAME'].'"<br>'."\r\n";
$text.='Адрес сайта: '.lecho ($item_db['FIELD_DOMAIN']).'<br>'."\r\n";
$text.='Название сайта: '.lecho ($item_db['FIELD_NAME']).'<br>'."\r\n";
$text.='Имя пользователя: '.lecho ($item_db['FIELD_USER_NAME']).'<br>'."\r\n";
$text.='Почта пользователя: '.lecho ($item_db['FIELD_USER_MAIL']).'<br>'."\r\n";
if ($item_db['FIELD_RET_LINK_ADDR']) $text.='Адрес с обраткой: <a href="'.lecho($item_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'</a><br>'."\r\n";
$text.='HTML ссылки: '.lecho ($item_db['FIELD_TEXT_HTML']).'<br>'."\r\n";
$text.='</div>'."\r\n";



//если указано доп. почта модератора, то отправляем уведомление и ему.
if ($links_settings_db['NEW_MAIL_NOTIC']) SI_send_mail ($links_settings_db['NEW_MAIL_NOTIC'], $subj, $text);

$text.='</div>'."\r\n";

//для отправки писма пользователю немного переделываем заголовок письма.
$subj='Ваша ссылка изменена в каталоге "'.$_SERVER['HTTP_HOST'].Global_WWW_Path.'". '.date ('d-m-Y H:i', $system_db['THIS_TIME']);
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);

}
//=====================================================================


function links_send_nolink_admin_notice ($item_db, $links_settings_db)
{
//функция отправляет уведомление модеру каталога о том, авторобот не нашёл обратки.
//$item_db - данные по ссылке.
//$links_settings_db - настройки каталога

global $system_db;


if (!$item_db['FIELD_USER_MAIL']) return;		//почта пустая

$subj='Обратная ссылка не найдена на сайт "'.$_SERVER['HTTP_HOST'].'" с сайта "'.lecho ($item_db['FIELD_DOMAIN']).'" '.date ('d-m-Y H:i', $system_db['THIS_TIME']);

$text='<div style="font-family: Verdana; font-size:11px; padding:3px;">'."\r\n";

$text.='<div style="margin:10px 0px 10px 0px; padding:10px; border:1px solid #ff0000;" align="center"><b>Авторобот не нашёл обратную ссылку!</b></div>';

$text.='Категория: "'.$item_db['KNAME'].'"<br>'."\r\n";
$text.='Адрес сайта: '.lecho ($item_db['FIELD_DOMAIN']).'<br>'."\r\n";
$text.='Название сайта: '.lecho ($item_db['FIELD_NAME']).'<br>'."\r\n";
$text.='Имя пользователя: '.lecho ($item_db['FIELD_USER_NAME']).'<br>'."\r\n";
$text.='Почта пользователя: '.lecho ($item_db['FIELD_USER_MAIL']).'<br>'."\r\n";
if ($item_db['FIELD_RET_LINK_ADDR']) $text.='Адрес с обраткой: <a href="'.lecho($item_db['FIELD_RET_LINK_ADDR']).'" target="_blank">'.lecho ($item_db['FIELD_RET_LINK_ADDR']).'</a><br>'."\r\n";
$text.='HTML ссылки: '.lecho ($item_db['FIELD_TEXT_HTML']).'<br>'."\r\n";
$text.='</div>'."\r\n";


//если указано доп. почта модератора, то отправляем уведомление и ему.
if ($links_settings_db['NEW_MAIL_NOTIC']) SI_send_mail ($links_settings_db['NEW_MAIL_NOTIC'], $subj, $text);

$text.='</div>'."\r\n";
SI_send_mail ($item_db['FIELD_USER_MAIL'], $subj, $text);

}
//=====================================================================

?>