<?php
/**
 * @file
 * Template for the 1 column panel layout.
 *
 * This template provides a three column 25%-50%-25% panel display layout.
 *
 * Variables:
 * - $id: An optional CSS id to use for the layout.
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: Content in the left column.
 *   - $content['middle']: Content in the middle column.
 *   - $content['right']: Content in the right column.
 */
?>
<div class="container">
    <div class="row">
        <div class="panel-display panel-katalogbt1 col-sm-3 col-xs-12" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
            <div class="row">
    <?php print $content['left']; ?>
            </div>
        </div>
        <div class="panel-display panel-katalogbt1 col-sm-9 col-xs-12" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
            <div class="row">
    <?php print $content['righttop']; ?>
            </div>
        </div>
        <div class="panel-display panel-katalogbt1 col-sm-9 col-xs-12" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
            <div class="lpbann">
    <?php print $content['lpbann']; ?>
            </div>
        </div>
        <div class="panel-display panel-katalogbt1 col-sm-9 col-xs-12" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
            <div class="row">
    <?php print $content['right']; ?>
            </div>
        </div>
        <div class="panel-display panel-katalogbt1 col-xs-12" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
            <div class="row">
    <?php print $content['bottom']; ?>
            </div>
        </div>
        
    </div>
</div>