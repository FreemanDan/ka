<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
if (Init_Admin_FairLinks!='Ok') die ('Not admin init!');		//проверяем флаг успешной инициализации админки.
//редактирование настроек.

include_once (Root_Dir.'_shell/functions/advanced_functions.php');	//загружаем доп. функи для этого типа страниц
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.

$form_name='settings'.Base_Prefix;

?>
<div class="catalog_path">Действия: <a href="<?=$_SERVER['PHP_SELF'] ?>">Главное меню "Каталог ссылок"</a> / Настройки каталога ссылок</div>
<?

//========================================================================

if ($_POST['ok'])
{
//записываем изменения.

$SETTINGS=base64_encode (@serialize ($_POST));

//записываем инфу.
SI_sql_query("UPDATE ".Base_Prefix."links_settings SET FIELD_SETTINGS='$SETTINGS'");

//генерим окно с сообщением
$choise_db=array(); $num=0;
$choise_db[$num]['name']='В главное меню "Каталог ссылок"';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'];

$choise_db[$num]['name']='Продолжить редактирование';
$choise_db[$num++]['link']=$_SERVER['PHP_SELF'].'?actions='.$actions;

$message='Изменения приняты!';
echo si_message_box ('Ок',$message, $choise_db, 0, '350px');
my_exit();
}
//========================================================================

//print_ar ($links_settings_db);
?>
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'] ?>" method="POST"  style="margin:0px;" name=<?=$form_name ?> >
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">

<fieldset class="normal_fieldset"><legend>Настройки каталога ссылок</legend>

Новые ссылки:<br>
<input type="radio" value="1" <? if ($links_settings_db['FAST_ENABLE']==1) echo 'checked'; ?> name="FAST_ENABLE"> - <span class="normal_red">публикуются без проверки модератором</span><br>
<input type="radio" value="0" <? if ($links_settings_db['FAST_ENABLE']==0) echo 'checked'; ?> name="FAST_ENABLE"> - <span class="normal_green">могут быть "включены" только модератором</span>
<br><br>

Количество ссылок на одной странице: <br>
На сайте: <?=l_text ('name="REC_ON_PAGE" size="15"', $links_settings_db['REC_ON_PAGE']) ?>&nbsp;&nbsp;&nbsp;&nbsp;В админке: <?=l_text ('name="REC_ON_PAGE_ADMIN" size="15"', $links_settings_db['REC_ON_PAGE_ADMIN']) ?>
<br>
<span class="help">Если 0, то количество ссылок определяется "по умолчанию".</span><br><br>

Новые ссылки добавлять в:<br>
<input type="radio" value="1" <? if ($links_settings_db['INSERT_IN']==1) echo 'checked'; ?> name="INSERT_IN"> - <span class="normal_red">в начало</span><br>
<input type="radio" value="0" <? if ($links_settings_db['INSERT_IN']==0) echo 'checked'; ?> name="INSERT_IN"> - <span class="normal_green">в конец</span>
<br><br>

В качестве black list использовать категорию:<br>
<?
$tmp_db=SI_sql_query("select id, FIELD_NAME from ".Base_Prefix."links_category ORDER BY FIELD_SORT ASC");
$view_category_db=array();
$view_category_db['0']='Неопределена';
$view_category_db=si_array_push ($view_category_db, si_generate_afa ($tmp_db, 'id', 'FIELD_NAME'));  
echo l_select ('name="BLACK_LIST_ID"', $view_category_db, $links_settings_db['BLACK_LIST_ID']);
?>
<br><br>


E-mail администратора каталога: <br>
<?=l_text ('name="NEW_MAIL_NOTIC" size="45"', $links_settings_db['NEW_MAIL_NOTIC']) ?>&nbsp;<input type="checkbox" name="ADMIN_NOTIC" value="1" <? if ($links_settings_db['ADMIN_NOTIC']==1) echo 'checked'; ?> > <span class="help">Отправлять уведомления о новых ссылках</span>
<br>
<br>

"Галочка" "оправить e-mail уведомление пользователю" по умолчанию:<br>
<input type="radio" value="checked" <? if ($links_settings_db['DEF_FAST_MAIL']=='checked') echo 'checked'; ?> name="DEF_FAST_MAIL"> - <span class="normal_red">Выбрана</span><br>
<input type="radio" value="" <? if ($links_settings_db['DEF_FAST_MAIL']=='') echo 'checked'; ?> name="DEF_FAST_MAIL"> - <span class="normal_green">Снята</span>

</fieldset>

<fieldset class="normal_fieldset"><legend>Правила добавляемой ссылки</legend>
Максимум ссылок в HTML: <br>
<?=l_text ('name="MAX_LINK_IN_HTML" size="10"', $links_settings_db['MAX_LINK_IN_HTML']) ?>
<br><br>

