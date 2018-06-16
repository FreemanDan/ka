<?php
/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */
$actionnowtitle = variable_get_value('keramartmodule_actionnowtitle');
$actiontill = format_date(strtotime(variable_get_value('keramartmodule_actiondatetill')), 'lp');
$actionnow = variable_get_value('keramartmodule_actionnow');
if ($actionnow == '1' or $actionnow == '2'):
    ?>
    <span class="actionnow">Акция! <strong><?php echo $actionnowtitle; ?></strong> на изготовление фонтанов<br>
        действует до <strong><?php echo $actiontill; ?></strong>!</span>
<?php else: ?>
    <span class="plus">+</span>
Для наших клиентов до конца недели</br>
<span class="first-client">изготовление эскиза бесплатно!</span></br>
Экономия до <span class="prise-from-digit"><b>5 000</b></span> рублей <a class="colorbox-node" href="/node/650?width=600&amp;height=600&amp;arrowKey=false">Подробнее&gt;&gt;</a> <br/>
<?php endif; ?>