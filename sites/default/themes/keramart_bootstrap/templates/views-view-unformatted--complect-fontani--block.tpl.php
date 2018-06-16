<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php foreach ($rows as $id => $row): ?>
<div class="col-xs-12 col-md-6 col-sm-6 col-lg-4">
    <?php print $row; ?>
  </div>
<?php endforeach; ?>
