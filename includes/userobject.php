<? include_once("initialize.php");

class userObject {

	private $fname;
	private $lname;
  private $netid;
  private $level;
  private $resources;
  private $numindex;


	function __construct($numid) {
    global $database;
    $sql="SELECT * FROM userlist WHERE numindex='".$numid."'";
		$result_set = $database->query($sql);
    $value = $database->fetch_array($result_set);
		$this->fname = $value['fname'];
    $this->lname = $value['lname'];
    $this->netid = $value['netid'];
    $this->level = $value['level'];
    $this->numindex = $numid;
	}

  function get_netid(){
    return $this->netid;
  }

	function get_numindex(){
    return $this->numindex;
  }

  function get_fname(){
    return $this->fname;
  }

  function get_lname(){
    return $this->lname;
  }

	function get_fullName(){
    return $this->fname.' '.$this->lname;
  }

  function get_level(){
    return $this->level;
  }

	function updateUser($fname, $lname, $netid, $level, $current, $webhook){
		global $database;

		$sql = "UPDATE userlist SET ";
		$sql .= "fname='". $database->escape_value($title) ."', ";
		$sql .= "lname='". $database->escape_value($type) ."', ";
		$sql .= "netid='". $database->escape_value($description) ."', ";
		$sql .= "level='". $database->escape_value($link) ."', ";
		$sql .= "current='". $database->escape_value($tags) ."', ";
		$sql .= "webhook='". $database->escape_value($tags) ."' ";
		$sql .= "WHERE numid='". $this->numindex . "' ";
		$database->query($sql);
	}

}
?>
