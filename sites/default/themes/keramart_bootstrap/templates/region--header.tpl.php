<?php

/**
 * @file
 * Default theme implementation to display a region.
 *
 * Available variables:
 * - $content: The content for this region, typically blocks.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - region: The current template type, i.e., "theming hook".
 *   - region-[name]: The name of the region with underscores replaced with
 *     dashes. For example, the page_top region would have a region-page-top class.
 * - $region: The name of the region variable as defined in the theme's .info file.
 *
 * Helper variables:
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $is_admin: Flags true when the current user is an administrator.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 *
 * @see template_preprocess()
 * @see template_preprocess_region()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<?php if ($content): ?>
<div class="col-xs-12 hidden-xs regionheader <?php print $classes; ?>">
  <div class="regionheader__logosloganwrapper pull-left">
    <a class="logo pull-left" href="<?php print url(); ?>" title="<?php print t('Home');?>"></a>
    <div class="regionheader__logosloganwrapper1 pull-left">
      <div class="regionheader__logo1"></div>
      <div class="regionheader__slogan">
        <?php print $content; ?>
      </div>
    </div>
  </div>
  <a class="callbackbutton callback-form btn btn-default visible-lg-block pull-right" href="http://keramart/cbox/zakaz"
    onclick="yaCounter823781.reachGoal('CALLBACKCLICK'); return true;">Закажите
    звонок</a>
  <div class="regionheader_phonewrapper pull-right">
    <div class="phone">8 (351) 2150062</div>
    <div class="regionheader__phonerf">Работаем по всей РФ!</div>
  </div>


</div>
<?php endif; ?>
