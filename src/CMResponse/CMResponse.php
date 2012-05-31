<?php
/**
* A model for a guestbok, to show off some basic controller & model-stuff.
* 
* @package LydiaCore
*/
class CMResponse extends CObject implements IHasSQL, IModule
{


  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }


  /**
   * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
   *
   * @param string $key the string that is the key of the wanted SQL-entry in the array.
   */
  public static function SQL($key=null)
  {
    $queries = array(
    	    'drop table response'        => "DROP TABLE IF EXISTS Response;",
    	    'create table response'  => "CREATE TABLE IF NOT EXISTS Response (responseTo INTEGER, author TEXT, response TEXT, created DATETIME default (datetime('now')));",
    	    'insert into response'   => 'INSERT INTO Response (author, response, responseTo) VALUES (?,?,?);',
    	    'select * from response' => 'SELECT * FROM Response ORDER BY created ASC;',
    	    'select * by id' => 'SELECT * FROM Response WHERE responseTo=? ORDER BY created DESC;',
    	    'delete from response'   => 'DELETE FROM Response;',
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }


  /**
   * Init the guestbook and create appropriate tables.
   */
  public function Init()
  {
    /*
    try {
      $this->db->ExecuteQuery(self::SQL('create table guestbook'));
      $this->session->AddMessage('notice', 'Feel free to fill the database with nonsens');
    } catch(Exception$e) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    }
    */
  }
  
    /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) 
  {
    switch($action) {
      case 'install': 
      	      try
      	      {
      	      	      $this->db->ExecuteQuery(self::SQL('drop table response'));
      	      	      $this->db->ExecuteQuery(self::SQL('create table response'));
      	      	      return array('success', 'Feel free to fill the response database with nonsens');  
      	      }
      	      catch(Exception$e)
      	      {
      	      	      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
      	      }
      	      break;
      default:
      	      throw new Exception('Unsupported action for this module.');
      	      break;
    }
  }
  

  /**
   * Add a new entry to the guestbook and save to database.
   */
  public function Add($author, $response, $responseTo)
  {
  	  if($response!="")
  	  {
  	  	  //echo "Author=".$author." response: ".$response." responseTo:".$responseTo;
		    $this->db->ExecuteQuery(self::SQL('insert into response'), array($author, $response, $responseTo));
		    $this->session->AddMessage('success', 'Your response has been posted.');
		    if($this->db->rowCount() != 1)
		    {
		    	    die('Failed to insert new response into database.');
		    }
	  }
	  else
	  {
	  	  $this->session->AddMessage('warning', "You can't post an empty comment.. idiot.");
	  }
  }
  

  /**
   * Delete all entries from the guestbook and database.
   */
  public function DeleteAll() {
    $this->db->ExecuteQuery(self::SQL('delete from response'));
    $this->session->AddMessage('info', 'Removed all messages.');
  }
  
  
  /**
   * Read response
   */
  public function ReadAll($id)
  {
	    try
	    {
		   return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from response', Array($id)));
	    }
	    catch(Exception $e)
	    {
	    	    echo "TJA";
		    return array();    
	    }
  }

  
}
