<?php
/**
* Sample controller for a site builder.
*/
class CCEntertainment extends CObject implements IController
{

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }
  

  /**
   * The page about me
   */
  public function Index()
  {
    $this->redirectToController('Home');
  }
  private function addFeatured()
  {
  	  $getNews = new CMContent();
	  $sport = $getNews->ListByTagAndType(array('tag'=>'sport', 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC'));
	  $entertainment = $getNews->ListByTagAndType(array('tag'=>'entertainment', 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC'));
	  $local = $getNews->ListByTagAndType(array('tag'=>'local', 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC'));
  	  $this->views
  	   ->AddInclude(__DIR__ . '/sportFeutured.tpl.php', array(
	    	    'news' => $sport),
	    	    'featured-first')
	    ->AddStyle('#featured-first{background-color:#00CED1; color:#FFF; text-align:center;}a.white, a.white:visited { color:#000; }') 
	    
	    ->AddInclude(__DIR__ . '/entertainmentFeutured.tpl.php', array(
	    	    'news' => $entertainment),
	    	    'featured-middle')
	    ->AddStyle('#featured-middle{background-color:#00CED1; color:#FFF; text-align:center;}a.white, a.white:visited { color:#000; }') 
	    
	    ->AddInclude(__DIR__ . '/localFeutured.tpl.php', array(
	    	    'news' => $local),
	    	    'featured-last')
	    ->AddStyle('#featured-last{background-color:#00CED1; color:#FFF; text-align:center;}a.white, a.white:visited { color:#000; }');
	    
  }
  public function Home()
  {
	    $getNews = new CMContent();
	    $newsRSS = $this->getFeed("http://expressen.se/rss/nyheter ");

	    $this->addFeatured();
	    $this->views->SetTitle('Den roligaste nyhetssidan')
	    ->AddInclude(__DIR__ . '/sidebarRSS.tpl.php', array(
	    	    'news' => $newsRSS,
	    	    'rubrik' => "Senaste från Expressen",),
	    	    'sidebar')
	    ->AddStyle('#sidebar{background-color:#EEE;}')
	   
	    ->AddInclude(__DIR__ . '/news.tpl.php', array(
                  'contents' => $getNews->ListAll(array('type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                  'heading' => 'Latest news:',
                )); 
  }
  public function About()
  {
  	  $newsRSS = $this->getFeed("http://expressen.se/rss/nyheter ");

	    $this->addFeatured();
	    $this->views->SetTitle('Den roligaste nyhetssidan')
	    ->AddInclude(__DIR__ . '/sidebarRSS.tpl.php', array(
	    	    'news' => $newsRSS,
	    	    'rubrik' => "Senaste från Expressen",),
	    	    'sidebar')
	    ->AddStyle('#sidebar{background-color:#EEE;}')
	   
	    ->AddInclude(__DIR__ . '/about.tpl.php', array());
  }

  function getFeed($feed_url)
  {
  	  $content = file_get_contents($feed_url);
  	  $x = new SimpleXmlElement($content);
  	  return $x;
  }


  /**
   * The news.
   */
  public function LatestNews()
  {
    $content = new CMContent();
    $newsRSS = $this->getFeed("http://expressen.se/rss/nyheter ");
    $this->views->SetTitle('nyheter')
    		->AddInclude(__DIR__ . '/sidebarRSS.tpl.php', array(
	    	    'news' => $newsRSS,
	    	    'rubrik' => "Senaste från Expressen",),
	    	    'sidebar')
                ->AddInclude(__DIR__ . '/news.tpl.php', array(
                  'contents' => $content->ListAll(array('type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                ));        
  }
  public function Forum($category="entertainment", $thread=null)
  {
    $content = new CMContent();
    
    $this->views->SetTitle('Forum')
    	->AddStyle('#flash{background-color:#EEE;text-align:center;}')
    	->AddString("<h1><a class='white' name='white' href=".$this->CreateUrl('entertainment/forum/entertainment').">Entertainment</a></h1>", array(), 'featured-first')
    	->AddStyle('#featured-first{background-color:#EEE;text-align:center;}')
    	->AddString("<h1><a class='white' name='white' href=".$this->CreateUrl('entertainment/forum/sport').">Sport</a></h1>", array(), 'featured-middle')
    	->AddStyle('#featured-middle{background-color:#EEE;text-align:center;}')
    	->AddString("<h1><a class='white' name='white' href=".$this->CreateUrl('entertainment/forum/local').">Local</a></h1>", array(), 'featured-last')
    	->AddStyle('#featured-last{background-color:#EEE;text-align:center;}');
	if($category==null && $thread==null)
	{
		$this->views
		->AddInclude(__DIR__ . "/forum.tpl.php", array(
                  'entertainment' => $content->ListByTagAndType(array('tag'=>'entertainment', 'type'=>'forum', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'sport' => $content->ListByTagAndType(array('tag'=>'sport', 'type'=>'forum', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'local' => $content->ListByTagAndType(array('tag'=>'local', 'type'=>'forum', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                )); 
        }
        else if($category=="entertainment" && $thread==null)
	{
		$this->views
		->AddInclude(__DIR__ . "/forum.tpl.php", array(
                  'entertainment' => $content->ListByTagAndType(array('tag'=>'entertainment', 'type'=>'forum', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'sport' => null,
                  'local' => null,
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                ))
                ->AddStyle('#featured-first{background-color:#00CED1; color:#FFF;}a.white, a.white:visited { color:#000; }'); 
        }
        else if($category=="sport" && $thread==null)
	{
		$this->views
		->AddInclude(__DIR__ . "/forum.tpl.php", array(
                  'sport' => $content->ListByTagAndType(array('tag'=>'sport', 'type'=>'forum', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'entertainment' => null,
                  'local' => null,
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                ))
                ->AddStyle('#featured-middle{background-color:#00CED1; color:#FFF;}a.white, a.white:visited { color:#000; }'); 
        }
        else if($category=="local" && $thread==null)
	{
		$this->views
		->AddInclude(__DIR__ . "/forum.tpl.php", array(
                  'local' => $content->ListByTagAndType(array('tag'=>'local', 'type'=>'forum', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'entertainment' => null,
                  'sport' => null,
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                ))
                ->AddStyle('#featured-last{background-color:#00CED1; color:#FFF;}a.white, a.white:visited { color:#000; }'); 
        }
        else
        {
        	if($category=="entertainment") $this->views ->AddStyle('#featured-first{background-color:#00CED1; color:#FFF;}a.white, a.white:visited { color:#000; }'); 
		else if($category=="sport") $this->views ->AddStyle('#featured-middle{background-color:#00CED1; color:#FFF;}a.white, a.white:visited { color:#000; }'); 
                else $this->views  ->AddStyle('#featured-last{background-color:#00CED1; color:#FFF;}a.white, a.white:visited { color:#000; }'); 
        	
        	$content = new CMContent($thread);
        	//$related = new CMContent();
		$comments = new CMResponse();
		    
		$commentsForm = new CFormResponse('entertainment/forum/'.$category.'/'.$thread, $this->session->getUserName(), $comments, $thread);
		$status = $commentsForm->Check();
		if($status === false)
		{
			$this->AddMessage('notice', 'The form could not be processed.');
			$this->RedirectToControllerMethod($category, $thread);
		}
		else if($status === true)
		{
			$this->RedirectToControllerMethod($category, $thread);
		}
		$this->views->SetTitle('Nyheter')
			->AddInclude(__DIR__ . '/singleThread.tpl.php', array(
			  'news' => $content,
			  'isAuthenticated' => $this->session->userIsAuthenticated(),
			  'commentsForm' => $commentsForm,
			  'comments' => $comments->readAll($thread),
			))
			->AddStyle('#primary{background-color:#99FFFF;}');
		}
  }
  public function Sport()
  {
    $content = new CMContent();
    $this->addFeatured();
    $sportRSS = $this->getFeed("http://www.aftonbladet.se/sportbladet/fotboll/rss.xml");
    $this->views->SetTitle('Sport news')
                ->AddInclude(__DIR__ . '/news.tpl.php', array(
                  'contents' => $content->ListByTagAndType(array('tag'=>'sport', 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                  'heading' => 'Sport news:',
                ))
    ->AddInclude(__DIR__ . '/sidebarRSS.tpl.php', array(
	    	    'news' => $sportRSS,
	    	    'rubrik' => "Senaste från Aftonbladet",),
	    	    'sidebar')
    ->AddStyle('#sidebar{background-color:#EEE;}');        
  }
  public function Entertainment()
  {
    $content = new CMContent();
    $this->addFeatured();
    $entertainmentRSS = $this->getFeed("http://expressen.se/rss/noje");
    $this->views->SetTitle('Entertainment news')
                ->AddInclude(__DIR__ . '/news.tpl.php', array(
                  'contents' => $content->ListByTagAndType(array('tag'=>'entertainment', 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                  'heading' => 'Entertainment news:',
                ))
                ->AddInclude(__DIR__ . '/sidebarRSS.tpl.php', array(
	    	    'news' => $entertainmentRSS,
	    	    'rubrik' => "Senaste från Expressen",),
	    	    'sidebar')
	    	->AddStyle('#sidebar{background-color:#EEE;}');        
  }
  public function Local()
  {
    $content = new CMContent();
    $this->addFeatured();
    $localRSS = $this->getFeed("http://svt.se/rss/nyheter/blekingenytt");
    $this->views->SetTitle('Local news')
                ->AddInclude(__DIR__ . '/news.tpl.php', array(
                  'contents' => $content->ListByTagAndType(array('tag'=>'local', 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'isAuthenticated' => $this->session->userIsAuthenticated(),
                  'heading' => 'Local news:',
                ))
    ->AddInclude(__DIR__ . '/sidebarRSS.tpl.php', array(
	    	    'news' => $localRSS,
	    	    'rubrik' => "Senaste från SVT",),
	    	    'sidebar')
    ->AddStyle('#sidebar{background-color:#EEE;}');        
  }
  

  
  /**
  * Edit new article
  */
  public function Edit($type="news", $id=null)
  {
    $content = new CMContent($id);

    $form = new CFormCreateNewContent($content, $type);
    	
    $status = $form->Check();
    if($status === false)
    {
      $this->AddMessage('notice', 'The form could not be processed.');
      $this->RedirectToController('edit', $id);
    }
    else if($status === true)
    {
      $this->RedirectToController('edit', $content['id']);
    }
    
    $title = (isset($id) ? 'Edit' : 'Create');
    $this->views->SetTitle("$title")
                ->AddInclude(__DIR__ . '/edit.tpl.php', array(
                  'user'=>$this->user, 
                  'content'=>$content, 
                  'form'=>$form,
                ));
  }
  

  /**
   * Create new article.
   */
  public function Create($type="news") {
    $this->Edit($type);
  }

  public function show($newsId=null, $newstype=null)
  {
    if($newsId!=null)
    {
	    $content = new CMContent($newsId);
	    //$related = new CMContent();
	    $comments = new CMResponse();
	    $commentsForm = new CFormResponse('entertainment/show', $this->session->getUserName(), $comments, $newsId);
	    $status = $commentsForm->Check();
	    if($status === false)
	    {
	      $this->AddMessage('notice', 'The form could not be processed.');
	      $this->RedirectToControllerMethod($newsId, $newstype);
	    }
	    else if($status === true)
	    {
	      $this->RedirectToControllerMethod($newsId, $newstype);
	    }
	    
	    $this->views->SetTitle('Nyheter')
			->AddInclude(__DIR__ . '/singleNews.tpl.php', array(
			  'news' => $content,
			  'isAuthenticated' => $this->session->userIsAuthenticated(),
			  'commentsForm' => $commentsForm,
			  'comments' => $comments->readAll($newsId),
			));
	    /*$related = new CMContent();
	    $this->views->AddInclude(__DIR__ . '/sidebarRelated.tpl.php', array(
			   'related' => $related->ListByTagAndType(array('tag'=>$newstype, 'type'=>'news', 'order-by'=>'title', 'order-order'=>'DESC')),),
			   'sidebar')
			 ->AddStyle('#sidebar{background-color:#EEE;}');*/     
    }	    
  }
  
}



class CFormCreateNewContent extends CForm
{

  /**
   * Properties
   */
  private $content;

  /**
   * Constructor
   */
  public function __construct($content, $type)
  {
    parent::__construct();
    $this->content = $content;
    $save = isset($content['id']) ? 'save' : 'create';
    $this->AddElement(new CFormElementHidden('id', array('value'=>$content['id'])))
    	 ->AddElement(new CFormElementHidden('type', array('value'=>$type)))
         ->AddElement(new CFormElementText('title', array('value'=>$content['title'])))
         ->AddElement(new CFormElementText('category', array('value'=>$content['tag'])));
    if($type=="news")
         $this->AddElement(new CFormElementText('img', array('value'=>$content['img'])));
 
         $this->AddElement(new CFormElementTextarea('data', array('label'=>'Content:', 'value'=>$content['data'])))
         ->AddElement(new CFormElementSubmit($save, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($content))))
         ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($content))));

    $this->SetValidation('title', array('not_empty'))
         ->SetValidation('category', array('not_empty'));
  }
  

  /**
   * Callback to save the form content to database.
   */
  public function DoSave($form, $content)
  {
    $content['id']     = $form['id']['value'];
    $content['title']  = $form['title']['value'];
    $content['img']  = $form['img']['value'];
    $content['tag']    = $form['category']['value'];
    $content['data']   = $form['data']['value'];
    $content['type']   = $form['type']['value'];
    $content['filter'] = "plain";
    return $content->Save();
  }
  /**
   * Callback to delete the content.
   */
  public function DoDelete($form, $content) {
    $content['id'] = $form['id']['value'];
    $content->Delete();
    CLydia::Instance()->RedirectTo('entertainment/home');
  }
  
  
}

class CFormResponse extends CForm
{

  /**
   * Properties
   */
  private $response;

  /**
   * Constructor
   */
  public function __construct($link, $user,$response, $id)
  {
  	  parent::__construct();
  	  $this->response = $response;
  	  $this->AddElement(new CFormElementTextarea('data', array('label'=>'Response:')))
  	  	->AddElement(new CFormElementSubmit('Post', array('callback'=>array($this, 'DoAdd'), 'callback-args'=>array($link, $user, $response, $id))));
  }
  

  /**
   * Callback to add the form content to database.
   */
  public function DoAdd($form, $link, $user, $response, $id)
  {
  	  $response->Add($user, strip_tags($form['data']['value']), $id);
  	  CLydia::Instance()->RedirectTo($link.'/'.$id);
  }


}