Максимальная длина HTML (символов): <br>
<?=l_text ('name="MAX_HTML_LENGHT" size="10"', $links_settings_db['MAX_HTML_LENGHT']) ?>
<br><br>

Разрешённые тэги в HTML: <br>
<?=l_text ('name="ALLOW_TAGS" size="50"', $links_settings_db['ALLOW_TAGS']) ?>
<div class="help">Список разрешённых тэгов для HTML кода ссылки. Например <?=lecho ('<a><p><i>'); ?></div>

</fieldset>

<fieldset class="normal_fieldset"><legend>Правила добавляемого сайта</legend>
Отказывать сайтам содержащим в адресе: <br>
<?=l_textarea ('name="DENY_DOMEN_STR" style="width:100%" rows="3"', $links_settings_db['DENY_DOMEN_STR']) ?>
<span class="help">Запрещённые строки разделены пробелом. Например строки narod.ru h15.ru будут запрещать добавление сайтов с бесплатных хостингов narod.ru и h15.ru</span>
<br><br>

Показатели сайта должен быть не меньше: <br>
тИЦ: <?=l_text ('name="MIN_TIC" size="5"', intval ($links_settings_db['MIN_TIC'])) ?>&nbsp;&nbsp;&nbsp;PR главной страницы: <?=l_text ('name="MIN_PR" size="5"', intval ($links_settings_db['MIN_PR'])) ?><br>
<span class="help">
Если поле равно 0, то параметр игнорируется.
</span>
<br><br>

Присутствие в индексе Yandex.ru:<br>
<input type="radio" value="1" <? if ($links_settings_db['VERIFY_YA_INDEX']==1) echo 'checked'; ?> name="VERIFY_YA_INDEX"> - <span class="normal_green">проверять</span><br>
<input type="radio" value="0" <? if ($links_settings_db['VERIFY_YA_INDEX']==0) echo 'checked'; ?> name="VERIFY_YA_INDEX"> - <span class="normal_red">не проверять</span>
<br><br>

Добавляемый сайт главное зеркало в Yandex.ru:<br>
<input type="radio" value="1" <? if ($links_settings_db['VERIFY_YA_MIRROR']==1) echo 'checked'; ?> name="VERIFY_YA_MIRROR"> - <span class="normal_green">проверять</span><br>
<input type="radio" value="0" <? if ($links_settings_db['VERIFY_YA_MIRROR']==0) echo 'checked'; ?> name="VERIFY_YA_MIRROR"> - <span class="normal_red">не проверять</span><br>
<span class="help">Скрипт запросит yandex.ru на предмет - является ли добавляемый сайт чьим то зеркалом или нет. Если добавляемый сайт является чьим то зеркалом, то будет сформированно сообщение об ошибке.</span>
<br><br>

Проверка обратных ссылок при добавлении:<br>
<input type="radio" value="1" <? if ($links_settings_db['VERIFY_RET_LINK']==1) echo 'checked'; ?> name="VERIFY_RET_LINK"> - <span class="normal_red">проверять</span><br>
<input type="radio" value="0" <? if ($links_settings_db['VERIFY_RET_LINK']==0) echo 'checked'; ?> name="VERIFY_RET_LINK"> - <span class="normal_green">не проверять</span> 
<div class="help" style="padding-left:40px;">
<input type="checkbox" name="HIDDEN_RET_LINK_FIELD" value="1" <? if ($links_settings_db['HIDDEN_RET_LINK_FIELD']==1) echo 'checked'; ?> > 
- убрать из формы поле для ввода адреса страницы с обратной ссылкой. 
Параметр учитывается только если обратные ссылки не проверяются при добавлении. 
</div>
<br>

На странице с обратной ссылкой должно быть не более исходящих ссылок: <br>
Всего: <?=l_text ('name="MAX_OUT_LINKS_TOTAL" size="10"', intval ($links_settings_db['MAX_OUT_LINKS_TOTAL'])) ?>
&nbsp;&nbsp;
Из них на разные домены: <?=l_text ('name="MAX_OUT_LINKS" size="10"', intval ($links_settings_db['MAX_OUT_LINKS'])) ?>
<br>
<span class="help">
Параметр "всего" - это сколько всего исходящих ссылок на станице, а параметр "на домены" - это количество доменов (сайтов) на которые ссылаются со страницы. 
Т.е. если 10 ссылок ссылаются на 1 сайт, то они и будут учтены как ссылающиеся на 1 сайт.<br>
Т.е. при параметрах "всего 100, на домены 10" будет означать, что на странице должно быть не более 100 разных исходящих ссылок ведущих на не более чем 10 разных сайтов.<br>
Если параметры равны 0, то они игнорируются. <br>
Также эти параметры применяются только если включена опция "Проверка обратных ссылок при добавлении"
</span>
<br><br>

