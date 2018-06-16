<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//обработка формы добавления шаг 0

//если всё будет нормально, то устанвливаем переменную $inc_form на следующую форму (шаг 1)
//если были ошибки, то заполняем ими переменную $err и устаналиваем $inc_form на форму нулевого шага.


include_once (Root_Dir.'_shell/forms/add_functions.php');	//функи для добавления ссылки.

//получаем данные из формы
$post_item_data=link_get_post_data ();	

$post_item_data=verify_add_link_data ($post_item_data, $links_settings_db);	//проверяем введённые данные.
$err.=$post_item_data['err'];

if (!$err) $inc_form='add_form1.php';	//ссылка прошла проверку и перешла ко второму шагу.

?>