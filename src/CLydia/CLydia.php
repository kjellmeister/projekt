<?php

class CLydia implements ISingleton
{
  private static $instance = null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $user;
	public $timer = array();
 
  
  protected function __construct() {
    // time page generation
    $this->timer['first'] = microtime(true); 
    
    // include the site specific config.php and create a ref to $ly to be used by config.php
    $ly = &$this;
    require(LYDIA_SITE_PATH.'/config.php');
    
    // Start a named session
    session_name($this->config['session_name']);
    session_start();
    $this->session = new CSession($this->config['session_key']);
    $this->session->PopulateFromSession();
    
    // Set default date/time-zone
    date_default_timezone_set($this->config['timezone']);
    
    // Create a database object.
    if(isset($this->config['database'][0]['dsn'])) {
      $this->db = new CDatabase($this->config['database'][0]['dsn']);
    }
    
    // Create a container for all views and theme data
    $this->views = new CViewContainer();
    
    // Create a object for the user
    $this->user = new CMUser($this);
  }
     /**
    * Frontcontroller, check url and route to controllers.
    */
    public function FrontControllerRoute()
    {
	    // Step 1
	    // Take current url and divide it in controller, method and parameters
	    // Take current url and divide it in controller, method and parameters
	    $this->request = new CRequest();
	    $this->request->Init($this->config['base_url'], $this->config['routing']);
	    $controller = $this->request->controller;
	    $method     = $this->request->method;
	    $arguments  = $this->request->arguments;
	    // Step 2
	    // Check if there is a callable method in the controller class, if then call it
	    
	    
	    // Is the controller enabled in config.php?
	    $controllerExists    = isset($this->config['controllers'][$controller]);
	    $controllerEnabled    = false;
	    $className             = false;
	    $classExists           = false;
	
	    if($controllerExists)
	    {
	      $controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] == true);
	      $className               = $this->config['controllers'][$controller]['class'];
	      $classExists           = class_exists($className);
	    }
	    
	        // Check if controller has a callable method in the controller class, if then call it
	        if($controllerExists && $controllerEnabled && $classExists)
	        {
	        	$rc = new ReflectionClass($className);
	        	if($rc->implementsInterface('IController'))
	        	{
	        		if($rc->hasMethod($method))
	        		{
	        			$controllerObj = $rc->newInstance();
	        			$methodObj = $rc->getMethod($method);
	        			$methodObj->invokeArgs($controllerObj, $arguments);
	        		} 
	        		else
	        		{
	        			die("404. " . get_class() . ' error: Controller does not contain method.');
	        		}
	        	}
	        	else
	        	{
	        		die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
	        	}
	        } 
	        else
	        { 
	        	die('404. Page is not found.');
	        }

    }
    
      /**
    /**
   * ThemeEngineRender, renders the reply of the request to HTML or whatever.
   */
 public function ThemeEngineRender()
 {
    // Save to session before output anything
    $this->session->StoreInSession();
  
    // Is theme enabled?
    //if(!isset($this->config['theme'])) { return; }
    
    // Get the paths and settings for the theme, look in the site dir first
    $themePath 	= LYDIA_INSTALL_PATH . '/' . $this->config['theme']['path'];
    $themeUrl		= $this->request->base_url . $this->config['theme']['path'];

    // Is there a parent theme?
    $parentPath = null;
    $parentUrl = null;
    if(isset($this->config['theme']['parent']))
    {
      $parentPath = LYDIA_INSTALL_PATH . '/' . $this->config['theme']['parent'];
      $parentUrl	= $this->request->base_url . $this->config['theme']['parent'];
    }

    
    // Add stylesheet name to the $ly->data array
    $this->data['stylesheet'] = $this->config['theme']['stylesheet'];
    
    // Make the theme urls available as part of $ly
    $this->themeUrl = $themeUrl;
    $this->themeParentUrl = $parentUrl;

    // Include the global functions.php and the functions.php that are part of the theme
    $ly = &$this;
    // First the default Lydia themes/functions.php
    include(LYDIA_INSTALL_PATH . '/themes/functions.php');
    // Then the functions.php from the parent theme
    if($parentPath) {
      if(is_file("{$parentPath}/functions.php")) {
        include "{$parentPath}/functions.php";
      }
    }
    // And last the current theme functions.php
    if(is_file("{$themePath}/functions.php")) {
      include "{$themePath}/functions.php";
    }

    // Extract $ly->data to own variables and handover to the template file
    extract($this->data);  // OBSOLETE, use $this->views->GetData() to set variables
    extract($this->views->GetData());
    if(isset($this->config['theme']['data'])) {
      extract($this->config['theme']['data']);
    }

    // Execute the template file
    $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
    if(is_file("{$themePath}/{$templateFile}")) {
      include("{$themePath}/{$templateFile}");
    } else if(is_file("{$parentPath}/{$templateFile}")) {
      include("{$parentPath}/{$templateFile}");
    } else {
      throw new Exception('No such template file.');
    }
  }
   

   /**
    * Singleton pattern. Get the instance of the latest created object or create a new one. 
    * @return CLydia The instance of this class.
    */
   public static function Instance()
   {
      if(self::$instance == null)
      {
      	      self::$instance = new CLydia();
      }
      return self::$instance;
   }
   
   	public function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }    
    if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }    
    if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
	    $this->session->SetFlash('timer', $this->timer);
    }    
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));
    exit;
  }


	/**
	 * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
	 *
	 * @param string method name the method, default is index method.
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function RedirectToController($method=null, $arguments=null) {
    $this->RedirectTo($this->request->controller, $method, $arguments);
  }

  
  public function AddMessage($type, $message) {
    $this->session->AddMessage($type, $message);
  }
  
   
  public function CreateUrl($urlOrController=null, $method=null, $arguments=null)
  {
    return $this->request->CreateUrl($urlOrController, $method, $arguments);
  }
  public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
	  $controller = is_null($controller) ? $this->request->controller : null;
	  $method = is_null($method) ? $this->request->method : null;	  
    $this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
  }
}
