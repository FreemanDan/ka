<?
define  ('Root_Dir','../'); 		//корень сайта относительно ЭТОГО скрипта
define  ('Forced_Sess_Start', 'On');	//принудительный старт сессии.

include (Root_Dir.'_shell/start_site.php'); //инициализация

//загрузка админских функций
include (Root_Dir.'_shell/functions/admin_functions.php');

//запускаем скрипт проверки авторизации.
//если авторизация не пройдена, то выполнение будет остановленно и будет выведенеа форма регистрации.
//также в этом скрипте устанавливается флаг инициализации админки.
include (Root_Dir.'_admin/admin_init.php');


//получаем все события и их обработчики.
$actions_db=get_admin_actions ();

if (!$actions) $actions='main_page';	//если действие неопределено, то главная страница.
$main_content=$actions_db[$actions]; //получаем элемент управления по акции

?>
<head>
<title>Панель управления</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/admin.css">
<script language="JavaScript" type="text/JavaScript"  src="admin.js"></script>
</head>

<body>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
   <td height="49" background="icons/_admin_bg_top.gif" align="left" class="header">&nbsp;&nbsp;&nbsp;Добро пожаловать, Администратор</td>
   <td width="175" background="icons/_admin_bg_top.gif" class="header" ><img src="icons/minilogo.jpg" border="0" width="175" height="49" alt=""></td>
  </tr>
</table>
<table border="0" width="100%" height="94%" cellspacing="0" cellpadding="3">
  <tr>
   <td width="200" bgcolor="#fefaea" valign="top"><? include (Root_Dir.'_admin/menu.php') ?></td>
   <td width="100%" valign="top"><div class="admin_content"><?
   instrument_name (get_only_text($path_db[0]['open_menu']['name']));	//имя открытого раздела.
   
   if ($main_content) include (Root_Dir.$main_content); //загружаем элемент управления
   ?></div></td>
  </tr>
</table>

</body>
<?
include (Root_Dir.'_shell/end_site.php'); //завершение работы
?>