<?php
/**
* Site configuration, this file is changed by user per site.
*
*/

/*
* Set level of error reporting
*/
error_reporting(-1);
ini_set('display_errors', 1);

/*
* Define session name
*/
$ly->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
$ly->config['session_key']  = 'lydia';


/*
* Define server timezone
*/
$ly->config['timezone'] = 'Europe/Stockholm';

/*
* Define internal character encoding
*/
$ly->config['character_encoding'] = 'UTF-8';

/*
* Define language
*/
$ly->config['language'] = 'en';

/**
* Set a base_url to use another than the default calculated
*/
$ly->config['base_url'] = null;

/**
* Define the controllers, their classname and enable/disable them.
*
* The array-key is matched against the url, for example: 
* the url 'developer/dump' would instantiate the controller with the key "developer", that is 
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $ly->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/

//URL will be changed with createUrl() in functions.php
$ly->config['controllers'] =
array
(
  'index'     	=> array('enabled' => true,'class' => 'CCIndex', 'url'=> '0'),
  'developer'   => array('enabled' => false,'class' => 'CCDeveloper', 'url'=> '0'),
  'guestbook'	=> array('enabled' => false, 'class' => 'CCGuestbook', 'url'=> '0'),
  'user'	=> array('enabled' => true, 'class' => 'CCUser', 'url'=> '0'),
  'acp'		=> array('enabled' => false, 'class' => 'CCAdminControlPanel', 'url'=> '0'),
  'content'	=> array('enabled' => false, 'class' => 'CCContent', 'url'=> '0'),
  'page'	=> array('enabled' => false, 'class' => 'CCPage', 'url'=> '0'),
  'blog'	=> array('enabled' => false, 'class' => 'CCBlog', 'url'=> '0'),
  'theme'	=> array('enabled' => false, 'class' => 'CCTheme', 'url'=> '0'),
  'module'	=> array('enabled' => true, 'class' => 'CCModules', 'url'=> '0'),
  'me'		=> array('enabled' => true, 'class' => 'CCMycontroller', 'url'=> '0'/*array('me'=>'me', 'guestbook'=>'me/guestbook', 'blog'=>'me/blog')*/),
  
  'entertainment'=> array('enabled' => true, 'class' => 'CCEntertainment', 
  	  'url'=> array('Home'=>'entertainment/Home', 'Sport' => 'entertainment/Sport', 'Entertainment' => 'entertainment/Entertainment', 'Local' => 'entertainment/Local', 'Forum'=>'entertainment/forum' , 'About' => 'entertainment/about')),
);

$ly->config['theme'] = array(
  'path'            => 'site/themes/mytheme',
  'parent'          => 'themes/grid',
  'stylesheet'      => 'style.css',
  'template_file'   => 'index.tpl.php',
  'regions' => array('flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
  ),
  // Add static entries for use in the template file. 
  'data' => array(
    'header' => 'Steffe',
    'slogan' => 'blixt1',
    'favicon' => '/~jokr11/PHPMVC/kmom6/themes/core/img/steffe.jpg',
    'logo' => "/~jokr11/PHPMVC/projekt/site/themes/mytheme/img/ankeborgsVeckoBlad.jpg",
    'logo_width'  => 80,
    'logo_height' => 80,
    'footer' => '<p>Steffe &copy;</p>',),
);



/**
* What type of urls should be used?
* 
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$ly->config['url_type'] = 1;

/**
* Set database(s).
*/
$ly->config['database'][0]['dsn'] = 'sqlite:' . LYDIA_SITE_PATH . '/data/.ht.sqlite';

/**
* How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
*/
$ly->config['hashing_algorithm'] = 'sha1salt';

/**
* Allow or disallow creation of new user accounts.
*/
$ly->config['create_new_users'] = true;

/**
* Define a routing table for urls.
*
* Route custom urls to a defined controller/method/arguments
*/
$ly->config['routing'] = array(
  'home' => array('enabled' => true, 'url' => 'index/index'),
);


//debug on/off
$ly->config['debug']['lydia'] = false;
$ly->config['debug']['db-num-queries'] = false;
$ly->config['debug']['db-queries'] = false;
