<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//инициализация админки и форма регистрации в одном лице.

if (!verify_admin()) 
{
//------------------
//эта часть выполняется если пользователь ткнулся в неразрешённую операцию в админке
//или функция verify_admin не возхвратила утвердительное значение.
//ошибка инициализации админки

$reg_ok='';

if ($_POST['admin_login_OK']) 
{
//нажата регисрация.. проверяем


if (defined('Admin_Login') && defined('Admin_Password'))
if (si_filters($_POST['login_name'], '1,5')==Admin_Login && si_filters($_POST['login_pass'], '1,5')==Admin_Password)
{
//ура! пользователь прётся в админку.. поаплодируем счастливому идиоту!!
//выводим в ссесию время регистрации и id пользователя.
si_set_session (Base_Prefix.'admin_act_time', $system_db['THIS_TIME']);
si_set_session (Base_Prefix.'admin_hash', md5(Admin_Login.Admin_Password));
$reg_ok='ok';		//флаг "данные верны. авторизация успешна"
}

}

if (!$reg_ok)
{
?><head>
<title>Авторизация</title>
<meta http-equiv="Content-Type" content="text/html; chcharset=utf-8
<link rel="stylesheet" type="text/css" href="<?=Global_WWW_Path ?>_admin/css/admin.css">
</head>


<form enctype="multipart/form-data" action="<?=$_SEVER['PHP_SELF'] ?>" method="POST">
<input name="actions" type="hidden" value="<?=$actions ?>">
<input name="subactions" type="hidden" value="<?=$subactions ?>">
<input name="id" type="hidden" value="<?=$id ?>">
<input name="subid" type="hidden" value="<?=$subid ?>">
<br><br><br><br><br><br><br><br><br><br><br>
<center>
<table  width="300"  cellspacing="1" class="maintable">
     <tr>
      <td colspan="2" align="center" height="35" class="maintitle">Авторизация</td>
     </tr>
    <tr class="rowlight">
      <td align="right" width="100%"><b>Логин:&nbsp;</td>
      <td><?=l_text ('name=login_name size=30',  $_POST['login_name']) ?></td>
     </tr>
     <tr class="rowlight">
      <td align="right"><b>Пароль:&nbsp;</td>
      <td><?=l_password ('name=login_pass size=30', '') ?></td>
     </tr>
     <tr class="rowdark">
      <td colspan="2" align="center" height="35"><?=l_buttion ('name=admin_login_OK', 'Принять') ?></td>
     </tr>
</table>
</form>
<?

//глушилка.
my_exit ();
}


//------------------
}


//всё нормально в плане авторизации.

//выставляем флаг "всё ок".
define  ('Init_Admin_FairLinks','Ok'); 		//выставляем флаг, что был запущен скрипт инициализации админки fairlinks


//записываем время последнего действия в сессию.
si_set_session (Base_Prefix.'admin_act_time', $system_db['THIS_TIME']);

?>