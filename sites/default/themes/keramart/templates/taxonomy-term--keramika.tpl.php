<?php

/**
 * @file
 * Default theme implementation to display a term.
 *
 * Available variables:
 * - $name: the (sanitized) name of the term.
 * - $content: An array of items for the content of the term (fields and
 *   description). Use render($content) to print them all, or print a subset
 *   such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $term_url: Direct URL of the current term.
 * - $term_name: Name of the current term.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - taxonomy-term: The current template type, i.e., "theming hook".
 *   - vocabulary-[vocabulary-name]: The vocabulary to which the term belongs to.
 *     For example, if the term is a "Tag" it would result in "vocabulary-tag".
 *
 * Other variables:
 * - $term: Full term object. Contains data that may not be safe.
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $page: Flag for the full page state.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the term. Increments each time it's output.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_taxonomy_term()
 * @see template_process()
 *
 * @ingroup themeable
 */
 ?>
 
<?php //отключаю описание, если страница не первая
$flag_donotshowdescr = 0;
if ( !empty($_GET['page'])) {
$flag_donotshowdescr = 1;
} 
//отключаем показ блока со спецпредложением для некоторых категорий
$flag_donotshowblock = 0;
$desired_terms = array(92, 85);
if ( ((arg(0) == 'taxonomy')
     && (arg(1) == 'term')
     && is_numeric(arg(2))
     && in_array(arg(2), $desired_terms))) {
 $flag_donotshowblock = 1;
  }
?>

<div id="taxonomy-term-<?php print $term->tid; ?>" class="<?php print $classes; ?>">

  <?php if (!$page): ?>
    <h2><a href="<?php print $term_url; ?>"><?php print $term_name; ?></a></h2>
  <?php endif; ?>

  <div class="content">
    <?php /* print render($content); */ ?>
	<?php print render($content['field_tax_main_list_desc']); ?>
<?php
if ($flag_donotshowblock == 0) {
$module_name = 'webform';   // - имя модуля, который отвечает за реализацию блока.      
$block_delta = 'client-block-635';  // - уникальный идентификатор блока в пределах модуля.
$block = block_load($module_name, $block_delta);
$block_to_render = _block_get_renderable_array( _block_render_blocks( array($block) ));
print render($block_to_render);
}
?> 
      
	<?php print render($content['kategorii_entity_view_1']); ?>
      <?php if ($flag_donotshowdescr == 0): ?>
      <?php if (isset($content['description'])): ?>
        <?php print token_replace('[custom:promo-splitter]'); ?>
      <?php endif; ?>
      
      
	<?php print render($content['description']); ?>
      <?php endif; ?>
	<?php print render($content['field_keramic_help']); ?>
  </div>

</div>
