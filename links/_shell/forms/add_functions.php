<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//комплект функций для проверки добавляемых ссылок

//=================================================

function verify_add_link_data ($post_item_data, $links_settings_db)
{
//функция проверяет данные на добавление из POST
//возвращает изменнные и проверенные данные
//$post_item_data - данные из формы. 
//возвращает проверенный и исправленный массив $post_item_data
//выполняем блок первых проверок.
$post_item_data=link_verify_LINK_CAT 	($post_item_data, $links_settings_db);
$post_item_data=link_verify_DOMAIN 		($post_item_data, $links_settings_db);
$post_item_data=link_verify_NAME 		($post_item_data, $links_settings_db);
$post_item_data=link_verify_IMG_ADDR 	($post_item_data, $links_settings_db);
$post_item_data=link_verify_TEXT_HTML 	($post_item_data, $links_settings_db);
$post_item_data=link_verify_RET_LINK_ADDR 	($post_item_data, $links_settings_db);
$post_item_data=link_verify_USER_NAME 	($post_item_data, $links_settings_db);
$post_item_data=link_verify_USER_MAIL 	($post_item_data, $links_settings_db);

//если первые проверки были нормальные. то выполняем блок вторых проверок.
if (!$post_item_data['err']) $post_item_data=link_verify_data_adv_2 ($post_item_data, $links_settings_db);

return $post_item_data;

}
//=================================================

function add_link_data ($post_item_data, $links_settings_db)
{
//функция добавляет инфу о ссылке в базу.
//подразумевается, что вся информация проверена в $_POST и является удовлетворяющей параметрам добавления.
//$post_item_data - данные из формы. 
//возвращает $post_item_data
//возвращает в $post_item_data['item_db'] полную инфу о добавленной ссылке
global $system_db;

//получаем следующий индекс сортировки согласно натсройкам
if ($links_settings_db['INSERT_IN']==1)
{
$tmp_db=SI_sql_query("select MIN(FIELD_SORT) AS MS from ".Base_Prefix."links_items");
$next_srt=$tmp_db[0]['MS']-1;
}
else
{
$tmp_db=SI_sql_query("select MAX(FIELD_SORT) AS MS from ".Base_Prefix."links_items");
$next_srt=$tmp_db[0]['MS']+1;
}

SI_sql_query("insert into ".Base_Prefix."links_items (FIELD_SORT) values('$next_srt')");
$id_ins=mysql_insert_id(); //id добавленной 
$CLEAR_DOMAIN=links_get_clear_domain ($post_item_data['DOMAIN']);

SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_LINK_CAT='".$post_item_data['LINK_CAT']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_CREATE_DATE='".$system_db['THIS_TIME']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_DOMAIN='$CLEAR_DOMAIN' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_NAME='".$post_item_data['NAME']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_IMG_ADDR='".$post_item_data['IMG_ADDR']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_TEXT_HTML='".$post_item_data['TEXT_HTML']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_RET_LINK_ADDR='".$post_item_data['RET_LINK_ADDR']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_USER_NAME='".$post_item_data['USER_NAME']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_USER_MAIL='".$post_item_data['USER_MAIL']."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_ENABLE='".intval ($links_settings_db['FAST_ENABLE'])."' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_IS_NEW='1' WHERE id='$id_ins'");
SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_KEY_FOR_EDIT='".links_generate_edit_key ()."' WHERE id='$id_ins'");

//после добавления производим проверку на возможность вставки новой ссылки вместо ссылки с флагом "автоперенос"
//если такая возможность есть, то выполняем вставку и автоперенос
link_perenos ($id_ins, $links_settings_db);

//ок. сцылка добавлена. Формируем письмо пользователю о добавленной ссылке.
$post_item_data['item_db']=links_get_item_full_data ($id_ins);
links_send_create ($post_item_data['item_db'], $links_settings_db);

return $post_item_data;

}

//=================================================


