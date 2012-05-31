<?php
/**
* Helpers for theming, available for all themes in their template files and functions.php.
* This file is included right before the themes own functions.php
*/

/**
* Create a url by prepending the base_url.
*/
function base_url($url)
{
  return CLydia::Instance()->request->base_url . trim($url, '/');
}

/**
* Return the current url.
*/
function current_url()
{
  return CLydia::Instance()->request->current_url;
}

/**
 * Escape data to make it safe to write in the browser.
 */
function esc($str) {
  return htmlEnt($str);
}

function filter_data($data, $filter) {
  return CMContent::Filter($data, $filter);
}

/**
* Print debuginformation from the framework.
*/
function get_debug()
{
  $ly = CLydia::Instance();  
  $html = null;
  if(isset($ly->config['debug']['db-num-queries']) && $ly->config['debug']['db-num-queries'] && isset($ly->db)) {
    $html .= "<p>Database made " . $ly->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($ly->config['debug']['db-queries']) && $ly->config['debug']['db-queries'] && isset($ly->db)) {
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $ly->db->GetQueries()) . "</pre>";
  }    
  if(isset($ly->config['debug']['lydia']) && $ly->config['debug']['lydia']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CLydia:</p><pre>" . htmlent(print_r($ly, true)) . "</pre>";
  }    
  return $html;
}

/**
* Get messages stored in flash-session.
*/
function get_messages_from_session() {
  $ly = CLydia::Instance();
  $messages1 = $ly->session->GetMessages();
  //echo $messages1;
  $html = null;
  if(!empty($messages1))
  {
    foreach($messages1 as $val)
    {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}

function getNavBar()
{
	$ly = CLydia::Instance();
	$navbar = "<nav class=navmenu>";
	foreach($ly->config['controllers'] as $key => $item)
	{
		if($item['url']!="0")
		{
			
			if(is_array($item['url']))
			{
				foreach($item['url'] as $linkname => $link)
				{
					$current = $ly->request->controller."/".$ly->request->getCurrentMethod();

					if($current == $link)
					{
						$selected=" class='selected'";
					}
					else
					{
						$selected = null;
					}
					
					$navbar .= "<a href='{$ly->request->CreateUrl($link)}'{$selected}>{$linkname}</a>\n";
				}
			}
			else
			{
				if($ly->request->controller == $key)
				{
					$selected=" class='selected'";
				}
				else
				{
					$selected = null;
				}
				$navbar .= "<a href='{$ly->request->CreateUrl($key)}'{$selected}>{$item['url']}</a>\n";
			}
		}
	}
	$navbar .= "</nav>";
	echo $navbar;
}

/**
* Render all views.
*
* @param $region string the region to draw the content in.
*/
function render_views($region='default') {
  return CLydia::Instance()->views->Render($region);
}


/**
* Check if region has views. Accepts variable amount of arguments as regions.
*
* @param $region string the region to draw the content in.
*/
function region_has_content($region='default' /*...*/)
{
  return CLydia::Instance()->views->RegionHasView(func_get_args());
}

/**
 * Prepend the theme_url, which is the url to the current theme directory.
 *
 * @param $url string the url-part to prepend.
 * @returns string the absolute url.
 */
function theme_url($url) {
  return create_url(CLydia::Instance()->themeUrl . "/{$url}");
}
/**
* Prepend the theme_parent_url, which is the url to the parent theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_parent_url($url) {
  return create_url(CLydia::Instance()->themeParentUrl . "/{$url}");
}

function create_url($urlOrController=null, $method=null, $arguments=null) {
  return CLydia::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

/**
* Login menu. Creates a menu which reflects if user is logged in or not.
*/

function login_menu()
{
  $ly = CLydia::Instance();
  if($ly->user['isAuthenticated']) {
    $items = "<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $ly->user['acronym'] . "</a> ";
    if($ly->user['hasRoleAdministrator']) {
      $items .= "<a href='" . create_url('acp') . "'>acp</a> ";
    }
    $items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
  } else {
    $items = "<a href='" . create_url('user/login') . "'>login</a> ";
  }
  return "<nav id='login-menu'>$items</nav>";
}


/**
* Get a gravatar based on the user's email.
*/
function get_gravatar($size=null)
{
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CLydia::Instance()->user['email']))) . '.jpg?' . ($size ? "s=$size" : null);
}
