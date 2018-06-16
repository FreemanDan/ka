<!--
Общий глобальный шаблон. 
шаблон является корневым.
{WWW_ROOT} - путь www в корень каталога со скриптами fairlinks (задан в _constants.php)
{TEMPLATE} - каталог с используемым шаблоном.
{TITLE} - заголовок страницы
{KEYWORDS}  - ключевые слова
{DESCRIPTION} - мета описание
{CONTENT} - сгенерированные данные.
{SYSTEM_MENU} - менюшка с системными разделами.
{VERSION} - номер версии и ссылка на сайт автора
{COPYRIGHT} - копирайт автора
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
<head>
<TITLE>{TITLE}</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META name="keywords" content="{KEYWORDS}" >
<META name="description" content="{DESCRIPTION}" >
<link href="{WWW_ROOT}tpl/{TEMPLATE}/css/styles.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript"  src="{WWW_ROOT}tpl/{TEMPLATE}/js/fl.js"></script>
</head>
<body>
<div align="center">
<div class="link_global" style="width:600px;">
<div class="links_page_name"><h2>{PAGE_NAME}</h2></div>
{CONTENT}
{SYSTEM_MENU}

<div class="links_exec_info">Общее время: <b>{TOTAL_EXEC_TIME}</b> сек. | Запросов MySQL: <b>{QUERIES_USED}</b> | Время выполнения запросов: <b>{QUERIES_EXEC_TIME}</b> сек.</div>

<div class="versncopy">
{VERSION} &nbsp;&nbsp;&nbsp;&nbsp; {COPYRIGHT}
</div>

</div>
</div>

</body>
</html>