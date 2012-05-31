<?php
class CCGuestbook extends CObject implements IController
{
  private $pageTitle = 'Guestbook Example';
  private $pageHeader = '<h1>Guestbook Example</h1><p>Showing off how to implement a guestbook in Lydia.</p>';
  private $formAction;
  private $pageForm;
  private $pageMessages="";
  
  private $guestbookModel;

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->guestbookModel = new CMGuestbook();
  }
  

  public function Index() {   
    $this->data['title'] = "Guestbook";

    // Include the file and store it in a string using output buffering
    $messages = $this->guestbookModel->ReadAll();
    $formAction = $this->request->CreateUrl('guestbook/handler');
    ob_start();
    include __DIR__ . '/index.tpl.php';
    $this->data['main'] = ob_get_clean();
  }
  
  public function Handler()
  {
    if(isset($_POST['doAdd']))
    {
      $this->guestbookModel->Add(strip_tags($_POST['newEntry']));
    }
    elseif(isset($_POST['doClear']))
    {
      $this->guestbookModel->DeleteAll();
    }            
    elseif(isset($_POST['doCreate']))
    {
      $this->guestbookModel->Init();
    }
    
    header('Location: ' . $this->request->CreateUrl('guestbook'));
  }
  
}
