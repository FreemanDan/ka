<?php
/**
 * @file
 * features_partners.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function features_partners_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-partners-body'.
  $field_instances['node-partners-body'] = array(
    'bundle' => 'partners',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(
          'trim_length' => 600,
        ),
        'type' => 'text_summary_or_trimmed',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'body',
    'label' => 'Body',
    'required' => FALSE,
    'settings' => array(
      'display_summary' => TRUE,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'module' => 'text',
      'settings' => array(
        'rows' => 20,
        'summary_rows' => 5,
      ),
      'type' => 'text_textarea_with_summary',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'node-partners-field_gorod'.
  $field_instances['node-partners-field_gorod'] = array(
    'bundle' => 'partners',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 4,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_gorod',
    'label' => 'Город',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 8,
    ),
  );

  // Exported field_instance: 'node-partners-field_image'.
  $field_instances['node-partners-field_image'] = array(
    'bundle' => 'partners',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'image',
        'settings' => array(
          'image_link' => '',
          'image_style' => '',
        ),
        'type' => 'image',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_image',
    'label' => 'Логотип',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => 'partners',
      'file_extensions' => 'png gif jpg jpeg',
      'focus' => 0,
      'focus_lock_ratio' => 0,
      'focus_min_size' => '',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'image',
      'settings' => array(
        'adobe_creative_sdk_enabled' => 0,
        'adobe_creative_sdk_position' => 0,
        'aviary_feather_enabled' => 0,
        'aviary_feather_position' => 0,
        'deviantartmuro_enabled' => 0,
        'deviantartmuro_position' => 0,
        'filefield_sources' => array(
          'filefield_sources' => array(
            'attach' => 0,
            'clipboard' => 0,
            'imageeditor' => 0,
            'imce' => 0,
            'reference' => 0,
            'remote' => 0,
            'upload' => 'upload',
          ),
          'source_attach' => array(
            'absolute' => 0,
            'attach_mode' => 'move',
            'path' => 'file_attach',
          ),
          'source_imageeditor' => array(
            'source_imageeditor_image' => 0,
          ),
          'source_imce' => array(
            'imce_mode' => 0,
          ),
          'source_reference' => array(
            'autocomplete' => 0,
            'search_all_fields' => 0,
          ),
        ),
        'flauntr_styler_enabled' => 0,
        'flauntr_styler_position' => 0,
        'fotoflexer_enabled' => 0,
        'fotoflexer_position' => 0,
        'imageeditor_icons_position' => 'bottom',
        'imageeditor_replace' => 0,
        'imageshack_upload_enabled' => 0,
        'imageshack_upload_position' => 0,
        'immio_upload_enabled' => 0,
        'immio_upload_position' => 0,
        'paintweb_enabled' => 0,
        'paintweb_position' => 0,
        'picmonkey_enabled' => 0,
        'picmonkey_position' => 0,
        'picozu_enabled' => 0,
        'picozu_position' => 0,
        'pixlr_editor_enabled' => 0,
        'pixlr_editor_position' => 0,
        'pixlr_express_enabled' => 0,
        'pixlr_express_position' => 0,
        'pixlr_upload_enabled' => 0,
        'pixlr_upload_position' => 0,
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'throbber',
        'snipshot_enabled' => 0,
        'snipshot_position' => 0,
        'sumopaint_enabled' => 0,
        'sumopaint_position' => 0,
        'svgedit_enabled' => 0,
        'svgedit_position' => 0,
      ),
      'type' => 'image_image',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'node-partners-field_linc'.
  $field_instances['node-partners-field_linc'] = array(
    'bundle' => 'partners',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'link',
        'settings' => array(),
        'type' => 'link_default',
        'weight' => 2,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_linc',
    'label' => 'Сайт:',
    'required' => 0,
    'settings' => array(
      'absolute_url' => 1,
      'attributes' => array(
        'class' => '',
        'configurable_class' => 0,
        'configurable_title' => 0,
        'rel' => '',
        'target' => 'user',
        'title' => '',
      ),
      'display' => array(
        'url_cutoff' => 80,
      ),
      'enable_tokens' => 1,
      'rel_remove' => 'default',
      'title' => 'optional',
      'title_label_use_field_label' => 0,
      'title_maxlength' => 255,
      'title_value' => '',
      'url' => 0,
      'user_register_form' => FALSE,
      'validate_url' => 1,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'link',
      'settings' => array(),
      'type' => 'link_field',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'node-partners-field_weight'.
  $field_instances['node-partners-field_weight'] = array(
    'bundle' => 'partners',
    'default_value' => array(
      0 => array(
        'value' => 0,
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'number',
        'settings' => array(
          'decimal_separator' => '.',
          'prefix_suffix' => TRUE,
          'scale' => 0,
          'thousand_separator' => '',
        ),
        'type' => 'number_integer',
        'weight' => 3,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_weight',
    'label' => 'Вес',
    'required' => 0,
    'settings' => array(
      'range' => 20,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'weight',
      'settings' => array(
        'range' => 20,
      ),
      'type' => 'weight_selector',
      'weight' => 7,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Body');
  t('Вес');
  t('Город');
  t('Логотип');
  t('Сайт:');

  return $field_instances;
}
