<?php
/**
 * A model for content stored in database.
 * 
 * @package LydiaCore
 */
class CMForumContent extends CObject implements IHasSQL, ArrayAccess, IModule 
{

  /**
   * Properties
   */
  public $data;


  /**
   * Constructor
   */
  public function __construct($id=null) {
    parent::__construct();
    if($id) {
      $this->LoadById($id);
    } else {
      $this->data = array();
    }
  }


  /**
   * Implementing ArrayAccess for $this->data
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }


  /**
   * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
   *
   * @param string $key the string that is the key of the wanted SQL-entry in the array.
   */
  public static function SQL($key=null, $args=null) {
    $order_order  = isset($args['order-order']) ? $args['order-order'] : 'ASC';
    $order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id';    
    $queries = array(
      'drop table Forumcontent'        => "DROP TABLE IF EXISTS ForumContent;",
      'create table Forumcontent'      => "CREATE TABLE IF NOT EXISTS ForumContent (id INTEGER PRIMARY KEY, tag TEXT KEY, type TEXT, title TEXT, img TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
      'insert Forumcontent'            => 'INSERT INTO ForumContent (tag,type,title, img, data,filter,idUser) VALUES (?, ?,?,?,?,?,?);',
      'select * by id'            => 'SELECT c.*, u.acronym as owner FROM ForumContent AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;',
      'select * by tagAndType'    => 'SELECT c.*, u.acronym as owner FROM ForumContent AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.tag=? AND c.type=? AND deleted IS NULL;',
      'select * by type'          => "SELECT c.*, u.acronym as owner FROM ForumContent AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
      'select *'                  => 'SELECT c.*, u.acronym as owner FROM ForumContent AS c INNER JOIN User as u ON c.idUser=u.id WHERE deleted IS NULL;',
      'update Forumcontent'            => "UPDATE ForumContent SET tag=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
      'update Forumcontent as deleted' => "UPDATE ForumContent SET deleted=datetime('now') WHERE id=?;",
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }

  
    /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        try {
          $this->db->ExecuteQuery(self::SQL('drop table Forumcontent'));
          $this->db->ExecuteQuery(self::SQL('create table Forumcontent'));
          $this->db->ExecuteQuery(self::SQL('insert Forumcontent'), array('sport', 'news', 'apa är en jävel på att dribbla', 'test.png', "Zlatan är helt galen, tycker alla barnen i Bullerbyn! Zlatan är helt galen, tycker alla barnen i Bullerbyn! Zlatan är helt galen, tycker alla barnen i Bullerbyn! Zlatan är helt galen, tycker alla barnen i Bullerbyn!", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert Forumcontent'), array('sport', 'news', 'apa gillar inte ens hockey längre', 'test.png', "Foppa berättar för oss i en exklusiv intervju att han hatar hockey och att han tänker springa in i väggen tio gånger. Foppa gillar inte ens hockey längre', Foppa berättar för oss i en exklusiv intervju att han hatar hockey och att han tänker springa in i väggen tio gånger.", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert Forumcontent'), array('sport', 'news', 'apa vinner tantligan i år igen!', 'test.png', "Stina gjorde två mål och firade med tre fina bakåtvolter.", 'plain', $this->user['id']));
          
          $this->db->ExecuteQuery(self::SQL('insert Forumcontent'), array('entertainment', 'news', 'Galna trisslotter', 'test.png', "Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs!", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert Forumcontent'), array('local', 'news', 'En häst hjälpte en hel by', 'test.png', "Alla barnen i bullerbyn fick en hästsko av hästen! Alla barnen i bullerbyn fick en hästsko av hästen! Alla barnen i bullerbyn fick en hästsko av hästen!", 'plain', $this->user['id']));
          
          return array('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
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
   * Save content. If it has a id, use it to update current entry or else insert new entry.
   *
   * @returns boolean true if success else false.
   */
  public function Save() {
    $msg = null;
    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update Forumcontent'), array($this['tag'], $this['type'], $this['title'], $this['img'], $this['data'], $this['data'], $this['filter'], $this['id']));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert Forumcontent'), array($this['tag'], $this['type'], $this['title'], $this['img'], $this['data'], $this['filter'], $this->user['id']));
      $this['id'] = $this->db->LastInsertId();
      $msg = 'created';
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', "Successfully {$msg} Forumcontent '" . htmlEnt($this['key']) . "'.");
    } else {
      $this->AddMessage('error', "Failed to {$msg} Forumcontent '" . htmlEnt($this['key']) . "'.");
    }
    return $rowcount === 1;
  }
    

  /**
   * Load content by id.
   *
   * @param id integer the id of the content.
   * @returns boolean true if success else false.
   */
  public function LoadById($id) {
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
    if(empty($res)) {
      $this->AddMessage('error', "Failed to load content with id '$id'.");
      return false;
    } else {
      $this->data = $res[0];
    }
    return true;
  }
  
  public function ListByTagAndType($args)
  {
  	  if(isset($args) && isset($args['tag']) && isset($args['type']))
  	  {
  	  	  $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by tagAndType', $args), array($args['tag'], $args['type']));
  	  }
	  if(empty($res))
	  {
	  	  $this->AddMessage('error', "Failed to load Forumcontent with tag ".$args['tag']);
	  	  return false;
	  }
	  else
	  {
	  	  $this->data = $res[0];
	  }
	  return $res;
  }
  
  
  /**
   * List all content.
   *
   * @returns array with listing or null if empty.
   */
  public function ListAll($args=null)
  {    
    try
    {
      if(isset($args) && isset($args['type']))
      {
        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
      }
      else
      {
        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));
      }
    }
    catch(Exception $e)
    {
      echo $e;
      return null;
    }
  }
  
  /**
   * Filter content according to a filter.
   *
   * @param $data string of text to filter and format according its filter settings.
   * @returns string with the filtered data.
   */
  public static function Filter($data, $filter) {
    switch($filter) {
      /*case 'php': $data = nl2br(makeClickable(eval('?>'.$data))); break;
      case 'html': $data = nl2br(makeClickable($data)); break;*/
      case 'htmlpurify': $data = nl2br(CHTMLPurifier::Purify($data)); break;
      case 'bbcode': $data = nl2br(bbcode2html(htmlEnt($data))); break;
      case 'plain': 
      default: $data = nl2br(makeClickable(htmlEnt($data))); break;
    }
    return $data;
  }
  
  public function GetFilteredData() {
    return $this->Filter($this['data'], $this['filter']);
  }
  
  /**
   * Delete content. Set its deletion-date to enable wastebasket functionality.
   *
   * @returns boolean true if success else false.
   */
  public function Delete()
  {
    if($this['id'])
    {
      $this->db->ExecuteQuery(self::SQL('update Forumcontent as deleted'), array($this['id']));
    }
    $rowcount = $this->db->RowCount();
    if($rowcount)
    {
      $this->AddMessage('success', "Successfully set Forumcontent '" . htmlEnt($this['key']) . "' as deleted.");
    }
    else
    {
      $this->AddMessage('error', "Failed to set Forumcontent '" . htmlEnt($this['key']) . "' as deleted.");
    }
    return $rowcount === 1;
  }
  
  
  
  
}