function save_edit_link_data ($post_item_data, $links_settings_db)
{
//функция сохраняет инфу о ссылке в базу.
//подразумевается, что вся информация проверена в $_POST и является удовлетворяющей параметрам добавления.
//$post_item_data - данные из формы. 

$CLEAR_DOMAIN=links_get_clear_domain ($post_item_data['DOMAIN']);

$use_id=intval($post_item_data['item_db']['id']);
if ($links_settings_db['EDIT']['FIELD_LINK_CAT']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_LINK_CAT='".$post_item_data['LINK_CAT']."' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_DOMAIN']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_DOMAIN='$CLEAR_DOMAIN' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_NAME']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_NAME='".$post_item_data['NAME']."' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_IMG_ADDR']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_IMG_ADDR='".$post_item_data['IMG_ADDR']."' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_TEXT_HTML']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_TEXT_HTML='".$post_item_data['TEXT_HTML']."' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_RET_LINK_ADDR']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_RET_LINK_ADDR='".$post_item_data['RET_LINK_ADDR']."' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_USER_NAME']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_USER_NAME='".$post_item_data['USER_NAME']."' WHERE id='$use_id'");
if ($links_settings_db['EDIT']['FIELD_USER_MAIL']==1) SI_sql_query("UPDATE ".Base_Prefix."links_items SET FIELD_USER_MAIL='".$post_item_data['USER_MAIL']."' WHERE id='$use_id'");

return $post_item_data;
}

//=================================================

function link_get_post_data ()
{
$post_item_data=array();
$post_item_data['DOMAIN']=si_filters ($_POST['DOMAIN'], '5');
$post_item_data['NAME']=si_filters ($_POST['NAME'], '5');
$post_item_data['LINK_CAT']=intval ($_POST['LINK_CAT']);
$post_item_data['IMG_ADDR']=trim ($_POST['IMG_ADDR']);
$post_item_data['RET_LINK_ADDR']=trim ($_POST['RET_LINK_ADDR']);
$post_item_data['USER_NAME']=si_filters ($_POST['USER_NAME'], '4,5');
$post_item_data['USER_MAIL']=si_filters ($_POST['USER_MAIL'], '2,4');
$post_item_data['TEXT_HTML']=trim ($_POST['TEXT_HTML']);
$post_item_data['err']='';		//сюда функи валидаторы складывают ошибки.
return $post_item_data;
}
//=================================================


function verify_edit_link_data ($post_item_data, $links_settings_db)
{
//функция проверяет данные при редактировании из POST
//$post_item_data - данные из формы. Также в поле $post_item_data['item_db'] - база по редактируемой ссылке.
//возвращает проверенный и исправленный массив $post_item_data

//выполняем блок первых проверок.
//также смотрим, если редактирование параметра запрещенно, то в данные из формы подставляем слэшованные данные из базы..
if ($links_settings_db['EDIT']['FIELD_LINK_CAT']==1) $post_item_data=link_verify_LINK_CAT 	($post_item_data, $links_settings_db); 		else $post_item_data['LINK_CAT']=adds ($post_item_data['item_db']['FIELD_LINK_CAT']);
if ($links_settings_db['EDIT']['FIELD_DOMAIN']==1) $post_item_data=link_verify_DOMAIN 		($post_item_data, $links_settings_db); 		else $post_item_data['DOMAIN']=adds ($post_item_data['item_db']['FIELD_DOMAIN']);
if ($links_settings_db['EDIT']['FIELD_NAME']==1) $post_item_data=link_verify_NAME 		($post_item_data, $links_settings_db); 		else $post_item_data['NAME']=adds ($post_item_data['item_db']['FIELD_NAME']);
if ($links_settings_db['EDIT']['FIELD_IMG_ADDR']==1) $post_item_data=link_verify_IMG_ADDR 	($post_item_data, $links_settings_db); 		else $post_item_data['IMG_ADDR']=adds ($post_item_data['item_db']['FIELD_IMG_ADDR']);
if ($links_settings_db['EDIT']['FIELD_TEXT_HTML']==1) $post_item_data=link_verify_TEXT_HTML 	($post_item_data, $links_settings_db); 		else $post_item_data['TEXT_HTML']=adds ($post_item_data['item_db']['FIELD_TEXT_HTML']);
if ($links_settings_db['EDIT']['FIELD_RET_LINK_ADDR']==1) $post_item_data=link_verify_RET_LINK_ADDR 	($post_item_data, $links_settings_db); 	else $post_item_data['LINK_ADDR']=adds ($post_item_data['item_db']['FIELD_RET_LINK_ADDR']);
if ($links_settings_db['EDIT']['FIELD_USER_NAME']==1) $post_item_data=link_verify_USER_NAME 	($post_item_data, $links_settings_db); 		else $post_item_data['USER_NAME']=adds ($post_item_data['item_db']['FIELD_USER_NAME']);
if ($links_settings_db['EDIT']['FIELD_USER_MAIL']==1) $post_item_data=link_verify_USER_MAIL 	($post_item_data, $links_settings_db); 		else $post_item_data['USER_MAIL']=adds ($post_item_data['item_db']['FIELD_USER_MAIL']);

//если первые проверки были нормальные. то выполняем блок вторых проверок.
if (!$post_item_data['err']) $post_item_data=link_verify_data_adv_2 ($post_item_data, $links_settings_db);

return $post_item_data;

}
//=================================================

