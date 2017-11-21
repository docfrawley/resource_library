<? include_once("initialize.php");

class userAdmin {

	private $users;


	function __construct() {
    $this->users = array();
	}

	function set_array(){
		global $database;
		$sql="SELECT * FROM userlist ORDER By lname";
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			array_push($this->users, $value);
		}
	}

	function get_who(){
		$user = new userObject(1);
		$returnArray = array(
			'netid' 		=> $user->get_netid(),
			'numindex' 	=> $user->get_numindex(),
			'fname'			=> $user->get_fname(),
			'lname'			=> $user->get_lname(),
			'level'			=> $user->get_level()
		);
		return $returnArray;
	}

	function addUser($fname, $lname, $netid, $level, $webhook){
		global $database;
		$current = true;
    $sql = "INSERT INTO userlist (";
	  	$sql .= "fname, lname, netid, level, current, webhook";
	  	$sql .= ") VALUES ('";
	  	$sql .= $fname ."', '";
      $sql .= $lname ."', '";
      $sql .= $netid ."', '";
			$sql .= $level ."', '";
			$sql .= $current ."', '";
		  $sql .= $webhook ."')";
		$database->query($sql);
	}

	function editUser($numindex, $fname, $lname, $netid, $level, $webhook){
		global $database;

		$sql = "UPDATE userlist SET ";
		$sql .= "fname='". $fname ."', ";
		$sql .= "lname='". $lname ."', ";
		$sql .= "netid='". $netid ."', ";
		$sql .= "level='". $level ."', ";
		$sql .= "webhook='". $webhook ."' ";
		$sql .= "WHERE numindex='". $numindex . "' ";
		$database->query($sql);
	}

  function deleteuser($numindex){
    global $database;
		$current = false;
		$sql = "UPDATE userlist SET ";
		$sql .= "current='0' ";
		$sql .= "WHERE numindex='". $numindex . "' ";
		$database->query($sql);
	}

  function get_users_list(){
    global $database;
    $returnArray = array();
    $sql="SELECT * FROM userlist ORDER BY lname";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			array_push($returnArray, $value);
		}
		return $returnArray;
  }

  function get_netids(){
    global $database;
    $returnArray = array();
    $sql="SELECT netid FROM userlist ORDER BY netid";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			array_push($returnArray, $value);
		}
		return $returnArray;
  }

	function get_users(){
		global $database;
    $returnArray = array();
    $sql="SELECT * FROM userlist WHERE current = 1 ORDER BY lname";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$name = $value['fname'].' '.$value['lname'];
			$t_array = array(
				'display'		=> $name,
				'level'			=> $value['level'],
				'numindex'	=> $value['numindex']
			);
			array_push($returnArray, $t_array);
		}
		return $returnArray;
	}

	function get_user($index=1) {
		global $database;
    $sql="SELECT * FROM userlist WHERE numindex = '".$index."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value;
	}

	function get_userid($netid){
		global $database;
    $sql="SELECT * FROM userlist WHERE netid = '".$netid."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value['numindex'];
	}

}
?>
