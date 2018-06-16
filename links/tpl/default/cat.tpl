<!-- 
Шаблон вывода листа ссылок со списком каталогов в колонку.
{LINK_LIST} - список ссылок
{CAT_LIST}  - список каталогов в колонку
{PAGES_BAR}  - порядковые страницы (если есть)
{ADD_LINK} - ссылка для добавления новой ссылки
{EDIT_LINK} - ссылка на форму изменения данных ранее добавленной ссылки.
-->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td valign="top" width="70%">
  {LINK_LIST}
  </td>
  <td valign="top" width="30%">
   <div class="links_mini_cat_block" style="margin-left:4px;">{CAT_LIST}</div>
  </td>
 </tr>
</table>
{PAGES_BAR}
