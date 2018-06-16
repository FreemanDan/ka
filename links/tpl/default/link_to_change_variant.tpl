<!--
Шаблон для вывода варианта для обмена.
{NUMBER} - порядковый номер варианта
{id} - ид варианта
{TEXTAREA_CODE} - "безопасный" код для текстового поля
{FIELD_CODE} - код для обмена "как есть"
-->
<fieldset style="padding:2px; margin-bottom:10px;"><legend>Вариант {NUMBER}</legend>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td valign="top" width="40%" align="left"><textarea name="{id}" rows="6" class="link_text_field" style="width:99%;">{TEXTAREA_CODE}</textarea></td>
  <td valign="top" width="60%" align="left"><div style="padding-left:5px;">{FIELD_CODE}</div></td>
 </tr>
</table>
</fieldset>
