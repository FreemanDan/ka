<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//обработка формы редактирования шаг 1
//если всё будет нормально, то устанвливаем переменную $inc_form на следующую форму (шаг 1)
//если были ошибки, то заполняем ими переменную $err и устаналиваем $inc_form на форму нулевого шага.


include_once (Root_Dir.'_shell/forms/add_functions.php');	//функи для добавления ссылки.
include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для генерирования сообщений.
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.

if (!si_get_session ('edit_link'.Base_Prefix)) 
{
$err.='Пожалуста авторизуйтесь<br>';
$inc_form='edit_form0.php';	//форма авторизации. шаг 0
}

//на всякий.. генерим ошибку, елси нет разрешённых параметров для изменения.
if (!$links_settings_db['EDIT'] && !$err) 
{
$err.='Нет параметров для изменения<br>';
$inc_form='edit_form1.php';	//форма редактирования. шаг 1
}

if (!$err)
{

//получаем массив со значениями формы. также в обработчиках фомируем поле с ошибками $post_item_data['err']
$post_item_data=link_get_post_data ();	

//получаем инфу по ид ссылки (ид закинули в сессию на нулевом шаге редактирования)
$post_item_data['item_db']=links_get_item_full_data (si_get_session ('edit_link'.Base_Prefix));

//проверяем введённые поля и собираем ошибки.
$post_item_data=verify_edit_link_data ($post_item_data, $links_settings_db);	//проверяем введённые данные.
$err.=$post_item_data['err'];

//теперь выполняем действия выставленные админов при изменении ссылки.
if (!$err) 
 {
 //ошибочек нет. Записываем изменения
 save_edit_link_data ($post_item_data, $links_settings_db);	//сохраняем.
 for ($i=0; $i<count($links_settings_db['EDIT_LINK_ACT']); $i++) link_exec_act ($links_settings_db['EDIT_LINK_ACT'][$i], $post_item_data['item_db']['id'], $links_settings_db, 1);
 }

//если были ошибки, то оставляем на этой форме.
if ($err) $inc_form='edit_form1.php';	//форма редактирования. шаг 1

}

//print_ar ($post_item_data);
if (!$err) $inc_form='edit_form2.php';	//уведомление об изменениях. шаг 2

?>