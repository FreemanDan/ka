<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//контроллер загружаемый разделов

//результат выполнения загружается в $page_stream_db['CONTENT']
//$mod_rewrite_path_db - последовательность параметров в массиве.

//доп. функи
include_once (Root_Dir.'_shell/functions/main_view_functions.php');	//загружам доп. функи

//--------------------------------
//определяем дополнительные данные, которые выводятся в шаблон.

$page_stream_db['WWW_ROOT']=Global_WWW_Path;	//www путь в корень папки со скриптами fairlinks от корня сайта
$page_stream_db['TEMPLATE']=Use_Template;	//папка с используемым шаблоном. (в каталоге tpl)

//каждый загруженный модуль или форма могут изменять эти данные по своему усмотрению.
//так, например, в каталоге ссылок на каждую категорию определены свои МЕТА данные.
//в прочих случаях (формы, диалоги и пр.) мета данные устанавливаются отдельно функцией links_get_meta ($key, $name);

$page_stream_db['TITLE']='Нет заголовка';	//заголовок страницы
$page_stream_db['PAGE_NAME']='Нет названия';	//название страницы
$page_stream_db['KEYWORDS']='';			//ключевые слова на странице
$page_stream_db['DESCRIPTION']='';		//МЕТА описание на странице
//--------------------------------


$post_item_data='';				//именно сюда загружаются данные для обхода функций валидации.
$err='';					//сюда пишем ошибочки там всякие..

//в зависимости от действия загружает тот или иной скрипт.
switch ($actions)
{
 case 'cat': 
  //если задан параметр показа категории - показываем её.
  //в разделах каталогов мета данные определяются АВТОМАТИЧЕСК из категорий.
  include (Root_Dir.'_shell/forms/cat.php');
 break;
 
 case 'add': 
  //работаем с формами добавления ссылки.
  $err='';			//сюда грузятся ошибки каждого шага.
  $inc_form='add_form0.php';	//по умолчанию грузим форму 0
  //если в ПОСТ указан step = 0 или 1, то грузим соотвествующий обработчик, который выставит значение $inc_form на нужное.
  //в обработчике происходит указание переменной $inc_form на нужную форму для загрузки.
  //т.е. если на шаге 0 всё правильно - грузим форму 1 или остаёмся на форме 0 с выводом ошибок.
  if (si_key_exists ('step', $_POST) && $_POST['step']==0) include (Root_Dir.'_shell/forms/add_exec0.php');
  if (si_key_exists ('step', $_POST) && $_POST['step']==1) include (Root_Dir.'_shell/forms/add_exec1.php');
  include (Root_Dir.'_shell/forms/'.$inc_form);
  //----------------------
  //устанавливаем мета данные для этого модуля.
  //предустановленные ключи можно посмотреть в таблице мета данных links_meta
  $meta_db=links_get_meta ('add', 'Форма добавления ссылки');
  if ($meta_db) foreach ($meta_db as $key => $value) $page_stream_db[$key]=$meta_db[$key];	//переносим переменные из $meta_db в данные страницы.
  //----------------------
 break;

 case 'edit': 
  //работаем с формами изменения ссылки.
  $err='';			//сюда грузятся ошибки каждого шага.
  $inc_form='edit_form0.php';	//по умолчанию грузим форму 0
  //если в ПОСТ указан step = 0 или 1, то грузим соотвествующий обработчик, который выставит значение $inc_form на нужное.
  //в обработчике происходит указание переменной $inc_form на нужную форму для загрузки.
  //т.е. если на шаге 0 всё правильно - грузим форму 1 или остаёмся на форме 0 с выводом ошибок.
  if (si_key_exists ('step', $_POST) && $_POST['step']==0) include (Root_Dir.'_shell/forms/edit_exec0.php');
  if (si_key_exists ('step', $_POST) && $_POST['step']==1) include (Root_Dir.'_shell/forms/edit_exec1.php');
  include (Root_Dir.'_shell/forms/'.$inc_form);
  //----------------------
  //устанавливаем мета данные для этого модуля.
  //предустановленные ключи можно посмотреть в таблице мета данных links_meta
  $meta_db=links_get_meta ('edit', 'Форма редактирования ссылки');
  if ($meta_db) foreach ($meta_db as $key => $value) $page_stream_db[$key]=$meta_db[$key];	//переносим переменные из $meta_db в данные страницы.
  //----------------------
 break;

 case 'message': 
  //выводим форму сообщения админу.
  include (Root_Dir.'_shell/forms/message.php');
  //----------------------
  //устанавливаем мета данные для этого модуля.
  //предустановленные ключи можно посмотреть в таблице мета данных links_meta
  $meta_db=links_get_meta ('message', 'Форма отправки сообщения модератору');
  if ($meta_db) foreach ($meta_db as $key => $value) $page_stream_db[$key]=$meta_db[$key];	//переносим переменные из $meta_db в данные страницы.
  //----------------------
 break;

 case 'remember': 
  //выводим форму восстановления пароля
  include (Root_Dir.'_shell/forms/remember.php');
  //----------------------
  //устанавливаем мета данные для этого модуля.
  //предустановленные ключи можно посмотреть в таблице мета данных links_meta
  $meta_db=links_get_meta ('remember', 'Форма восстановления кода для редактирования');
  if ($meta_db) foreach ($meta_db as $key => $value) $page_stream_db[$key]=$meta_db[$key];	//переносим переменные из $meta_db в данные страницы.
  //----------------------
 break;

 default:
  //по умолчанию получаем большой список категорий
  //главная страница каталога
  include (Root_Dir.'_shell/forms/main_page.php');
  //----------------------
  //устанавливаем мета данные для этого модуля.
  //предустановленные ключи можно посмотреть в таблице мета данных links_meta
  $meta_db=links_get_meta ('main_page', 'Главная страница каталога ссылок');
  if ($meta_db) foreach ($meta_db as $key => $value) $page_stream_db[$key]=$meta_db[$key];	//переносим переменные из $meta_db в данные страницы.
  //----------------------
 break;
}

//добавляем в поток номер версии и копирайт автора.
include (Root_Dir.'_shell/versncopy.php');
?>