function link_verify_LINK_CAT ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы

$tmp_db=SI_sql_query("select id from ".Base_Prefix."links_category WHERE FIELD_ENABLE='1' AND id='".intval($post_item_data['LINK_CAT'])."'");
if (!$tmp_db)
	{
	 $post_item_data['err'].='Неправильно указана категория!<br>';
	 return $post_item_data; 
	}

return $post_item_data;
}
//=================================================

function link_verify_DOMAIN ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы

if (!eto_ne_pusto ($post_item_data['DOMAIN'])) 
	{
	 $post_item_data['err'].='Поле "Адрес сайта" обязательно для заполнения!<br>';
	 return $post_item_data; 
	}

//обрабатываем адрес сайта. Смотрим, чтобы они был НОРМАЛЬНЫЙ
$pos=strpos ($post_item_data['DOMAIN'], 'http://');
if (!eto_ne_pusto($pos) || $pos>0)
	{
	 $post_item_data['err'].='Неправильно указан адрес сайта!<br>';
	 return $post_item_data; 
	}
	
if (si_filters($post_item_data['DOMAIN'],'1')!=strip_tags(si_filters($post_item_data['DOMAIN'],'1')))
	{
	 $post_item_data['err'].='Адрес сайта содержит запрещённые символы!<br>';
	 return $post_item_data; 
	}


//проверяем, не содержитли домен запрещённых строк.
$deny_str_db=array();
$tmp_err='';	//временая ошибка.
if (eto_ne_pusto($links_settings_db['DENY_DOMEN_STR'])) $deny_str_db=explode (' ', trim($links_settings_db['DENY_DOMEN_STR']));
for ($i=0; $i<count($deny_str_db); $i++)
 if (trim ($deny_str_db[$i])) 
  if (eto_ne_pusto(strpos($post_item_data['DOMAIN'], trim ($deny_str_db[$i])))) $tmp_err='Добавление Вашего сайта запрещенно в наш каталог!<br>';

$post_item_data['err'].=$tmp_err;
return $post_item_data; 

}
//=================================================


function link_verify_NAME ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы

if (!eto_ne_pusto ($post_item_data['NAME'])) 
	{
	 $post_item_data['err'].='Поле "Название сайта" обязательно для заполнения!<br>';
	 return $post_item_data; 
	}

