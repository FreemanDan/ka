<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//форма добавления ссылки. шаг 1

include_once (Root_Dir.'_shell/functions/captcha_functions.php');		//подгружаем функи для работы с капчей

$form_db=array();	//сюда грузим данные для шаблона.
$form_db['ACTION']=links_get_url ('add', '', '');
$form_db['FORM_NAME']='add_link';
$form_db['HIDDEN_VARS']='<input type="hidden" name="step" value="1">';		//дополнительные скрытые переменные в форме.
$form_db['ERRORS']='';
$form_db['FIELD_STRINGS']='';
$form_db['ERRORS']=si_generate_error_list ($err);	//генерим список ошибок из ошибок, собранных в $err


$tpl_db=array();	//загребаем шаблоны
$tpl_db['add_form']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_form.tpl'));	//получаем шаблон
$tpl_db['add_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_strings.tpl'));	//получаем шаблон
$tpl_db['add_big_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_big_strings.tpl'));	//получаем шаблон


//---
//выводим форму.

$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']='<b>Проверьте правильность заполненных данных</b>';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);

$string_db=array();
$string_db['NAME']='Адрес сайта';
$string_db['STAR']='*';
$sel=srp_lecho ($_POST['DOMAIN']);
$string_db['FIELD']=$sel.'<input type="hidden" name="DOMAIN" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---


$string_db=array();
$string_db['NAME']='Название сайта';
$string_db['STAR']='*';
$sel=srp_lecho ($_POST['NAME']);
$string_db['FIELD']=$sel.'<input type="hidden" name="NAME" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---


$string_db=array();
$string_db['NAME']='Категория';
$string_db['STAR']='*';
$sel=intval ($_POST['LINK_CAT']);
//получаем выбранный каталог
$links_cat_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category WHERE id='$sel'");

$string_db['FIELD']=$links_cat_db[0]['FIELD_NAME'].'<input type="hidden" name="LINK_CAT" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='Ваш баннер';
$string_db['STAR']='&nbsp;';
$tmp_str='';
$sel=srp_lecho ($_POST['IMG_ADDR']);
if ($sel) $tmp_str='<img src="'.si_filters ($_POST['IMG_ADDR'], '5, 1').'" width="88" height="31">';
$string_db['FIELD']=$tmp_str.'<input type="hidden" name="IMG_ADDR" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);

//---

if ($links_settings_db['VERIFY_RET_LINK'] || !$links_settings_db['HIDDEN_RET_LINK_FIELD'])
{
$string_db=array();
$string_db['NAME']='Обратная ссылка';
$string_db['STAR']='&nbsp;';
if ($links_settings_db['VERIFY_RET_LINK']) $string_db['STAR']='*';
$sel=srp_lecho ($_POST['RET_LINK_ADDR']);
if (trim (strtolower ($sel))=='http://') $sel='';
$string_db['FIELD']=$sel.'<input type="hidden" name="RET_LINK_ADDR" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
}
//---

$string_db=array();
$string_db['NAME']='Ваше имя';
$string_db['STAR']='*';
$sel=srp_lecho ($_POST['USER_NAME']);
$string_db['FIELD']=$sel.'<input type="hidden" name="USER_NAME" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='Ваш e-mail';
$string_db['STAR']='*';
$sel=srp_lecho ($_POST['USER_MAIL']);
$string_db['FIELD']=$sel.'<input type="hidden" name="USER_MAIL" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

$string_db=array();
$string_db['NAME']='HTML Вашей ссылки';
$string_db['STAR']='*';
$sel=srp_lecho ($_POST['TEXT_HTML']);
$string_db['FIELD']=si_filters ($_POST['TEXT_HTML'], '5, 1').'<input type="hidden" name="TEXT_HTML" value="'.$sel.'">';
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

if ($links_settings_db['CAPTCHA'])
{
//капча.. если включена.

//генерим новый номер капчи
$captcha_num=captcha_get_rand_number(4);
$captcha_id=captcha_reg_new_number ($captcha_num);

$string_db=array();
$string_db['NAME']='Введите символы с картинки';
$string_db['STAR']='*';
$string_db['FIELD']='<input type="hidden" name="captcha_id" value="'.$captcha_id.'"><table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td><img src="'.Global_WWW_Path.'_shell/captcha/view_captcha.php?captcha_id='.$captcha_id.'" border="0" alt="captcha"></td>
  <td>&nbsp;</td>
  <td width="100%">'.l_text ('name="captcha" class="link_text_field" style="width:100px;"', '').'</td>
 </tr>
</table>';

$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
}
//---


$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']=l_buttion ('name="prev" class="link_grey_button"', 'Изменить данные').'&nbsp;&nbsp;&nbsp;'.l_buttion ('name="next" class="link_grey_button"', 'Добавить ссылку');
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);
//---

//выкидыаем данные через шаблон в поток.
$page_stream_db['CONTENT'].=si_field_replace ($form_db, $tpl_db['add_form']);

?>