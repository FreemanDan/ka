<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.
//форма редактирования ссылки. шаг 2
//поздравление о принятых изменениях.
//все данные передаются сюда в $post_item_data


$form_db=array();
$form_db['HEADER']='Изменения приняты';
$form_db['MESSAGE']='Спасибо. Изменения приняты. ';

//доп запись в сообщение.
$addr_to_link=links_get_link_addr ($post_item_data['item_db'], $links_settings_db);	//получаем полную ссылку на ссылку.. гыыыы... тафтология..
if ($post_item_data['item_db']['FIELD_ENABLE']==1) $form_db['MESSAGE'].='Ваша ссылка сейчас доступна по адресу <a href="'.$addr_to_link.'">'.$addr_to_link.'</a> <br><br>';
		else $form_db['MESSAGE'].='После проверки модератором Ваша ссылка будет доступна по адресу <a href="'.$addr_to_link.'">'.$addr_to_link.'</a> <br><br>';


//выбрасываем данные в поток.
$page_stream_db['CONTENT'].=si_ff_replace ($form_db, Root_Dir.'tpl/'.Use_Template.'/message.tpl', 1);	//заодно чистим шаблон от мусора.. (коментариев и пр.)

?>