<?

//###############################################################################################
//#												#
//#	Скрипт инициализации сайта. Подгрузка основных фунок, фильтры, настройки среды.		#
//#												#
//#	Написать код, понятный машине - сможет каждый.						#
//#	Намного труднее написать код, понятный человеку. 					#
//#						(c) Кто то из великих..				#
//#												#
//###############################################################################################

define  ('Init_FairLinks','Ok'); 		//выставляем флаг, что был запущен скрипт инициализации fairlinks

//В случае экстремальной отладки закомментировать эту строку..
//кто хочет поспорить о таких настройках - пжалуста.. пишем мне в почту alexey@zanevskiy.com
ini_set ("error_reporting", 'E_ALL & ~E_NOTICE');

//определение включения/выключения буфферизации вывода.
//если In_Buffer=='On', то врубаем бефферизацию.
//если включена буфферизация, то выброс из буффера будет в end_site.php
//несмотря на то, что все данные пишутся в поток $page_stream_db['CONTENT'], но могут возникнуть ситуации, когда нужно бефферизовать вывод. 
//вот для таких случаев и надо использовать буфферизацию. По умолчанию буфферизация отключена.
if (In_Buffer=='On') ob_start ();

//вспомогательные системные функци загружаемые до инициализации и загрузки основных фунок.
include (Root_Dir.'_shell/functions/init_functions.php');

//засекаем время старта системы.
//погрешность тут фактически минимальная.
$FL_start_microtime=microtime(); 	//время старта.


//====================================
//выставляем настройки 
ini_set("session.use_trans_sid", 0);		//скрываем по возможности передачу номера сессии в урле.
ini_set("magic_quotes_sybase", 0);		//выключаем возможные дурацкие настройки
ini_set("magic_quotes_gpc", 1);			//привык.. а сила привычки - страшная штука..
ini_set("session.use_cookies", 1); 		//по возможности номер сессии пишем в куку.
ini_set ("max_execution_time", 300);		//максимальное время выполнения. Если поставить 0, то время выполнения будет неограниченно.


setlocale(LC_CTYPE, "ru_RU.UTF-8");	//настройка локали. на всякий..


//загрузка настроек и констант.
include (Root_Dir.'_constants.php');

//грузим основные функи.
include (Root_Dir.'_shell/functions/functions.php');		//основные функции
include (Root_Dir.'_shell/functions/advanced_functions.php');	//дополнительные функции
include (Root_Dir.'_shell/functions/tpl_functions.php');	//функции для работы с шаблонами


//если кука на сессию уже стоит, то всё зашибись. стартуем спокойненько сессию.
//также статуем сессию, если выставлен принудительный старт сессии Forced_Sess_Start=On
if ($_COOKIE[session_name ()] || Forced_Sess_Start=='On')  session_start();
//ДОБАВЛЕНИЕ И ЧТЕНИЕ СЕССИЙ ТОЛЬКО ЧЕРЕЗ ФУНКЦИИ si_set_session () и si_get_session () !!


//подключаемся к базу данных с настройками из _constants.php.
//если подключение неудалось, то останавливаем выполнение.
Error_Reporting(1+2+4);
@$base_connect=mysql_connect(HostName,UserName,Password);
if(!$base_connect) die ('<div align="center" style="padding-top:200px;"><font color="#FF0000" size="4"><b>Connection MySQL error!</b></font></div>');
mysql_select_db(DBName);	//выбираем базу. 



//обработка гет (через GET зепрещенно передавать всё, кроме цифр, анг.симв. . _ и  -)
//также через GET запрещенно передавать массивы и пр. хрень.. ИБО НЕФИГ!
//кому надо, то пользуйтесь массивами $_REQUEST и т.д.
if ($_GET) foreach ($_GET as $key => $value) if (!is_array ($_GET[$key])) $_GET[$key]=warn ($value); else $_GET[$key]='';

if (!ini_get('magic_quotes_gpc')) 
{
//всётаки привык я работать с get_magic_quotes_gpc()==1 .. мндя..
//и т.к. всё расчитанно именно на такую работу, то в случае выключенных 'magic_quotes_gpc слэшируем суперглобаные массивы.
$_GET=adds_array ($_GET);
$_COOKIE=adds_array ($_COOKIE);
$_POST=adds_array ($_POST);
}


$system_db=array();		//массив со всякими допольнительными настройками системы.
$system_db['THIS_TIME']=time();	//единое время.
$system_db['SQL_EXEC_QUERY_DB']=array();	//массив в который пишется время выполнения запросов к MySQL
$system_db['LAST_QUERY_ERROR']=array();		//массив в который собираются сообщения об ошибках запросов к MySQL. Вывести их можно функцие print_ar ($system_db['LAST_QUERY_ERROR']);

$links_settings_db=get_link_settings ();	//получаем настройки каталога ссылок.
$system_db['FIELD_ADMIN_MAIL']=$links_settings_db['NEW_MAIL_NOTIC'];


//дополнительно.
$err='';			//обычно сюда собираются сообщения об ошибках.
$page_stream_db=array();	//массив - основной поток страницы. Основное поле $page_stream_db['CONTENT'] куда и собираются данные для вывода.


//дополнительно системные переменные.
$actions	=	warn($_REQUEST['actions']);
$subactions	=	warn($_REQUEST['subactions']);
$id		=	intval($_REQUEST['id']);
$subid		=	intval($_REQUEST['subid']);
$page		=	warn($_REQUEST['page']);


?>