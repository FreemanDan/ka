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
<div class="panel-display panel-katalog lpbann clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
    <div class="content-inner">
    <?php print $content['bann']; ?>
    </div>
</div>
<div class="panel-display panel-katalog clearfix lptopform content-inner" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
<?php print $content['lptopform']; ?>
</div>
<div class="panel-display panel-katalog clearfix lpcontent" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
<?php print $content['middle']; ?>
</div>
