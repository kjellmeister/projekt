<!doctype html>
<html lang='en'> 
<head>
  <meta charset='utf-8'/>
  <title><?=$title?></title>
   <link rel='shortcut icon' href='<?=$favicon?>'/>
  <link rel='stylesheet' href='<?=theme_url($stylesheet)?>'/>
  <?php if(isset($inline_style)): ?><style><?=$inline_style?></style><?php endif; ?>
</head>
<body>

<div id='outer-wrap-header'>
  <div id='inner-wrap-header'>
    <div id='header'>
      <div id='login-menu'><?=login_menu()?></div>
      <div id='banner'>
        <img src="<?=$logo?>" alt="logo"/>
      </div>
      <?= getNavBar() ?>
    </div>
  </div>
</div>
<?php if(region_has_content('flash')): ?>
<div id='outer-wrap-flash'>
  <div id='inner-wrap-flash'>
    <div id='flash'><?=render_views('flash')?></div>
  </div>
</div>
<?php endif; ?>

<?php if(region_has_content('featured-first', 'featured-middle', 'featured-last')): ?>
<div id='outer-wrap-featured'>
  <div id='inner-wrap-featured'>
    <div id='featured-first'><?=render_views('featured-first')?></div>
    <div id='featured-middle'><?=render_views('featured-middle')?></div>
    <div id='featured-last'><?=render_views('featured-last')?></div>
  </div>
</div>
<?php endif; ?>

<div id='outer-wrap-main'>
  <div id='inner-wrap-main'>
    <div id='primary'><?=get_messages_from_session()?><?=@$main?><?=render_views('primary')?><?=render_views()?></div>
    <div id='sidebar'><?=render_views('sidebar')?></div>
  </div>
</div>

<?php if(region_has_content('triptych-first', 'triptych-middle', 'triptych-last')): ?>
<div id='outer-wrap-triptych'>
  <div id='inner-wrap-triptych'>
    <div id='triptych-first'><?=render_views('triptych-first')?></div>
    <div id='triptych-middle'><?=render_views('triptych-middle')?></div>
    <div id='triptych-last'><?=render_views('triptych-last')?></div>
  </div>
</div>
<?php endif; ?>



<div id='outer-wrap-footer'>
  <?php if(region_has_content('footer-column-one', 'footer-column-two', 'footer-column-three', 'footer-column-four')): ?>
  <div id='inner-wrap-footer-column'>
    <div id='footer-column-one'><?=render_views('footer-column-one')?></div>
    <div id='footer-column-two'><?=render_views('footer-column-two')?></div>
    <div id='footer-column-three'><?=render_views('footer-column-three')?></div>
    <div id='footer-column-four'><?=render_views('footer-column-four')?></div>
  </div>
  <?php endif; ?>
  <div id='inner-wrap-footer'>
   <div id='footer'><?=$footer?><?=get_debug()?>
    <p>Page was loaded in <?=round(microtime(true) - $ly->timer['first'], 5)*1000?> msecs. Steffe is fast as lightning.</p>
  </div>
</div>

</body>
</html>

