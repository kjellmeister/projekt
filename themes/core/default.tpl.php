<!doctype html>
<html lang="sv"> 
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
<div id='header'>
<div id='login-menu'>
        <?=login_menu()?>
</div>
<img style="margin:0"src="/~jokr11/PHPMVC/kmom4/themes/core/img/steffe.jpg" alt="logo"/>
<?= getNavBar() ?>
</div>

  <div id='wrap-main'>
    <div id='main' role='main'>
      <?=get_messages_from_session()?>
      <?=@$main?>
      <?=render_views()?>
      <?=get_debug()?>
    </div>
  </div>
  <div id="footer">
    <?=$footer?>
    <p>Page was loaded in <?=round(microtime(true) - $ly->timer['first'], 5)*1000?> msecs. Steffe is fast as lightning.</p>
  </div>
</body>
</html>

