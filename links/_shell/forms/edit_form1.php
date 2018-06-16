<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//форма редактирования ссылки. шаг 1


$form_db=array();	//сюда грузим данные для шаблона.
$form_db['ACTION']=links_get_url ('edit', '', '');
$form_db['FORM_NAME']='edit_link';
$form_db['HIDDEN_VARS']='<input type="hidden" name="step" value="1">';		//дополнительные скрытые переменные в форме.
$form_db['ERRORS']='';
$form_db['FIELD_STRINGS']='';
$form_db['ERRORS']=si_generate_error_list ($err);	//генерим список ошибок из ошибок, собранных в $err

$tmp_str=$system_db['FIELD_ADMIN_MAIL'];
if ($links_settings_db['NEW_MAIL_NOTIC']) $tmp_str=$links_settings_db['NEW_MAIL_NOTIC'];
$form_db['MODER_TEXT']='Все Ваши предложения, пожелания и замечания по работе<br> каталога ссылок присылайте на email <a href="mailto:'.$tmp_str.'?subject=О каталоге ссылок">'.$tmp_str.'</a>.';


$tpl_db=array();	//загребаем шаблоны
$tpl_db['add_form']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_form.tpl'));	//получаем шаблон
$tpl_db['add_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_strings.tpl'));	//получаем шаблон
$tpl_db['add_big_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_big_strings.tpl'));	//получаем шаблон
$tpl_db['link_to_change']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/link_to_change.tpl'));	//получаем шаблон
$tpl_db['link_to_change_variant']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/link_to_change_variant.tpl'));	//получаем шаблон


//получаем инфу по ид ссылки (ид закинули в сессию на нулевом шаге редактирования)
$item_db=links_get_item_full_data (si_get_session ('edit_link'.Base_Prefix));

