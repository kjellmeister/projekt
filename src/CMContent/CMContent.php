<?php
/**
 * A model for content stored in database.
 * 
 * @package LydiaCore
 */
class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule 
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
      'drop table content'        => "DROP TABLE IF EXISTS Content;",
      'create table content'      => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, tag TEXT KEY, type TEXT, title TEXT, img TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
      'insert content'            => 'INSERT INTO Content (tag,type,title, img, data,filter,idUser) VALUES (?, ?,?,?,?,?,?);',
      'select * by id'            => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;',
      'select * by tagAndType'    => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.tag=? AND c.type=? AND deleted IS NULL;',
      'select * by type'          => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
      'select *'                  => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE deleted IS NULL;',
      'update content'            => "UPDATE Content SET tag=?, type=?, title=?, img=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
      'update content as deleted' => "UPDATE Content SET deleted=datetime('now') WHERE id=?;",
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
          $this->db->ExecuteQuery(self::SQL('drop table content'));
          $this->db->ExecuteQuery(self::SQL('create table content'));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('sport', 'news', 'Zlatan kan dribbla!', 'zlatan.jpg', "Zlatan är helt galen, tycker alla barnen i Bullerbyn! Zlatan är helt galen, tycker alla barnen i Bullerbyn! Zlatan är helt galen, tycker alla barnen i Bullerbyn! Zlatan är helt galen, tycker alla barnen i Bullerbyn!", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('sport', 'news', 'Foppa gillar inte ens hockey längre', 'foppa.jpg', "Foppa berättar för oss i en exklusiv intervju att han hatar hockey och att han tänker springa in i väggen tio gånger. Foppa gillar inte ens hockey längre', Foppa berättar för oss i en exklusiv intervju att han hatar hockey och att han tänker springa in i väggen tio gånger.", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('sport', 'news', 'Curlingtanterna vinner tantligan i år igen!', 'curlingtanterna.jpg', "Stina gjorde två mål och firade med tre fina bakåtvolter.", 'plain', $this->user['id']));
          
          $this->db->ExecuteQuery(self::SQL('insert content'), array('entertainment', 'news', 'Galna trisslotter', 'triss.jpg', "Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! 'Kan man kanske vara miljonär så länge man vill så tänker jag aldrig skrapa min!' säger Göran Börjesson.", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('local', 'news', 'En häst hjälpte en hel by', 'zebra.jpg', "Alla barnen fick en hästsko av hästen! Även om de redan var använda, tyckte barnen att det var den finaste present som en man kan få av en häst!", 'plain', $this->user['id']));
          
          $this->db->ExecuteQuery(self::SQL('insert content'), array('entertainment', 'forum', 'Zlatan fake', 'test.png', "Har hört att zlatans vänsterfot är av plast! Vad tror ni?", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('sport', 'forum', 'Foppa tror att han är asiat', 'test.png', "Det går rykten om att foppa tror att han är asiat bara för att hans mamma gillar ris", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('sport', 'forum', 'Curlingtanterna vinner tantligan i år igen!', 'test.png', "Stina gjorde två mål och firade med tre fina bakåtvolter.", 'plain', $this->user['id']));
          
          $this->db->ExecuteQuery(self::SQL('insert content'), array('sport', 'forum', 'Galna trisslotter', 'test.png', "Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs! Nu kan du behålla din trisslott extra länge innan du skrapar den! Galen färs och ketchup tvärs!", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('local', 'forum', 'En häst hjälpte en hel by', 'test.png', "Hästen jagade bort alla svanar med fågelinfluensan! Jag var nära på att bli smittad! Vad är det häftigaste en häst har gjort för er?", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('local', 'forum', 'Nya träd i Hoglandspark', 'test.png', "Vad tycker ni om de nya träden som de har planterat i Hoglands park? Jag tycker att det blev fint!", 'plain', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('entertainment', 'forum', 'Lady Gaga kan göra bakåtvolter', 'test.png', "Vad är det häftigaste tricket ni kan!?", 'plain', $this->user['id']));
          
          
          
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
      $this->db->ExecuteQuery(self::SQL('update content'), array($this['tag'], $this['type'], $this['title'], $this['img'], $this['data'], $this['filter'], $this['id']));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert content'), array($this['tag'], $this['type'], $this['title'], $this['img'], $this['data'], $this['filter'], $this->user['id']));
      $this['id'] = $this->db->LastInsertId();
      $msg = 'created';
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', "aSuccessfully {$msg} content '" . htmlEnt($this['type']) . "'.");
    } else {
      $this->AddMessage('error', "Failed to {$msg} content '" . htmlEnt($this['type']) . "'.");
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
	  	  //$this->AddMessage('error', "Failed to load content with tag ".$args['tag']);
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
      $this->db->ExecuteQuery(self::SQL('update content as deleted'), array($this['id']));
    }
    $rowcount = $this->db->RowCount();
    if($rowcount)
    {
      $this->AddMessage('success', "Successfully set content '" . htmlEnt($this['key']) . "' as deleted.");
    }
    else
    {
      $this->AddMessage('error', "Failed to set content '" . htmlEnt($this['key']) . "' as deleted.");
    }
    return $rowcount === 1;
  }
  
  
  
  
}
