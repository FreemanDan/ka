<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//форма добавления ссылки. шаг 0


$form_db=array();	//сюда грузим данные для шаблона.
$form_db['ACTION']=links_get_url ('add', '', '');
$form_db['FORM_NAME']='add_link';
$form_db['HIDDEN_VARS']='<input type="hidden" name="step" value="0">';		//дополнительные скрытые переменные в форме.
$form_db['ERRORS']='';
$form_db['FIELD_STRINGS']='';
$form_db['ERRORS']=si_generate_error_list ($err);	//генерим список ошибок из ошибок, собранных в $err


$tpl_db=array();	//загребаем шаблоны
$tpl_db['add_form']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_form.tpl'));	//получаем шаблон
$tpl_db['add_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_strings.tpl'));	//получаем шаблон
$tpl_db['add_big_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_big_strings.tpl'));	//получаем шаблон
$tpl_db['link_to_change']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/link_to_change.tpl'));	//получаем шаблон
$tpl_db['link_to_change_variant']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/link_to_change_variant.tpl'));	//получаем шаблон


//---
//выводим форму.
$string_db=array();
$string_db['NAME']='Адрес сайта';
$string_db['STAR']='*';
$sel='http://';
if ($_POST['DOMAIN']) $sel=$_POST['DOMAIN'];
$string_db['FIELD']=l_text ('name="DOMAIN" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---


$string_db=array();
$string_db['NAME']='Название сайта';
$string_db['STAR']='*';
$sel='';
if ($_POST['NAME']) $sel=$_POST['NAME'];
$string_db['FIELD']=l_text ('name="NAME" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

//получаем все каталоги
$links_cat_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE FIELD_ENABLE='1' ORDER BY FIELD_SORT ASC");
$links_cat_db=si_generate_afa ($links_cat_db, 'id', 'FIELD_NAME');
$string_db=array();
$string_db['NAME']='Категория';
$string_db['STAR']='*';
$sel='';
if ($_POST['LINK_CAT']) $sel=$_POST['LINK_CAT'];
$string_db['FIELD']=l_select ('name="LINK_CAT" class="link_text_field"', $links_cat_db, $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='Ваш баннер';
$string_db['STAR']='&nbsp;';
$sel='';
if ($_POST['IMG_ADDR']) $sel=$_POST['IMG_ADDR'];
$string_db['FIELD']=l_text ('name="IMG_ADDR" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='Адрес вашего баннера размером 88х31. (.gif .jpg .png)<br>Например: http://mysite.ru/88x31.gif';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);

if ($links_settings_db['VERIFY_RET_LINK'] || !$links_settings_db['HIDDEN_RET_LINK_FIELD'])
{
//поле для ввода обратки.. Если оно обязательно или не скрытое, то выводим.
//---
$href_link_to_change='#variants';
$string_db=array();
$string_db['NAME']='Обратная ссылка';
$string_db['STAR']='&nbsp;';
if ($links_settings_db['VERIFY_RET_LINK']) $string_db['STAR']='*';
$sel='';
if (!eto_ne_pusto($sel) && !$_POST) $sel='http://';
if ($_POST['RET_LINK_ADDR']) $sel=$_POST['RET_LINK_ADDR'];
$string_db['FIELD']=l_text ('name="RET_LINK_ADDR" class="link_text_field" style="width:99%;"', $sel);
$string_db['DESC']='<div>Полный адрес страницы на Вашем сайте, где была размещена ссылка на наш сайт.</div>';
if ($links_settings_db['VERIFY_RET_LINK']) $string_db['DESC'].='<span class="link_form_red_text">ВНИМАНИЕ!</span> Прежде чем нажимать кнопку Вы должны установить на своем сайте <a href="'.$href_link_to_change.'">любой из наших HTML-кодов</a>. 
Скрипт проверит наличие установленного кода и только после этого добавит Вашу ссылку.';
	else $string_db['DESC'].='Вы можете установить к себе на сайт <a href="'.$href_link_to_change.'">одну из наших ссылок.</a>';

$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---
}


$string_db=array();
$string_db['NAME']='Ваше имя';
$string_db['STAR']='*';
$sel='';
if ($_POST['USER_NAME']) $sel=$_POST['USER_NAME'];
$string_db['FIELD']=l_text ('name="USER_NAME" class="link_text_field" size="40"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='Ваш e-mail';
$string_db['STAR']='*';
$sel='';
if ($_POST['USER_MAIL']) $sel=$_POST['USER_MAIL'];
$string_db['FIELD']=l_text ('name="USER_MAIL" class="link_text_field" size="40"', $sel);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='HTML Вашей ссылки';
$string_db['STAR']='*';
$sel='';
if ($_POST['TEXT_HTML']) $sel=$_POST['TEXT_HTML'];
$string_db['FIELD']=l_textarea ('name="TEXT_HTML" class="link_text_field" style="width:99%" rows="8"', $sel);
$string_db['DESC']='
<span class="link_form_red_text">Прочтите это внимательно! В каталог добавлять сайт бесполезно</span>, если он не соответствует Керам-Арт по теме. Основные наши темы - изобразительное искусство и дизайн, художественная керамика, оборудование и материалы для керамики.<br>
В коде разрешены ТОЛЬКО тэги "<span class="link_form_red_text">'.lecho ($links_settings_db['ALLOW_TAGS']).'</span>" 
Любые другие тэги, javaScript или стили будут удалены.<br>
Все ссылки в Вашем HTML коде <span class="link_form_red_text">ДОЛЖНЫ вести ТОЛЬКО</span> на одну из страниц регистрируемого сайта. 
(адрес введённый в поле "Адрес сайта" или одна из страниц на указанном сайте). <br>';
$string_db['DESC'].='
Максимум ссылок: <b>'.intval($links_settings_db['MAX_LINK_IN_HTML']).'</b>
<br>
Максимальная длина Вашего HTML: <b>'.intval($links_settings_db['MAX_HTML_LENGHT']).'</b> символов. ';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']=l_buttion ('name="next" class="link_grey_button"', 'Добавить ссылку');
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);
//---

//выводим сдесь наши коды для обмена.
$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']='';

$VARIANTS='';

// DanFreeman  рандомизатор
//DanFreeman  немного изменил код, теперь выводятся случайные две записи
//получаем все варианты ссылок для обмена.
$links_variants_db=SI_sql_query("select * from ".Base_Prefix."links_variants WHERE FIELD_ENABLE='1' ORDER BY RAND() LIMIT 2");

for ($i=0; $i<count($links_variants_db); $i++)
{
//подправляем данные в коде для обмена.
$links_variants_db[$i]['NUMBER']=$i+1;
$links_variants_db[$i]['TEXTAREA_CODE']=lecho($links_variants_db[$i]['FIELD_CODE']);
$links_variants_db[$i]['TEXTAREA_SH_NAME']=lecho($links_variants_db[$i]['FIELD_SHORT_NAME']);
$links_variants_db[$i]['TEXTAREA_BANNER']= lecho($links_variants_db[$i]['FIELD_BANNER']);
$VARIANTS.=si_field_replace ($links_variants_db[$i], $tpl_db['link_to_change_variant']);
}

$string_db['FIELD']=si_field_replace (array('VARIANTS'=>$VARIANTS), $tpl_db['link_to_change']);

$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);
//---

$page_stream_db['CONTENT'].=si_field_replace ($form_db, $tpl_db['add_form']);
?>