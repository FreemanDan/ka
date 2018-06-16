<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//форма отправки сообщения модератору

include_once (Root_Dir.'_shell/functions/captcha_functions.php');		//подгружаем функи для работы с капчей
include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой

$err='';		//сюда складываем ошибки. если чо.
$bee_send='';		//флаг успешной отправки письма. Если равен Ok - значит письмо было отправленно.

if ($_POST['send'])
{
$NAME=si_filters ($_POST['NAME'], '1,5');
$MAIL=si_filters ($_POST['MAIL'], '1,5');
$MESSAGE=si_filters ($_POST['MESSAGE'], '1,5');

//блок проверок
if (!eto_ne_pusto($NAME)) $err.='Поле "Ваше имя" обязательно для заполнения!<br>';
if (!eto_ne_pusto($MAIL)) $err.='Поле "Ваш e-mail" обязательно для заполнения!<br>';
if (!eto_ne_pusto($MESSAGE)) $err.='Поле "Сообщение" обязательно для заполнения!<br>';
if (!$err) if (!si_verify_valid_email ($MAIL)) $err.='Введите корректный e-mail!<br>';

//обрабатываем капчу.
if (!captcha_verify ($_POST['captcha_id'], $_POST['captcha'])) $err.='Вы неправильно ввели символы с картинки!<br>';
catpcha_drop_id ($_POST['captcha_id']);	//коцаем ид полюбому.

if (!$err)
{
//ошибок нет.. формируем и отправляем письмо.

$subj='Сообщение из каталога ссылок '.$_SERVER['HTTP_HOST'].' ('.date ('d-m-Y H:i', $system_db['THIS_TIME']).')';

$this_cat_link='http://'.$_SERVER['HTTP_HOST'].Global_WWW_Path;
$text='Каталог ссылок <a href="'.$this_cat_link.'" target="_blank">'.$this_cat_link.'</a><br><br>';
$text.='<table width="640" border="1" cellspacing="0" bordercolorlight="#000000" bordercolordark="#FFFFFF" cellpadding="3" bordercolor="#FFFFFF">';
$text.='<tr><td align="right" width="40%"><b>Имя:</b></td><td width="60%" align="left">&nbsp;'.lecho($NAME, 1000).'</td></tr>';
$text.='<tr><td align="right"><b>E-mail:</b></td><td align="left">&nbsp;'.lecho($MAIL,1000).'</td></tr>';
$text.='<tr><td align="right"><b>Сообщение:</b></td><td align="left">'.nl2br(lecho ($MESSAGE, 5000)).'</td></tr>';
$text.='</table>';

//отправляем писм админу.
SI_send_mail ($links_settings_db['NEW_MAIL_NOTIC'], $subj, $text);
$bee_send='Ok';
}

}

if ($bee_send!='Ok')
{
$form_db=array();	//сюда грузим данные для шаблона.
$form_db['ACTION']=links_get_url ('message', '', '');
$form_db['FORM_NAME']='message';
$form_db['HIDDEN_VARS']='';		//дополнительные скрытые переменные в форме.
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
$string_db['NAME']='Ваше имя';
$string_db['STAR']='*';
$string_db['FIELD']=l_text ('name="NAME" class="link_text_field" style="width:99%;"', $_POST['NAME']);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

//---
$string_db=array();
$string_db['NAME']='Ваш e-mail';
$string_db['STAR']='*';
$string_db['FIELD']=l_text ('name="MAIL" class="link_text_field" size="30"', $_POST['MAIL']);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---

//---
$string_db=array();
$string_db['NAME']='Сообщение';
$string_db['STAR']='*';
$string_db['FIELD']=l_textarea ('name="MESSAGE" class="link_text_field" rows="10" style="width:99%;"', $_POST['MESSAGE']);
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
//---


//генерим новый номер капчи
$captcha_num=captcha_get_rand_number(4);
$captcha_id=captcha_reg_new_number ($captcha_num);

$string_db=array();
$string_db['NAME']='Введите символы с картинки';
$string_db['STAR']='*';
$string_db['FIELD']='<input type="hidden" name="captcha_id" value="'.$captcha_id.'"><table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td><img src="'.Global_WWW_Path.'_shell/captcha/view_captcha.php?captcha_id='.$captcha_id.'" border="0" alt="captcha" border="0" alt="captcha"></td>
  <td>&nbsp;</td>
  <td width="100%">'.l_text ('name="captcha" class="link_text_field" style="width:100px;"', '').'</td>
 </tr>
</table>';

$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_strings']);
$string_db=array();
$string_db['NAME']='';
$string_db['STAR']='';
$string_db['FIELD']=l_buttion ('name="send" class="link_grey_button"', 'Отправить');
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);
//---

//добавляем форму на страницу.
$page_stream_db['CONTENT'].=si_field_replace ($form_db, $tpl_db['add_form']);
}
else
{
//сообщение было отправленно.
//выводим поздравительное уведомление.

$form_db=array();
$form_db['HEADER']='Сообщение отправлено';
$form_db['MESSAGE']='Спасибо. Ваше сообщение отправлено администратору каталога ссылок.';

//выбрасываем данные в поток.
$page_stream_db['CONTENT'].=si_ff_replace ($form_db, Root_Dir.'tpl/'.Use_Template.'/message.tpl', 1);	//заодно чистим шаблон от мусора.. (коментариев и пр.)

}
?>