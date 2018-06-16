<!--
Шаблон для вывода варианта для обмена.
{NUMBER} - порядковый номер варианта
{id} - ид варианта
{TEXTAREA_CODE} - "безопасный" код для текстового поля
{FIELD_CODE} - код для обмена "как есть"
-->
<fieldset style="padding:2px; margin-bottom:10px;"><legend>Вариант {NUMBER}:</legend>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
  <td valign="top" width="22%" align="right">
  Короткое описание:<br></td>
   <td valign="top" width="78%" align="left"><textarea name="{id}" rows="2" class="link_text_field" style="width:99%;">{TEXTAREA_SH_NAME}</textarea></td>
   </tr>
  <tr> </tr>
 <tr>
  <td valign="top" width="22%" align="right">
  Код текстовой ссылки:<br></td>
  <td valign="top" width="78%" align="left"><textarea name="{id1}" rows="4" class="link_text_field" style="width:99%;">{TEXTAREA_CODE}</textarea></td>
 </tr>
  <tr>
  <td valign="top" width="22%" align="right">
  <div style="padding-left:5px;">Текстовое описание:<br>
  </div></td>
  <td valign="top" width="78%" align="left"><span style="padding-left:5px;">{FIELD_CODE}</span></td>
  </tr>
    <tr>
  <td valign="top" width="22%" align="right">
  Код баннера:<br></td>
  <td valign="top" width="78%" align="left"><textarea name="{id2}" rows="4" class="link_text_field" style="width:99%;">{TEXTAREA_BANNER}</textarea></td>
  </tr>
</table>
</fieldset>

