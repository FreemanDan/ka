<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//форма отправки сообщения модератору

include_once (Root_Dir.'_shell/functions/captcha_functions.php');		//подгружаем функи для работы с капчей
include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для генерирования сообщений.
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.

$err='';		//сюда складываем ошибки. если чо.
$bee_send='';		//флаг успешной отправки письма. Если равен Ok - значит письмо было отправленно.

if ($_POST['send'])
{
$MAIL=trim ($_POST['MAIL']);

//блок проверок
if (!eto_ne_pusto($MAIL)) $err.='Поле "Ваш e-mail" обязательно для заполнения!<br>';
if (!$err) if (!si_verify_valid_email ($MAIL)) $err.='Введите корректный e-mail!<br>';

//обрабатываем капчу.
if (!captcha_verify ($_POST['captcha_id'], $_POST['captcha'])) $err.='Вы неправильно ввели символы с картинки!<br>';
catpcha_drop_id ($_POST['captcha_id']);	//коцаем ид полюбому.

if (!$err)
{
//ошибок нет.. ищем ссылку
$item_db=SI_sql_query ("select id from ".Base_Prefix."links_items WHERE FIELD_USER_MAIL LIKE '%".get_string_for_like ($MAIL)."%'");

if (!$item_db) $err.='Ссылок с e-mail "'.si_filters ($MAIL, '0,2').'" нет в каталоге!<br>';
}

if (!$err && $item_db)
{
//отлично. Выполняем действие "отправить уведомление" над всеми найденными ссылками.

for ($i=0; $i<count($item_db); $i++) link_exec_act ('good_message', $item_db[$i]['id'], $links_settings_db, 1);

$bee_send='Ok';		//выкидываем флаг, что всё ок.
}

}

if ($bee_send!='Ok')
{
$form_db=array();	//сюда грузим данные для шаблона.
$form_db['ACTION']=links_get_url ('remember', '', '');
$form_db['FORM_NAME']='remember';
$form_db['HIDDEN_VARS']='';		//дополнительные скрытые переменные в форме.
$form_db['ERRORS']='';
$form_db['FIELD_STRINGS']='';
$form_db['ERRORS']=si_generate_error_list ($err);	//генерим список ошибок из ошибок, собранных в $err


$tpl_db=array();	//загребаем шаблоны
$tpl_db['add_form']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_form.tpl'));	//получаем шаблон
$tpl_db['add_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_strings.tpl'));	//получаем шаблон
$tpl_db['add_big_strings']=clear_garbage_tpl (file_get_contents (Root_Dir.'tpl/'.Use_Template.'/add_big_strings.tpl'));	//получаем шаблон



//---
$string_db=array();
$string_db['NAME']='Ваш e-mail';
$string_db['STAR']='*';
$string_db['FIELD']=l_text ('name="MAIL" class="link_text_field" size="30"', $_POST['MAIL']);
$string_db['DESC']='Укажите e-mail, который был введён при добавлении ссылки и, если ссылка с таким e-mail присутствует в базе, то на этот e-mail будет выслано письмо с кодом для редактирования этой ссылки.';
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
$string_db['FIELD']=l_buttion ('name="send" class="link_grey_button"', 'Принять');
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
$form_db['MESSAGE']='
На указанные почтовые адреса отправлен код для редактирования и уведомление о положении ссылки в нашем каталоге.<br>
Если сообщение не придёт в ближайшее время, то обратитесь к администратору каталога ссылок.
';

//выбрасываем данные в поток.
$page_stream_db['CONTENT'].=si_ff_replace ($form_db, Root_Dir.'tpl/'.Use_Template.'/message.tpl', 1);	//заодно чистим шаблон от мусора.. (коментариев и пр.)

}
?>