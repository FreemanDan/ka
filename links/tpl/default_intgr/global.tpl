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
<link href="{WWW_ROOT}tpl/{TEMPLATE}/css/styles.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript"  src="{WWW_ROOT}tpl/{TEMPLATE}/js/fl.js"></script>

<div align="center" class="link_global">
<div><h1>{PAGE_NAME}</h1></div>
{CONTENT}
{SYSTEM_MENU}



<!-- <div class="links_exec_info">Общее время: <b>{TOTAL_EXEC_TIME}</b> сек. | Запросов MySQL: <b>{QUERIES_USED}</b> | Время выполнения запросов: <b>{QUERIES_EXEC_TIME}</b> сек.</div>

<div class="versncopy">
{VERSION} &nbsp;&nbsp;&nbsp;&nbsp; {COPYRIGHT}
</div> -->

</div>