if (strlen ($post_item_data['NAME'])>255) 
	{
	 $post_item_data['err'].='Поле "Название сайта" превышает 255 символов!<br>';
	 return $post_item_data; 
	}

//проверяем имя.
if (si_filters($post_item_data['NAME'],'1')!=strip_tags(si_filters($post_item_data['NAME'],'1'))) 
	{
	 $post_item_data['err'].='Назание сайта содержит запрещённые символы!<br>';
	 return $post_item_data; 
	}
	
return $post_item_data;
}
//=================================================


function link_verify_IMG_ADDR ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы
global $allow_img_ext;	//$allow_img_ext - массив с разрешёнными расширениями загружаемых картинок.

if (!eto_ne_pusto($post_item_data['IMG_ADDR'])) return $post_item_data; 	//нет баннера

//проверяем правильность указанного баннера.
$pos=strpos ($post_item_data['IMG_ADDR'], 'http://');
if (!eto_ne_pusto($pos) || $pos>0)
	{
	 $post_item_data['err'].='Неправильно указан адрес баннера 88х31!<br>';
	 return $post_item_data; 
	}
	
if (si_filters($post_item_data['IMG_ADDR'], '1')!=strip_tags(si_filters($post_item_data['IMG_ADDR'], '1')))
	{
	 $post_item_data['err'].='Адрес баннера 88х31 содержит запрещённые символы!<br>';
	 return $post_item_data; 
	}

	

//проверяем разширения баннера.
$tmp_db=@parse_url($post_item_data['IMG_ADDR']);
$tmp_db=@pathinfo($tmp_db['path']);

//проверяем, сколько точек в названии баннера.
//учитывая глюки в разных брозверях и операционакх, ну нах всякие замуты с кучей точек.
if (substr_count($tmp_db['basename'], '.')>1) 
	{
	//неправильное расширение файла картинки.
	 $post_item_data['err'].='Неправильное имя файла или расширение. Слишком много точек в имени файла!<br>';
	 return $post_item_data; 	
	}

if (!in_array (strtolower ($tmp_db['extension']), $allow_img_ext))
	{
	//неправильное расширение файла картинки.
	 $post_item_data['err'].='Неправильное расширение файла баннера. Разрешены ТОЛЬКО '.implode(', ', $allow_img_ext).'!<br>';
	 return $post_item_data; 	
	}


return $post_item_data;
}
//=================================================


function link_verify_TEXT_HTML ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы

if (!eto_ne_pusto(si_filters($post_item_data['TEXT_HTML'], '1'))) $post_item_data['err'].='Поле "HTML Вашей ссылки" пустое!<br>';

if (strlen (si_filters($post_item_data['TEXT_HTML'], '1'))>$links_settings_db['MAX_HTML_LENGHT']) 	$post_item_data['err'].='Поле "HTML Вашей ссылки" превышает '.$links_settings_db['MAX_HTML_LENGHT'].' символов!<br>';

//проверяем введённые тэги
if (si_filters($post_item_data['TEXT_HTML'], '1')!=strip_tags(si_filters($post_item_data['TEXT_HTML'], '1'), $links_settings_db['ALLOW_TAGS'])) $post_item_data['err'].='Ваш HTML код содержит запрещённые тэги. Для добавления в каталог разрешены только тэги "'.lecho ($links_settings_db['ALLOW_TAGS']).'"';

//проверяем правильность ссылок в коде.
if (!$err) if (!links_verify_valid_href_code (si_filters ($post_item_data['TEXT_HTML'], '1'), $post_item_data['DOMAIN'])) $post_item_data['err'].='Ссылки в HTML должны вести на регистрируемый адрес!<br>';


//проверяем, количество ссылок в хтмл не превышаетли максимально разрешёное количество.
$html_links_db=links_get_all_href_from_html (si_filters ($post_item_data['TEXT_HTML'], '1'));
if (count($html_links_db)>$links_settings_db['MAX_LINK_IN_HTML']) $post_item_data['err'].='Количество ссылок в Вашем HTML превышает '.$links_settings_db['MAX_LINK_IN_HTML'].' шт.';

