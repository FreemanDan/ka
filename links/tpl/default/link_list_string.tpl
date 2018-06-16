<!--
Шаблон строки с ссылкой
{FIELD_DOMAIN} - адрес сайта
{FIELD_NAME} - название ссылки
{FIELD_IMG_ADDR} - HTML код для картинки. Код генерится в программе. Это необходимо для формирования flash баннеров.
{FIELD_TEXT_HTML} - текст с ссылками
-->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
<tr>
 <td width="88" valign="top">{FIELD_IMG_ADDR}</td>
 <td align="left" valign="top"><div style="padding-left:3px;">
  <a href="http://{FIELD_DOMAIN}" target="blank"><b>{FIELD_NAME}</b></a><br>
  {FIELD_TEXT_HTML}
 </div></td>
</tr>
</table>