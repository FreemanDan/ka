<?php
/**
 * @file
 * portfolio.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function portfolio_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'breath_portfolio';
  $view->description = 'Для отображения списка изделий из определенной категории (LP-фонтаны page-node-680 , скульпт. портрет)';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Портфолио - карусель';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Примеры работ в жанре "Скульптурный портрет"';
  $handler->display->display_options['css_class'] = 'lategoria';
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_text'] = 'ещё';
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Применить';
  $handler->display->display_options['exposed_form']['options']['reset_button_label'] = 'Сбросить';
  $handler->display->display_options['exposed_form']['options']['exposed_sorts_label'] = 'Сортировать по';
  $handler->display->display_options['exposed_form']['options']['sort_asc_label'] = 'По возрастанию';
  $handler->display->display_options['exposed_form']['options']['sort_desc_label'] = 'По убыванию';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '8';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'jcarousel';
  $handler->display->display_options['style_options']['wrap'] = '0';
  $handler->display->display_options['style_options']['visible'] = '';
  $handler->display->display_options['style_options']['responsive'] = 1;
  $handler->display->display_options['style_options']['auto'] = '0';
  $handler->display->display_options['style_options']['autoPause'] = 1;
  $handler->display->display_options['style_options']['easing'] = '';
  $handler->display->display_options['style_options']['vertical'] = 0;
  $handler->display->display_options['style_options']['ajax'] = 1;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['default_field_elements'] = FALSE;
  /* Шапка: Глобальный: Текстовое поле */
  $handler->display->display_options['header']['area']['id'] = 'area';
  $handler->display->display_options['header']['area']['table'] = 'views';
  $handler->display->display_options['header']['area']['field'] = 'area';
  $handler->display->display_options['header']['area']['format'] = 'full_html';
  /* Связь: Содержимое: Ценовая группа (field_item_prise_group) */
  $handler->display->display_options['relationships']['field_item_prise_group_tid']['id'] = 'field_item_prise_group_tid';
  $handler->display->display_options['relationships']['field_item_prise_group_tid']['table'] = 'field_data_field_item_prise_group';
  $handler->display->display_options['relationships']['field_item_prise_group_tid']['field'] = 'field_item_prise_group_tid';
  /* Поле: Содержимое: Фото */
  $handler->display->display_options['fields']['field_imagemain']['id'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['table'] = 'field_data_field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['field'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['label'] = '';
  $handler->display->display_options['fields']['field_imagemain']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_imagemain']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_imagemain']['type'] = 'colorbox';
  $handler->display->display_options['fields']['field_imagemain']['settings'] = array(
    'colorbox_node_style' => 'katalog-w280-copy',
    'colorbox_image_style' => 'katalog-large-copy',
    'colorbox_gallery' => 'none',
    'colorbox_gallery_custom' => '',
    'colorbox_caption' => 'auto',
    'colorbox_caption_custom' => '',
  );
  $handler->display->display_options['fields']['field_imagemain']['delta_limit'] = '1';
  $handler->display->display_options['fields']['field_imagemain']['delta_offset'] = '0';
  /* Поле: Поле: Должность */
  $handler->display->display_options['fields']['field_short_title']['id'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['table'] = 'field_data_field_short_title';
  $handler->display->display_options['fields']['field_short_title']['field'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['label'] = '';
  $handler->display->display_options['fields']['field_short_title']['alter']['path'] = '[view_node]';
  $handler->display->display_options['fields']['field_short_title']['alter']['absolute'] = TRUE;
  $handler->display->display_options['fields']['field_short_title']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Ссылка */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['label'] = '';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['text'] = '[field_short_title]';
  $handler->display->display_options['fields']['view_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_width'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_height'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_rel'] = '';
  /* Поле: Поле: Cтоимость */
  $handler->display->display_options['fields']['field_prise_groups_cost']['id'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['table'] = 'field_data_field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['field'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['relationship'] = 'field_item_prise_group_tid';
  $handler->display->display_options['fields']['field_prise_groups_cost']['label'] = '';
  $handler->display->display_options['fields']['field_prise_groups_cost']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_type'] = 'p';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_class'] = 'spisokizd-prise-heading';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['type'] = 'default';
  /* Поле: Содержимое: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['alter']['trim_whitespace'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['max_length'] = '100';
  $handler->display->display_options['fields']['body']['alter']['more_link_text'] = 'Подробнее';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['trim'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['html'] = TRUE;
  $handler->display->display_options['fields']['body']['element_type'] = 'p';
  $handler->display->display_options['fields']['body']['element_class'] = 'spisokisdelii-desc-field';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '125',
  );
  /* Поле: Термин таксономии: Подстановка в текст спецпредложения */
  $handler->display->display_options['fields']['field_special_1_txt']['id'] = 'field_special_1_txt';
  $handler->display->display_options['fields']['field_special_1_txt']['table'] = 'field_data_field_special_1_txt';
  $handler->display->display_options['fields']['field_special_1_txt']['field'] = 'field_special_1_txt';
  $handler->display->display_options['fields']['field_special_1_txt']['relationship'] = 'field_item_prise_group_tid';
  $handler->display->display_options['fields']['field_special_1_txt']['label'] = '';
  $handler->display->display_options['fields']['field_special_1_txt']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_special_1_txt']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_special_1_txt']['alter']['text'] = 'Идеи [field_special_1_txt]';
  $handler->display->display_options['fields']['field_special_1_txt']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Номер образца */
  $handler->display->display_options['fields']['field_artikul']['id'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['table'] = 'field_data_field_artikul';
  $handler->display->display_options['fields']['field_artikul']['field'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['label'] = '№';
  $handler->display->display_options['fields']['field_artikul']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_artikul']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_artikul']['element_label_type'] = 'span';
  /* Критерий сортировки: Поле: Вес аккаунта (field_weight) */
  $handler->display->display_options['sorts']['field_weight_value']['id'] = 'field_weight_value';
  $handler->display->display_options['sorts']['field_weight_value']['table'] = 'field_data_field_weight';
  $handler->display->display_options['sorts']['field_weight_value']['field'] = 'field_weight_value';
  $handler->display->display_options['sorts']['field_weight_value']['order'] = 'DESC';
  /* Контекстный фильтр: Содержимое: Имеет идентификатор термина таксономии */
  $handler->display->display_options['arguments']['tid']['id'] = 'tid';
  $handler->display->display_options['arguments']['tid']['table'] = 'taxonomy_index';
  $handler->display->display_options['arguments']['tid']['field'] = 'tid';
  $handler->display->display_options['arguments']['tid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['tid']['exception']['title'] = 'Все';
  $handler->display->display_options['arguments']['tid']['default_argument_type'] = 'taxonomy_tid';
  $handler->display->display_options['arguments']['tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['tid']['summary_options']['items_per_page'] = '25';
  /* Критерий фильтра: Содержимое: Опубликовано */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Критерий фильтра: Содержимое: Тип */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'portfolioitem' => 'portfolioitem',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;

  /* Display: Content pane портфолио */
  $handler = $view->new_display('panel_pane', 'Content pane портфолио', 'panel_pane_2');
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Поле: Содержимое: Фото */
  $handler->display->display_options['fields']['field_imagemain']['id'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['table'] = 'field_data_field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['field'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['label'] = '';
  $handler->display->display_options['fields']['field_imagemain']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_imagemain']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_imagemain']['type'] = 'colorbox';
  $handler->display->display_options['fields']['field_imagemain']['settings'] = array(
    'colorbox_node_style' => 'katalog-w280-copy',
    'colorbox_image_style' => 'katalog-large-copy',
    'colorbox_gallery' => 'none',
    'colorbox_gallery_custom' => '',
    'colorbox_caption' => 'auto',
    'colorbox_caption_custom' => '',
  );
  $handler->display->display_options['fields']['field_imagemain']['delta_limit'] = '1';
  $handler->display->display_options['fields']['field_imagemain']['delta_offset'] = '0';
  /* Поле: Поле: Должность */
  $handler->display->display_options['fields']['field_short_title']['id'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['table'] = 'field_data_field_short_title';
  $handler->display->display_options['fields']['field_short_title']['field'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['label'] = '';
  $handler->display->display_options['fields']['field_short_title']['alter']['path'] = '[view_node]';
  $handler->display->display_options['fields']['field_short_title']['alter']['absolute'] = TRUE;
  $handler->display->display_options['fields']['field_short_title']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Ссылка */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['label'] = '';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['text'] = '[field_short_title]';
  $handler->display->display_options['fields']['view_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_width'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_height'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_rel'] = '';
  /* Поле: Поле: Cтоимость */
  $handler->display->display_options['fields']['field_prise_groups_cost']['id'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['table'] = 'field_data_field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['field'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['relationship'] = 'field_item_prise_group_tid';
  $handler->display->display_options['fields']['field_prise_groups_cost']['label'] = '';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_type'] = 'p';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_class'] = 'spisokizd-prise-heading';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['type'] = 'default';
  /* Поле: Содержимое: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['alter']['trim_whitespace'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['max_length'] = '100';
  $handler->display->display_options['fields']['body']['alter']['more_link_text'] = 'Подробнее';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['trim'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['html'] = TRUE;
  $handler->display->display_options['fields']['body']['element_type'] = 'p';
  $handler->display->display_options['fields']['body']['element_class'] = 'spisokisdelii-desc-field';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '125',
  );
  /* Поле: Содержимое: Номер образца */
  $handler->display->display_options['fields']['field_artikul']['id'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['table'] = 'field_data_field_artikul';
  $handler->display->display_options['fields']['field_artikul']['field'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['label'] = '';
  $handler->display->display_options['fields']['field_artikul']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_artikul']['element_label_colon'] = FALSE;
  $handler->display->display_options['defaults']['sorts'] = FALSE;

  /* Display: Блок */
  $handler = $view->new_display('block', 'Блок', 'block_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['defaults']['exposed_form'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Применить';
  $handler->display->display_options['exposed_form']['options']['reset_button_label'] = 'Сбросить';
  $handler->display->display_options['exposed_form']['options']['exposed_sorts_label'] = 'Сортировать по';
  $handler->display->display_options['exposed_form']['options']['expose_sort_order'] = FALSE;
  $handler->display->display_options['exposed_form']['options']['sort_asc_label'] = 'По возрастанию';
  $handler->display->display_options['exposed_form']['options']['sort_desc_label'] = 'По убыванию';
  $handler->display->display_options['exposed_form']['options']['autosubmit'] = TRUE;
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'allow_secondary' => 0,
      'secondary_label' => 'Дополнительные настройки',
    ),
    'field_taxonomy_item_tid' => array(
      'bef_format' => 'bef_ul',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
  );
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '8';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '5';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_label'] = 'Элементов на страницу';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all_label'] = '- Все -';
  $handler->display->display_options['pager']['options']['expose']['offset_label'] = 'Пропустить';
  $handler->display->display_options['pager']['options']['tags']['first'] = '« первая';
  $handler->display->display_options['pager']['options']['tags']['previous'] = 'назад';
  $handler->display->display_options['pager']['options']['tags']['next'] = 'вперед';
  $handler->display->display_options['pager']['options']['tags']['last'] = 'последняя »';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['row_class'] = 'portfolio-item col-xs-6 col-md-3';
  $handler->display->display_options['style_options']['default_row_class'] = FALSE;
  $handler->display->display_options['style_options']['row_class_special'] = FALSE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['default_field_elements'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Поле: Содержимое: Фото */
  $handler->display->display_options['fields']['field_imagemain']['id'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['table'] = 'field_data_field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['field'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['label'] = '';
  $handler->display->display_options['fields']['field_imagemain']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_imagemain']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_imagemain']['settings'] = array(
    'image_style' => 'portfolio-315x409',
    'image_link' => '',
  );
  $handler->display->display_options['fields']['field_imagemain']['delta_limit'] = '1';
  $handler->display->display_options['fields']['field_imagemain']['delta_offset'] = '0';
  /* Поле: короткий заголовок */
  $handler->display->display_options['fields']['field_short_title']['id'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['table'] = 'field_data_field_short_title';
  $handler->display->display_options['fields']['field_short_title']['field'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['ui_name'] = 'короткий заголовок';
  $handler->display->display_options['fields']['field_short_title']['label'] = '';
  $handler->display->display_options['fields']['field_short_title']['alter']['path'] = '[view_node]';
  $handler->display->display_options['fields']['field_short_title']['alter']['absolute'] = TRUE;
  $handler->display->display_options['fields']['field_short_title']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Ссылка */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['label'] = '';
  $handler->display->display_options['fields']['view_node']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['text'] = '[field_short_title]';
  $handler->display->display_options['fields']['view_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_width'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_height'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_rel'] = '';
  /* Поле: Поле: Cтоимость */
  $handler->display->display_options['fields']['field_prise_groups_cost']['id'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['table'] = 'field_data_field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['field'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['relationship'] = 'field_item_prise_group_tid';
  $handler->display->display_options['fields']['field_prise_groups_cost']['label'] = '';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['type'] = 'default';
  /* Поле: Содержимое: таксономия изделия */
  $handler->display->display_options['fields']['field_taxonomy_item']['id'] = 'field_taxonomy_item';
  $handler->display->display_options['fields']['field_taxonomy_item']['table'] = 'field_data_field_taxonomy_item';
  $handler->display->display_options['fields']['field_taxonomy_item']['field'] = 'field_taxonomy_item';
  $handler->display->display_options['fields']['field_taxonomy_item']['label'] = '';
  $handler->display->display_options['fields']['field_taxonomy_item']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_taxonomy_item']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_taxonomy_item']['delta_offset'] = '0';
  /* Поле: Глобальный: Пользовательский текст */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = '';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<div class="info_container">
                <div class="sub_title">
                  [field_short_title]
                  <span>[field_prise_groups_cost] </span>
                  <a href="[view_node]" tabindex="0">УЗНАТЬ ПОДРОБНЕЕ</a>
                </div>
              </div>';
  $handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Путь */
  $handler->display->display_options['fields']['path']['id'] = 'path';
  $handler->display->display_options['fields']['path']['table'] = 'node';
  $handler->display->display_options['fields']['path']['field'] = 'path';
  $handler->display->display_options['fields']['path']['label'] = '';
  $handler->display->display_options['fields']['path']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Номер образца */
  $handler->display->display_options['fields']['field_artikul']['id'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['table'] = 'field_data_field_artikul';
  $handler->display->display_options['fields']['field_artikul']['field'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['label'] = '';
  $handler->display->display_options['fields']['field_artikul']['element_label_colon'] = FALSE;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Контекстный фильтр: Содержимое: Имеет идентификатор термина таксономии (с глубиной) */
  $handler->display->display_options['arguments']['term_node_tid_depth']['id'] = 'term_node_tid_depth';
  $handler->display->display_options['arguments']['term_node_tid_depth']['table'] = 'node';
  $handler->display->display_options['arguments']['term_node_tid_depth']['field'] = 'term_node_tid_depth';
  $handler->display->display_options['arguments']['term_node_tid_depth']['default_action'] = 'default';
  $handler->display->display_options['arguments']['term_node_tid_depth']['exception']['title'] = 'Все';
  $handler->display->display_options['arguments']['term_node_tid_depth']['default_argument_type'] = 'taxonomy_tid';
  $handler->display->display_options['arguments']['term_node_tid_depth']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['term_node_tid_depth']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['term_node_tid_depth']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['term_node_tid_depth']['depth'] = '2';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Критерий фильтра: Содержимое: Опубликовано */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Критерий фильтра: Содержимое: Тип */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'portfolioitem' => 'portfolioitem',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Критерий фильтра: Содержимое: таксономия изделия (field_taxonomy_item) */
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['id'] = 'field_taxonomy_item_tid';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['table'] = 'field_data_field_taxonomy_item';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['field'] = 'field_taxonomy_item_tid';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['expose']['operator_id'] = 'field_taxonomy_item_tid_op';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['expose']['label'] = 'таксономия изделия (field_taxonomy_item)';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['expose']['operator'] = 'field_taxonomy_item_tid_op';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['expose']['identifier'] = 'field_taxonomy_item_tid';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['is_grouped'] = TRUE;
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['group_info']['identifier'] = 'field_taxonomy_item_tid';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['group_info']['group_items'] = array(
    1 => array(
      'title' => 'Напольные',
      'operator' => 'or',
      'value' => array(
        64 => '64',
      ),
    ),
    2 => array(
      'title' => 'Декоративные водопады',
      'operator' => 'or',
      'value' => array(
        65 => '65',
      ),
    ),
    3 => array(
      'title' => 'Настольные фонтаны',
      'operator' => 'or',
      'value' => array(
        66 => '66',
      ),
    ),
  );
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_taxonomy_item_tid']['vocabulary'] = 'keramika';

  /* Display: Блок-портрет */
  $handler = $view->new_display('block', 'Блок-портрет', 'block_2');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'jcarousel';
  $handler->display->display_options['style_options']['wrap'] = '0';
  $handler->display->display_options['style_options']['skin'] = '';
  $handler->display->display_options['style_options']['visible'] = '';
  $handler->display->display_options['style_options']['responsive'] = 1;
  $handler->display->display_options['style_options']['auto'] = '0';
  $handler->display->display_options['style_options']['autoPause'] = 1;
  $handler->display->display_options['style_options']['easing'] = '';
  $handler->display->display_options['style_options']['vertical'] = 0;
  $handler->display->display_options['style_options']['ajax'] = 1;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['default_field_elements'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Поле: Содержимое: Фото */
  $handler->display->display_options['fields']['field_imagemain']['id'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['table'] = 'field_data_field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['field'] = 'field_imagemain';
  $handler->display->display_options['fields']['field_imagemain']['label'] = '';
  $handler->display->display_options['fields']['field_imagemain']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_imagemain']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_imagemain']['type'] = 'colorbox';
  $handler->display->display_options['fields']['field_imagemain']['settings'] = array(
    'colorbox_node_style' => 'katalog-w280-copy',
    'colorbox_image_style' => 'katalog-large-copy',
    'colorbox_gallery' => 'none',
    'colorbox_gallery_custom' => '',
    'colorbox_caption' => 'auto',
    'colorbox_caption_custom' => '',
  );
  $handler->display->display_options['fields']['field_imagemain']['delta_limit'] = '1';
  $handler->display->display_options['fields']['field_imagemain']['delta_offset'] = '0';
  /* Поле: Поле: Должность */
  $handler->display->display_options['fields']['field_short_title']['id'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['table'] = 'field_data_field_short_title';
  $handler->display->display_options['fields']['field_short_title']['field'] = 'field_short_title';
  $handler->display->display_options['fields']['field_short_title']['label'] = '';
  $handler->display->display_options['fields']['field_short_title']['alter']['path'] = '[view_node]';
  $handler->display->display_options['fields']['field_short_title']['alter']['absolute'] = TRUE;
  $handler->display->display_options['fields']['field_short_title']['element_label_colon'] = FALSE;
  /* Поле: Содержимое: Ссылка */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['label'] = '';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['text'] = '[field_short_title]';
  $handler->display->display_options['fields']['view_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_width'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_height'] = '600';
  $handler->display->display_options['fields']['view_node']['node_in_colorbox_rel'] = '';
  /* Поле: Поле: Cтоимость */
  $handler->display->display_options['fields']['field_prise_groups_cost']['id'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['table'] = 'field_data_field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['field'] = 'field_prise_groups_cost';
  $handler->display->display_options['fields']['field_prise_groups_cost']['relationship'] = 'field_item_prise_group_tid';
  $handler->display->display_options['fields']['field_prise_groups_cost']['label'] = '';
  $handler->display->display_options['fields']['field_prise_groups_cost']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_type'] = 'p';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_class'] = 'spisokizd-prise-heading';
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_prise_groups_cost']['type'] = 'default';
  /* Поле: Содержимое: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['alter']['trim_whitespace'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['max_length'] = '100';
  $handler->display->display_options['fields']['body']['alter']['more_link_text'] = 'Подробнее';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['trim'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['html'] = TRUE;
  $handler->display->display_options['fields']['body']['element_type'] = 'p';
  $handler->display->display_options['fields']['body']['element_class'] = 'spisokisdelii-desc-field';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '125',
  );
  /* Поле: Содержимое: Номер образца */
  $handler->display->display_options['fields']['field_artikul']['id'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['table'] = 'field_data_field_artikul';
  $handler->display->display_options['fields']['field_artikul']['field'] = 'field_artikul';
  $handler->display->display_options['fields']['field_artikul']['label'] = '№';
  $handler->display->display_options['fields']['field_artikul']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_artikul']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_artikul']['element_label_type'] = 'span';
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  $translatables['breath_portfolio'] = array(
    t('Master'),
    t('Примеры работ в жанре "Скульптурный портрет"'),
    t('ещё'),
    t('Применить'),
    t('Сбросить'),
    t('Сортировать по'),
    t('По возрастанию'),
    t('По убыванию'),
    t('термин из field_item_prise_group'),
    t('[field_short_title]'),
    t('Подробнее'),
    t('Идеи [field_special_1_txt]'),
    t('№'),
    t('Все'),
    t('Content pane портфолио'),
    t('View panes'),
    t('Блок'),
    t('Дополнительные настройки'),
    t('Элементов на страницу'),
    t('- Все -'),
    t('Пропустить'),
    t('« первая'),
    t('назад'),
    t('вперед'),
    t('последняя »'),
    t('<div class="info_container">
                <div class="sub_title">
                  [field_short_title]
                  <span>[field_prise_groups_cost] </span>
                  <a href="[view_node]" tabindex="0">УЗНАТЬ ПОДРОБНЕЕ</a>
                </div>
              </div>'),
    t('таксономия изделия (field_taxonomy_item)'),
    t('Блок-портрет'),
  );
  $export['breath_portfolio'] = $view;

  return $export;
}
