<?
//комплект функций для диалога проверки ссылки через Яндекс

//=========================================================
function links_get_ya_ret_link ($this_site, $partner_site)
{
//функция генерит ссылку для просмотра обратки в яндексе
//$this_site - относительно какого сайта ищем (этот сайт)
//$partner_site - сайт партнёра на котором ищем ссылку.

$this_site=links_get_clear_domain ($this_site);
$partner_site=links_get_clear_domain ($partner_site);

//формируем строку запроса:
$this_site=str_replace ('www.', '', $this_site);	//этот сайт без www.
$partner_site=str_replace ('www.', '', lecho ($partner_site));			//удалённый сайт без www.
$ya_link='http://www.yandex.ru/yandsearch?surl='.$partner_site.'&Link=www.'.$this_site.','.$this_site.',www.'.$this_site;


return $ya_link;
}
//=========================================================
function links_get_ya_ret ($this_site, $partner_site)
{
//функция возвращает массив страниц содержащих обратную ссылку, найденных через Яндекс.
//$this_site - относительно какого сайта ищем (этот сайт)
//$partner_site - сайт партнёра на котором ищем ссылку.

$ex_db=array();

$html=links_get_remote_page (links_get_ya_ret_link ($this_site, $partner_site));
if ($html['err']) return $ex_db;

$html_link_db=links_get_all_href_from_html ($html['html']);	//выдираем оттуда все ссылки.
//print_ar ($html_link_db);

//перебираем все ссылки и заносим в массив только те, что ведут на сайт $partner_site
$partner_site=links_get_clear_domain ($partner_site);
for ($i=0; $i<count($html_link_db); $i++)
{
$tmp_link=links_get_clear_domain ($html_link_db[$i]);
if (links_ravno_links ($tmp_link, $partner_site)) $ex_db[]=$html_link_db[$i];	//да, это ссылка ведёт на сайт партнёров.. добавляем.
}

return $ex_db;
}
//=========================================================
?>