Защита CAPTCHA:<br>
<input type="radio" value="0" <? if ($links_settings_db['CAPTCHA']==0) echo 'checked'; ?> name="CAPTCHA"> - <span class="normal_red">Отключена</span><br>
<input type="radio" value="1" <? if ($links_settings_db['CAPTCHA']==1) echo 'checked'; ?> name="CAPTCHA"> - <span class="normal_green">Включена</span>
<br>
<span class="help">
После заполнения формы и проверки всех параметров добавляемой ссылки пользователю будет предложено ввести код с картинки.
</span>
</fieldset>

<fieldset class="normal_fieldset"><legend>Изменение параметров ссылок владельцами</legend>

Разрешить изменение:<br>
<input type="checkbox" name="EDIT[FIELD_LINK_CAT]" value="1" <? if ($links_settings_db['EDIT']['FIELD_LINK_CAT']==1) echo 'checked'; ?> > - изменение категории<br> 
<input type="checkbox" name="EDIT[FIELD_DOMAIN]" value="1" <? if ($links_settings_db['EDIT']['FIELD_DOMAIN']==1) echo 'checked'; ?> > - изменение адреса сайта<br> 
<input type="checkbox" name="EDIT[FIELD_NAME]" value="1" <? if ($links_settings_db['EDIT']['FIELD_NAME']==1) echo 'checked'; ?> > - изменение названия сайта<br> 
<input type="checkbox" name="EDIT[FIELD_IMG_ADDR]" value="1" <? if ($links_settings_db['EDIT']['FIELD_IMG_ADDR']==1) echo 'checked'; ?> > - изменение адреса баннера 88х31<br> 
<input type="checkbox" name="EDIT[FIELD_TEXT_HTML]" value="1" <? if ($links_settings_db['EDIT']['FIELD_TEXT_HTML']==1) echo 'checked'; ?> > - изменение HTML ссылки<br> 
<input type="checkbox" name="EDIT[FIELD_RET_LINK_ADDR]" value="1" <? if ($links_settings_db['EDIT']['FIELD_RET_LINK_ADDR']==1) echo 'checked'; ?> > - изменение адреса страницы с обратной ссылкой<br> 
<input type="checkbox" name="EDIT[FIELD_USER_NAME]" value="1" <? if ($links_settings_db['EDIT']['FIELD_USER_NAME']==1) echo 'checked'; ?> > - изменение имени пользователя<br> 
<input type="checkbox" name="EDIT[FIELD_USER_MAIL]" value="1" <? if ($links_settings_db['EDIT']['FIELD_USER_MAIL']==1) echo 'checked'; ?> > - изменение почты пользователя<br> 

<div class="help">
При выполнении некотрых действий пользователям в отправленном письме прилагается адрес и пароль для редактирования, 
по которым они могут изменить разрешённые параметры своих ссылок. Если нет разрешённых параметров для изменения, то в письме не будут прилагаться данные для изменения ссылок.
<br><b>При редактировании ссылок применяются параметры проверки новых ссылок.</b>
</div>

<br><br>

При успешном изменении данных пользователем выполнить:<br>
<?
//формируем и выводим таблицу с действиями

$act_db=array();
$act_db[]='off';
$act_db[]='on';
$act_db[]='recicler';
$act_db[]='autoperenos';
$act_db[]='good_message';
$act_db[]='edit_admin_notice';
$act_db[]='nah';
echo links_get_act_table ('EDIT_LINK_ACT', $act_db, $links_settings_db['EDIT_LINK_ACT']);

?>

</fieldset>

<fieldset class="normal_fieldset"><legend>Правила каталога ссылок</legend>
Максимум штрафных баллов: <br>
<?=l_text ('name="MAX_BAD_BALLS" size="10"', $links_settings_db['MAX_BAD_BALLS']) ?><br><br>

При превышении штрафных баллов выполнить:<br>
<?
//формируем и выводим таблицу с действиями

$act_db=array();
$act_db[]='off';
$act_db[]='recicler';
$act_db[]='autoperenos';
$act_db[]='please_set_retry';
$act_db[]='gde_obratka';
$act_db[]='good_message';
$act_db[]='nah';
$act_db[]='bb_admin_notice';
echo links_get_act_table ('BAD_BALLS_ACT', $act_db, $links_settings_db['BAD_BALLS_ACT']);

?>
</fieldset>
<br><br>
<center><?=l_buttion ('name=ok', 'Принять изменения') ?></center>
</form>