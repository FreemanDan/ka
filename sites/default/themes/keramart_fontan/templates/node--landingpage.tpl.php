<?php
/**
 * @file
 * Zen theme's implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   - view-mode-[mode]: The view mode, e.g. 'full', 'teaser'...
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 *   The following applies only to viewers who are registered users:
 *   - node-by-viewer: Node is authored by the user currently viewing the page.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $pubdate: Formatted date and time for when the node was published wrapped
 *   in a HTML5 time element.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content. Currently broken; see http://drupal.org/node/823380
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see zen_preprocess_node()
 * @see template_process()
 */
 ?>
<?php // Добавляю библиотеку, для раскрытия блока с ценами при нажатии на заголовок "Цены" ?>
<?php drupal_add_library('system', 'drupal.collapse');
	drupal_add_css('drupal.css', array('group' => CSS_SYSTEM));
 ?>
<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if ($title_prefix || $title_suffix || $display_submitted || $unpublished || !$page && $title): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if (!$page && $title): ?>
        <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>

      <?php if ($display_submitted): ?>
        <p class="submitted">
          <?php print $user_picture; ?>
          <?php print $submitted; ?>
        </p>
      <?php endif; ?>

      <?php if ($unpublished): ?>
        <p class="unpublished"><?php print t('Unpublished'); ?></p>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
	?>
    <?php print render($content['field_promo_description']); ?>
	
	<div class="content-75-left">
	<?php //Печатаем картинку
	 print render($content['field_spec_pict']); ?>
	</div>
	<div class="content-25-right">
	<div class="cpecial-block">
	<div id="lp-arrow-img">
	<?php print render($content['field_tiser_description']); ?>
	</div>
	<div class="lp-promo-heading">Отправьте заявку</div>
	<?php //Печатаем текст спецпредложения
	 print render($content['field_special_1_txt']); ?>
	
	<?php //Печатаем форму акции
        $action_form =  node_view(node_load(635), 'full', NULL);
	print strip_tags(render($action_form), '<div><form><input>');
	?>
	<div class="primechanie-font">* ваши контактные данные в безопасности и не будут переданы третьим лицам</div>
	</div>
	</div>
	<div class="clearfix"></div>
	<?php print token_replace('[custom:triggers-numbers]'); ?>
        <?php print token_replace('[custom:promo-splitter]'); ?>
	<div class="clearfix"></div>
	<?php //Печатаем блок с вьюсами примеры работ
	print views_embed_view('lp_ou_works', 'block'); ?>
	<p>&nbsp</p>
	
	<?php
	$module_name = 'block';   // - имя модуля, который отвечает за реализацию блока.      
	$block_delta = '10';  // - уникальный идентификатор блока в пределах модуля.
	$block = block_load($module_name, $block_delta);
        $block_to_render = _block_get_renderable_array( _block_render_blocks( array($block) ));
	print render($block_to_render);
	
	?>
	<?php print token_replace('[custom:promo-splitter]'); ?>
	<?php // print render($content['field_taxonomy_item']); ?>
	<?php //print token_replace('[custom:order-form-big-prise]'); ?>
	<div class="content-splitter"></div>
	
	<?php print render($content['body']); ?>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</article><!-- /.node -->
