<?php
/**
 * @file eva-display-entity-view.tpl.php
 * Entity content view template
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div id="ceni-uslugi">
    <?php /* if ($title): ?>
      <h2 class="title"><?php print $title; ?></h2>
      <?php endif; */ ?>
    <h2 class="title">Цены на услуги</h2>
    <?php if ($header): ?>
        <div class="view-header">Стоимость изготовления фонтана зависит от его конфигурации, наличия дополнительных декоративных элементов и размеров, поэтому всегда расчитывается индивидуально. Типовые варианты и цены приведены в таблице.
            <?php //print $header; ?>
        </div>
    <?php endif; ?>
    <?php if ($rows): ?>
        <?php print $rows; ?>
    <?php elseif ($empty): ?>
        <div class="view-empty">
            <?php print $empty; ?>
        </div>
    <?php endif; ?>
    <?php if ($footer): ?>
        <div class="view-footer">
            <?php
            $actionnowtitle = variable_get_value('keramartmodule_actionnowtitle');
            $actiontill = format_date(strtotime(variable_get_value('keramartmodule_actiondatetill')), 'lp');
            $actionnow = variable_get_value('keramartmodule_actionnow');
            if ($actionnow == '1' or $actionnow == '2'):
                ?>
                <span class="actionnow">* Обратите внимание, что указанные цены по акции- <b><?php echo $actionnowtitle; ?> на изготовление фонтанов</b> действуют до <strong><?php echo $actiontill; ?></strong>! После чего, действие акции заканчивается!</span>
            <?php else: ?>
        <?php endif; ?>
        <?php print $footer; ?>
        </div>
<?php endif; ?>
</div>
