<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//форма редактирования ссылки. шаг 0

include_once (Root_Dir.'_shell/functions/captcha_functions.php');		//подгружаем функи для работы с капчей


$form_db=array();	//сюда грузим данные для шаблона.
$form_db['ACTION']=links_get_url ('edit', '', '');
$form_db['FORM_NAME']='edit_link';
$form_db['HIDDEN_VARS']='<input type="hidden" name="step" value="0">';		//дополнительные скрытые переменные в форме.
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
$string_db['NAME']='Код для изменения ссылки';
$string_db['STAR']='*';
$sel='';
if ($_POST['KEY_FOR_EDIT']) $sel=$_POST['KEY_FOR_EDIT'];
$string_db['FIELD']=l_text ('name="KEY_FOR_EDIT" class="link_text_field" size="30"', $sel);
$string_db['DESC']='Если вы забыли свой персональный код для изменения ссылки, 
то можете <a href="'.links_get_url ('message', '', '').'">запросить</a> его у администратора каталога ссылок либо воспользоваться <a href="'.links_get_url ('remember', '', '').'">формой восстановления кода</a>.';
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
$string_db['FIELD']=l_buttion ('name="next" class="link_grey_button"', 'Вход');
$string_db['DESC']='';
$form_db['FIELD_STRINGS'].=si_field_replace ($string_db, $tpl_db['add_big_strings']);
//---

//добавляем форму на страницу.
$page_stream_db['CONTENT'].=si_field_replace ($form_db, $tpl_db['add_form']);
?>