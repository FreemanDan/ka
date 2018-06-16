<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//обработка формы добавления шаг 1
//на этом шаге производим проверку и добавление ссылки если проверка успешна.
//также откатываемся к шагу 0, если нажата кнопка "изменить данные".
//если всё будет нормально, то устанвливаем переменную $inc_form на следующую форму (шаг 2)
//если были ошибки, то заполняем ими переменную $err и устаналиваем $inc_form на форму шага 1 (эта же форма).
//для формы шага 2 передаём переменную $new_link_id с id добавленной ссылки.


include_once (Root_Dir.'_shell/forms/add_functions.php');	//функи для добавления ссылки.
include_once (Root_Dir.'_shell/functions/mail_functions.php');				//загрузка функций для управления почтой
include_once (Root_Dir.'_shell/functions/links_mail_functions.php');	//функи для генерирования сообщений.
include_once (Root_Dir.'_shell/functions/actions_handlers.php');		//загружаем информацию о действиях и их обработчики.
include_once (Root_Dir.'_shell/functions/captcha_functions.php');		//подгружаем функи для работы с капчей

$new_link_id='';		//в этой переменной передаём в послений шаг номер добавленной ссылки.

if ($_POST['prev']) $inc_form='add_form0.php';		//возвращемся к изменению данных

//если нажата кнопка "добавить", то выполняем проверку и добавление
if ($_POST['next'])
{
$inc_form='add_form1.php';	//по умолчанию крутимся на этой же форме.

//получаем массив со значениями формы. также в обработчиках фомируем поле с ошибками $post_item_data['err']
$post_item_data=link_get_post_data ();	

$post_item_data=verify_add_link_data ($post_item_data, $links_settings_db);	//проверяем введённые данные.
$err.=$post_item_data['err'];

if ($links_settings_db['CAPTCHA'])
{
//---------
//проверяем капчу.
if (!captcha_verify ($_POST['captcha_id'], $_POST['captcha'])) $err.='Вы неправильно ввели код подтвержения!<br>';
catpcha_drop_id ($_POST['captcha_id']);	//коцаем ид полюбому.
//---------
}

if (!$err)
{
//ошибок нет.
//добавляем ссылку в базу и переходим к форме поздравления.
$post_item_data=add_link_data ($post_item_data, $links_settings_db);
$inc_form='add_form2.php';	//ссылка прошла проверку и была добавлена. после чего переходим на шаг 2 - поздравление пользователя
}

}

?>