//проверяем наличие в ХТМЛ запрещённых вставок.
$tmp_err=0;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('javascript'))))  	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onBlur'))))  		$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onMouseOver'))))  	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onChange'))))  	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onSelect'))))  	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onClick'))))  	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onSubmit')))) 	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onFocus'))))  	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onUnload')))) 	$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('onLoad'))))  		$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('style='))))  		$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('class='))))  		$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('id='))))  		$tmp_err=1;
if (eto_ne_pusto (strpos (strtolower ($post_item_data['TEXT_HTML']), strtolower ('name='))))  		$tmp_err=1;

if ($tmp_err==1) $post_item_data['err'].='HTML Вашей ссылки имеет запрещённые тэги или javascript!<br>';

return $post_item_data;
}
//=================================================


function link_verify_RET_LINK_ADDR ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы

//если поле содержит только http://, то считаем, что пустое.
if (strtolower (trim ($post_item_data['RET_LINK_ADDR']))=='http://') $post_item_data['RET_LINK_ADDR']='';


if ($links_settings_db['VERIFY_RET_LINK'] && !eto_ne_pusto ($post_item_data['RET_LINK_ADDR']))
	{
	 $post_item_data['err'].='Поле "Обратная ссылка" обязательно для заполнения!<br>';
	 return $post_item_data; 
	}

//проверяем адрес сайта и адрес обратки если ввeдён адрес обратки.
if (eto_ne_pusto ($post_item_data['RET_LINK_ADDR']) || $links_settings_db['VERIFY_RET_LINK'])
if (links_get_clear_domain ($post_item_data['DOMAIN'])!=links_get_clear_domain ($post_item_data['RET_LINK_ADDR']))
	{
	 $post_item_data['err'].='Обратная ссылка должна находиться на регистрируемом сайте!<br>';
	 return $post_item_data; 
	}

return $post_item_data; 
}
//=================================================


function link_verify_USER_NAME ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы


if (!eto_ne_pusto ($post_item_data['USER_NAME']))
	{
	 $post_item_data['err'].='Поле "Ваше имя" обязательно для заполнения!<br>';
	 return $post_item_data; 
	}

return $post_item_data; 
}
//=================================================


function link_verify_USER_MAIL ($post_item_data, $links_settings_db)
{
//функция проверяет правильность значения введённого в поле 
//$post_item_data - предварительно обработанные данные из формы

if (!eto_ne_pusto ($post_item_data['USER_MAIL'])) 
	{ 
	 $post_item_data['err'].='Поле "Ваш e-mail" обязательно для заполнения!<br>'; 
	 return $post_item_data; 
	}
if (!si_verify_valid_email($post_item_data['USER_MAIL'])) 	
	{
	 $post_item_data['err'].='Неправильный e-mail!<br>'; 
	 return $post_item_data; 
	}
	
return $post_item_data; 
}
//=================================================

