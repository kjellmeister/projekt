<?php
/**
* A model for a guestbok, to show off some basic controller & model-stuff.
* 
* @package LydiaCore
*/
class CMGuestbook extends CObject implements IHasSQL, IModule
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
      'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
      'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
      'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
      'delete from guestbook'   => 'DELETE FROM Guestbook;',
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
        try {
          $this->db->ExecuteQuery(self::SQL('create table guestbook'));
          return array('success', 'Feel free to fill the database with nonsens');  
        } catch(Exception$e) {
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
  public function Add($entry)
  {
  	  if($entry!="")
  	  {
		    $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
		    $this->session->AddMessage('success', 'Your message has been inserted.');
		    if($this->db->rowCount() != 1)
		    {
		      die('Failed to insert new guestbook item into database.');
		    }
	  }
	  else
	  {
	  	  $this->session->AddMessage('warning', "You can't post an empty comment...");
	  }
  }
  

  /**
   * Delete all entries from the guestbook and database.
   */
  public function DeleteAll() {
    $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
    $this->session->AddMessage('info', 'Removed all messages.');
  }
  
  
  /**
   * Read all entries from the guestbook & database.
   */
  public function ReadAll() {
    try {
      return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
    } catch(Exception $e) {
      return array();    
    }
  }

  
}
