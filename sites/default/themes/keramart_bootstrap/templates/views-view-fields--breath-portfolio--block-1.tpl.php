<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>
<?php foreach ($fields as $id => $field): ?>
<?php /* echo  $id.'<br />'; */?>
<?php
if($id=='field_imagemain'){$kartinka=$field->content;}
if($id=='field_short_title'){$zagolovok=$field->content;}
if($id=='field_prise_groups_cost'){$csena=$field->content;}
if($id=='path'){$ssilka=$field->content;}
if($id=='field_artikul'){$articul=$field->content;}
$path = current_path();
$path_alias = drupal_lookup_path('alias',$path);
?>
<?php endforeach; ?>
<?php echo $kartinka; ?>
<div class="info_container">
    <div class="sub_title">
        <?php echo $zagolovok; ?>
        <?php echo '<span>Стоимость аналогичных работ<br>'.$csena.'</span>'; ?>
       <?php /* <a class="colorbox-node" href="zapros-ceny?width=300&height=470&arrowKey=false" onclick="yaCounter823781.reachGoal('PRFRMCLICK'); return true;">ЗАПРОС ЦЕНЫ</a> */ ?> 
        <a href="<?php echo $ssilka; ?>?width=90%25&height=90%25&maxWidth=1000&maxHeight=1200" 
           onclick="yaCounter823781.hit('<?php echo $ssilka; ?>', {
    title: '<?php echo $articul; ?>',
    referer: '/<?php echo $path_alias; ?>'
}); return true;"class="colorbox-node">УЗНАТЬ ПОДРОБНЕЕ</a>
    </div>
</div>