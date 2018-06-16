<?
define  ('Root_Dir',''); 		//файловый путь в папку со скриптами fairlinks относительно ЭТОГО скрипта
define  ('In_Buffer','Off'); 		//флаг буфферизации

include (Root_Dir.'_shell/start_site.php'); 	//инициализация

//выполняем скрипты по порядку.... авось всё получится...
include (Root_Dir.'_shell/parse_uri.php'); 	//распарсиваем переданные параметры.
include (Root_Dir.'_shell/main_view.php'); 	//контроллер формирования контента в зависимости от полученных параметров.. во как.
include (Root_Dir.'_shell/system_menu.php'); 	//дополнительно строим меню с основными разделами


include (Root_Dir.'_shell/end_site.php');	//завершение работы

?>