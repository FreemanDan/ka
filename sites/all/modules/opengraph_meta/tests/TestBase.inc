<?php
// $Id

module_load_include('inc','opengraph_meta','opengraph_meta.common');


/**
 * Base test class
 */
abstract class OGMTTestBase extends DrupalWebTestCase {

  protected  $ogm = NULL;
  protected  $ogm_objects_backup = NULL;

  protected  $ogm_data = NULL;
  protected  $ogm_settings = NULL;
  protected  $ogm_render = NULL;

  public function setUp() {
    parent::setUp();

    $this->ogm = OpenGraphMeta::instance();
    $this->ogm_settings = new OGMTestSettings();
    $this->ogm_objects_backup = $this->ogm->__get_objects();
    $this->ogm->__set_objects($this->ogm_settings);
  }

  public function tearDown() {
    $instance = OpenGraphMeta::instance();
    call_user_func_array(array(&$instance, '__set_objects'), $this->ogm_objects_backup);

    parent::tearDown();
  }

  protected function _build_test_node($nid) {
    $node = new stdClass();
    $node->nid = $nid;
    $node->title = 'test title';
    $node->type = 'type1';
    $this->_set_node_body($node, str_pad('',255,'ab'));
    return $node;
  }


  protected function _set_node_body(&$node, $body) {
    $node->body = array(LANGUAGE_NONE => array('0' => array('value' => $body)));
  }


  protected function _create_img_field($mime, $path) {
    return array('filemime' => $mime, 'uri' => $path);
  }
}


class OGMTestSettings implements OGMSettings {
  public $vars = array();
  public function get($name, $default) {
    return isset($this->vars[$name]) ? $this->vars[$name] : $default;
  }
  public function set($name, $value) {
    $this->vars[$name] = $value;
  }
}