function link_verify_data_adv_2 ($post_item_data, $links_settings_db)
{
//блок вторых проверок для данных $post_item_data, которые прошли первые проверки.
//в качестве дополнительного параметра в $post_item_data['item_db'] инфа по ссылке в случае её редактирования.

//проверяем.. нетли у нас в базе такого сайта.
if (links_verify_unic_domain ($post_item_data['DOMAIN'], intval($post_item_data['item_db']['id']))) $post_item_data['err'].='Такой сайт уже есть в базе!<br>';


//проверяем ТИЦ сайта.
if (intval ($links_settings_db['MIN_TIC'])>0)
{
$tic=intval (links_getTIC($post_item_data['DOMAIN']));
if ($tic<intval ($links_settings_db['MIN_TIC'])) $post_item_data['err'].='ТИЦ Вашего сайта определён как '.$tic.', а по правилам нашего каталога принимаются только сайты ТИЦ которых не менее '.$links_settings_db['MIN_TIC'].'!<br>';
}

//проверяем PR сайта.
if (intval ($links_settings_db['MIN_PR'])>0)
{
//выбираем ьольший ПР из страниц с www и без
$tmp_domain=links_get_clear_domain ($post_item_data['DOMAIN']);
$pr_1=links_PR_getPR($tmp_domain);

$tmp_domain=str_replace ('www.', '', strtolower ($tmp_domain));	//домен без www.
$pr_2=links_PR_getPR($tmp_domain);

$pr=$pr_1;
if ($pr_2>$pr_1) $pr=$pr_2;

if ($pr<intval ($links_settings_db['MIN_PR'])) $post_item_data['err'].='PR Вашего сайта определён как '.$pr.', а по правилам нашего каталога принимаются только сайты PR которых не менее '.$links_settings_db['MIN_PR'].'!<br>';
}



//проверяем, проиндексирован ли сайт яндексом.
if ($links_settings_db['VERIFY_YA_INDEX']) $post_item_data['err'].=links_verify_yandex_index ($post_item_data['DOMAIN']);

//проверяем.. является ли уазанный сайт основным.
//функция проверки зеркал возвратит сообщение о зеркалах.
//также функция проверит наличие сайта в yandex.ru
//или возвратит пустую строку, если всё ок.
if ($links_settings_db['VERIFY_YA_MIRROR']) $post_item_data['err'].=links_verify_mirrors_domain ($post_item_data['DOMAIN']);



//если в настройках выставленно "проверять обратку", то пытаемся найти по указанной странице адрес на этот сайт.
if ($links_settings_db['VERIFY_RET_LINK'])
{

//проверяем robots.txt. Закрыт ли там адрес с обраткой?
//в этот уголок кода логика может заглянуть только если выставлен флаг "проверять обратку" и поле заполнено.
if (!links_in_open_robots ($post_item_data['RET_LINK_ADDR'])) $post_item_data['err'].='Обратная ссылка закрыта в robots.txt!<br>';

$link_list_db=get_all_my_banner_links ();		//выбираем все ссылки из включенных баннеров для обмена.

$_GET['href_db']='';	//КАСТЫЛЬ, БЛЯ!!
//проверяем. установленнали одна из ссылок $link_list_db на указанной странице $RET_LINK_ADDR
//в качество дополнительной функции функция заполняет массив $_GET['href_db'] (костыль, бля.. но помоему лучше такой костыль, чем дважды читать страницу с обраткой... и так уже перегружено всё всякими чтениями.)
if (!link_verify_set_link ($link_list_db, $post_item_data['RET_LINK_ADDR'])) $post_item_data['err'].='Обратная ссылка на Вашем сайте обязательна!<br>';

//раскладываем массив ссылок на данные domain и total
//для проверки сколько всего внешних ссылок и на сколько доменов.
$mod_href_fb=links_mod_href_db ($_GET['href_db']);


if (intval ($links_settings_db['MAX_OUT_LINKS_TOTAL'])>0 && $mod_href_fb['total']>intval ($links_settings_db['MAX_OUT_LINKS_TOTAL'])) $post_item_data['err'].='Количество исходящих ссылок должно быть не более '.intval ($links_settings_db['MAX_OUT_LINKS_TOTAL']).', а на Вашей странице '.$mod_href_fb['total'].' шт.!<br>';
if (intval ($links_settings_db['MAX_OUT_LINKS'])>0 && $mod_href_fb['domain']>intval ($links_settings_db['MAX_OUT_LINKS'])) $post_item_data['err'].='Страница с нашей ссылкой должна ссылаться не более чем на '.intval ($links_settings_db['MAX_OUT_LINKS']).' сайтов, а сейчас она ссылается на '.$mod_href_fb['domain'].' сайтов!<br>';
}

return $post_item_data;
}
//=================================================

?>