if ($links_settings_db['EDIT'])
{
//выводим форму, если есть разрешённые для редактирования поля.

//выводим поля. которые разрешено редактировать.
if ($links_settings_db['EDIT']['FIELD_DOMAIN']==1)
{
//---
//выводим форму.
$string_db=array();
$string_db['NAME']='Адрес сайта';
$string_db['STAR']='*';
$sel='http://'.$item_db['FIELD_DOMAIN'];
if ($_POST['DOMAIN']) $sel=$_POST['DOMAIN'];
$string_db['FIELD']=l_text ('name="DOMAIN" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}


if ($links_settings_db['EDIT']['FIELD_NAME']==1)
{
$string_db=array();
$string_db['NAME']='Название сайта';
$string_db['STAR']='*';
$sel=$item_db['FIELD_NAME'];
if ($_POST['NAME']) $sel=$_POST['NAME'];
$string_db['FIELD']=l_text ('name="NAME" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}


if ($links_settings_db['EDIT']['FIELD_LINK_CAT']==1)
{
//получаем все каталоги
$links_cat_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE FIELD_ENABLE='1' ORDER BY FIELD_SORT ASC");
$links_cat_db=si_generate_afa ($links_cat_db, 'id', 'FIELD_NAME');
$string_db=array();
$string_db['NAME']='Категория';
$string_db['STAR']='*';
$sel=$item_db['FIELD_LINK_CAT'];
if ($_POST['LINK_CAT']) $sel=$_POST['LINK_CAT'];
$string_db['FIELD']=l_select ('name="LINK_CAT" class="link_text_field"', $links_cat_db, $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}


if ($links_settings_db['EDIT']['FIELD_IMG_ADDR']==1)
{
$string_db=array();
$string_db['NAME']='Ваш баннер';
$string_db['STAR']='&nbsp;';
$sel=$item_db['FIELD_IMG_ADDR'];
if ($_POST['IMG_ADDR']) $sel=$_POST['IMG_ADDR'];
$string_db['FIELD']=l_text ('name="IMG_ADDR" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='Адрес вашего баннера размером 88х31. (.gif .jpg .png)<br>Например: http://mysite.ru/88x31.gif';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
}


if ($links_settings_db['EDIT']['FIELD_RET_LINK_ADDR']==1)
{

//поле для ввода обратки.. 
//---
$href_link_to_change='#variants';

$string_db=array();
$string_db['NAME']='Обратная ссылка';
$string_db['STAR']='&nbsp;';
if ($links_settings_db['VERIFY_RET_LINK']) $string_db['STAR']='*';

$sel=$item_db['FIELD_RET_LINK_ADDR'];
if ($_POST['RET_LINK_ADDR']) $sel=$_POST['RET_LINK_ADDR'];
$string_db['FIELD']=l_text ('name="RET_LINK_ADDR" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='<div>Полный адрес страницы на Вашем сайте, где была размещена ссылка на наш сайт.</div>';
if ($links_settings_db['VERIFY_RET_LINK']) $string_db['DESC'].='<span class="link_form_red_text">ВНИМАНИЕ!</span> Прежде чем нажимать кнопку Вы должны установить на своем сайте <a href="'.$href_link_to_change.'">любой из наших HTML-кодов</a>. 
Скрипт проверит наличие установленного кода и только после этого изменения будут приняты.';
	else $string_db['DESC'].='Вы можете установить к себе на сайт <a href="'.$href_link_to_change.'">одну из наших ссылок.</a>';

$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}


if ($links_settings_db['EDIT']['FIELD_USER_NAME']==1)
{

$string_db=array();
$string_db['NAME']='Ваше имя';
$string_db['STAR']='*';
$sel=$item_db['FIELD_USER_NAME'];
if ($_POST['USER_NAME']) $sel=$_POST['USER_NAME'];
$string_db['FIELD']=l_text ('name="USER_NAME" class="link_text_field" size="40"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}


if ($links_settings_db['EDIT']['FIELD_USER_MAIL']==1)
{
$string_db=array();
$string_db['NAME']='Ваш e-mail';
$string_db['STAR']='*';
$sel=$item_db['FIELD_USER_MAIL'];
if ($_POST['USER_MAIL']) $sel=$_POST['USER_MAIL'];
$string_db['FIELD']=l_text ('name="USER_MAIL" class="link_text_field" size="40"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}

if ($links_settings_db['EDIT']['FIELD_TEXT_HTML']==1)
{
$string_db=array();
$string_db['NAME']='HTML Вашей ссылки';
$string_db['STAR']='*';
$sel=$item_db['FIELD_TEXT_HTML'];
if ($_POST['TEXT_HTML']) $sel=$_POST['TEXT_HTML'];
$string_db['FIELD']=l_textarea ('name="TEXT_HTML" class="link_text_field" style="width:99%" rows="8"', $sel);
$string_db['DESC']='В коде разрешены ТОЛЬКО тэги "<span class="link_form_red_text">'.lecho ($links_settings_db['ALLOW_TAGS']).'</span>" 
Любые другие тэги, javaScript или стили будут удалены.<br>
Все ссылки в Вашем HTML коде <span class="link_form_red_text">ДОЛЖНЫ вести ТОЛЬКО</span> на одну из страниц регистрируемого сайта. 
(адрес введённый в поле "Адрес сайта" или одна из страниц на указанном сайте). <br>';
$string_db['DESC'].='
Максимум ссылок: <b>'.intval($links_settings_db['MAX_LINK_IN_HTML']).'</b>
<br>
Максимальная длина Вашего HTML: <b>'.intval($links_settings_db['MAX_HTML_LENGHT']).'</b> символов. ';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}

$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']=l_buttion ('name="next" class="link_grey_button"', 'Принять изменения');
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);




$VARIANTS='';
//получаем все варианты ссылок для обмена.
$links_variants_db=SI_sql_query("select * from ".Base_Prefix."links_variants WHERE FIELD_ENABLE='1' ORDER BY FIELD_SORT ASC");

for ($i=0; $i<count($links_variants_db); $i++)
{
//подправляем данные в коде для обмена.
$links_variants_db[$i]['NUMBER']=$i+1;
$links_variants_db[$i]['TEXTAREA_CODE']=lecho($links_variants_db[$i]['FIELD_CODE']);

$VARIANTS.=si_field_replace ($links_variants_db[$i], $tpl_db['link_to_change_variant']);
}

$string_db['FIELD']=si_field_replace (array('VARIANTS'=>$VARIANTS), $tpl_db['link_to_change']);

$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);
//---

//---
}
else
{
//если нет полей для редактирования, то выводим сообщение об этом.
$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']='<div style="padding:30px;" align="center">Нет доступных параметров для редактирования</div>';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);

}

//добавляем что получилось на страницу.
$page_stream_db['CONTENT'].=si_field_replace ($form_db, $tpl_db['add_form']);

?>