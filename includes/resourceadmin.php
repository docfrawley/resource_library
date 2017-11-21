<? include_once("initialize.php");
session_start();
class resourceAdmin {

	private $res_array;
	private $tag;


	function __construct() {
    $this->res_array = array();
	}

	function get_titles(){
		global $database;
		$returnArray = array();
		$sql="SELECT * FROM resources WHERE doshow !='d' AND doshow !='ds' ORDER BY title";
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			$temp_array = array(
				'display'	=> $value['title'],
				'numindex'=>	$value['numid']
			);
			array_push($returnArray, $temp_array);
		}
		return $returnArray;
	}

	function get_pending(){
		global $database;
		$returnArray = array();
		$sql="SELECT * FROM resources WHERE doshow ='p' ORDER BY wupload DESC";
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			$value['wupload'] = date('m/d/y',$value['wupload']);
			$numindex = $value['who'];
			$sqlt="SELECT * FROM userlist WHERE numindex='".$numindex."'";
			$result_sett = $database->query($sqlt);
			$info = $database->fetch_array($result_sett);
			$value['who'] = $info['fname'].' '.$info['lname'];
			array_push($returnArray, $value);
		}
		return $returnArray;
	}

	function get_edit_resources($what, $searching){
		global $database;
		switch ($what) {
			case 'title':
				$sql="SELECT * FROM resources WHERE title ='".$searching."'";
				break;
			case 'latest':
				if ($_SESSION['level'] == 'super'){
					$sql="SELECT * FROM resources WHERE doshow !='d' AND doshow != 'ds' ORDER BY wupload DESC LIMIT 20";
				} else {
					$sql="SELECT * FROM resources WHERE who = '".$numindex."' AND doshow !='d' AND doshow != 'ds' ORDER BY wupload DESC LIMIT 20";
				}
				break;
			case 'person':
				$full_name = explode(' ', $searching);
				$lname = $full_name[1];
				$sqln = "SELECT numindex FROM userlist
						WHERE lname ='".$lname."'";
				$result_setn = $database->query($sqln);
				$value = $database->fetch_array($result_setn);
				$numindex = $value['numindex'];
				$sql="SELECT * FROM resources WHERE who ='".$numindex."' AND doshow !='d' AND doshow !='ds'";
				break;
			case 'tag':
				$sql="SELECT * from tags WHERE tag='".$searching."'";
				$result_set = $database->query($sql);
				$value = $database->fetch_array($result_set);
				$tagid = $value['id'];
				$temp_array = $this->get_search_results($tagid, 'views', 'a', $_SESSION['level']);
				return $temp_array;
				break;
			default:
				break;
		}
		$returnArray = array();
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$value['wupload'] = date('m/d/y',$value['wupload']);
			$numindex = $value['who'];
			$sqlt="SELECT * FROM userlist WHERE numindex='".$numindex."'";
			$result_sett = $database->query($sqlt);
			$info = $database->fetch_array($result_sett);
			$value['who'] = $info['fname'].' '.$info['lname'];
			array_push($returnArray, $value);
		}
		return $returnArray;
	}

	function latestAdditions(){
		global $database;
		$sql="SELECT * FROM resources WHERE doshow ='s' ORDER BY wupload DESC";
		$result_set = $database->query($sql);
		$returnArray = array();
		$rArray = array();
    while ($value = $database->fetch_array($result_set)) {
			array_push($rArray, $value);
		}
		for ($i=0; $i <11 ; $i++) {
			array_push($returnArray, $rArray[$i]);
		}
		return $returnArray;
	}

	function get_resources_array($what='s'){
		global $database;
		$this->res_array = array();
		switch ($what) {
			case 's':
				$sql="SELECT * FROM resources WHERE doshow ='s' ORDER BY numviews DESC";
				break;
			case 'a':
				$sql="SELECT * FROM resources WHERE doshow != 'd' AND doshow != 'ds'  ORDER BY numviews DESC";
				break;
			case 'd':
				$sql="SELECT * FROM resources WHERE doshow != 'ds'  ORDER BY numviews DESC";
				break;
			default:
				# code...
				break;
		}
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			$value['wupload'] = date('m/d/y',$value['wupload']);
			$numindex = $value['who'];
			$sqlt="SELECT * FROM userlist WHERE numindex='".$numindex."'";
			$result_sett = $database->query($sqlt);
			$info = $database->fetch_array($result_sett);
			$value['who'] = $info['fname'].' '.$info['lname'];
			array_push($this->res_array, $value);
		}
	}

	function get_latest_uploads($what = 's'){
		global $database;
		$sql="SELECT * FROM resources WHERE doshow ='s' ORDER BY wupload DESC";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$value['wupload'] = date('m/d/y',$value['wupload']);
			array_push($this->res_array, $value);
		}
	}

	function get_resources_rankings($what='s'){
		global $database;
		$this->res_array = array();
		switch ($what) {
			case 's':
				$sql="SELECT *, (thumbsup-thumbsdown) as rank FROM resources
							WHERE doshow ='s' ORDER BY rank DESC";
				break;
			case 'a':
				$sql="SELECT *, (thumbsup-thumbsdown) as rank FROM resources
							WHERE doshow != 'd' AND doshow != 'ds' ORDER BY rank DESC";
				break;
			default:
				# code...
				break;
		}
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			$value['wupload'] = date('m/d/y',$value['wupload']);
			$numindex = $value['who'];
			$sqlt="SELECT * FROM userlist WHERE numindex='".$numindex."'";
			$result_sett = $database->query($sqlt);
			$info = $database->fetch_array($result_sett);
			$value['who'] = $info['fname'].' '.$info['lname'];
			array_push($this->res_array, $value);
		}
	}

	function tag_number($tag){
		global $database;
		$which_tag = strtolower($tag);
		$sql="SELECT * FROM tags WHERE tag='".$which_tag."'";
		$result_set = $database->query($sql);
    	$value = $database->fetch_array($result_set);
		return $value['id'];
	}

	function get_search_results($tag, $hsearch, $what='s', $inTags){
		if ($hsearch==='views'){
			$this->get_resources_array();
		} elseif ($hsearch==='rank') {
			$this->get_resources_rankings();
		} else {
			$this->get_latest_uploads();
		}
		$temp_array = array();
		$tag_admin = new tagAdmin();
		if ($inTags=='true'){
			$the_tag = (int)$tag;
			$tag_admin->add_hit($the_tag);
			foreach ($this->res_array as $value) {
				$tags_array = explode(',', $value['tags']);
				if (in_array($the_tag, $tags_array)){
					array_push($temp_array, $value);
				}
			}
		} else {
			foreach ($this->res_array as $value) {
				// $title = $database->escape_value($value['title']);
				// $description = $database->escape_value($value['description']);
				$description = strtolower($value['description']);
				$title = strtolower($value['title']);
				// $pattern = '/[^0-9a-z]/gi';
				// $replace = ' ';
				// $title = preg_replace($pattern, $replace, $title);
				// $description = preg_replace($pattern, $replace, $description);
				
				if (strpos($title, $tag) != false
				|| strpos($description, $tag) != false){
					array_push($temp_array, $value);
				}
			}
			$tag_admin->register_notag($tag, $temp_array);
			
		}
		return $temp_array;
	}

	function addResource($tags, $type, $title, $description, $link){
		global $database;
		$netid = $_SESSION['casnetid'];
		$user_admin = new userAdmin();
		$numindex = $user_admin->get_userid($netid);
		$doshow = 'p';
		$the_type = $database->escape_value($type);
		$the_link = $database->escape_value($link);
		if (strpos($the_link, 'lynda') !== false){
			$the_type = 'lynda';
		}
		$today = date('U');
		$sql = "INSERT INTO resources (";
	  	$sql .= "title, type_resource, description, tags, who, wupload, doshow, rlink";
	  	$sql .= ") VALUES ('";
	  	$sql .= $database->escape_value($title)  ."', '";
      	$sql .= $the_type  ."', '";
      	$sql .= $database->escape_value($description)."', '";
		$sql .= $database->escape_value($tags) ."', '";
		$sql .= $numindex ."', '";
		$sql .= $today ."', '";
		$sql .= $doshow ."', '";
		$sql .= $the_link ."')";
		$database->query($sql);

		$posta = array(
		'text' => "The resource you just uploaded is now in the pending queue, and the super admins have been notified. Once they approve the resource, it will be visible on the site. I'll slack you when that happens."
		);
		$post = json_encode($posta);
		$netid = $_SESSION['casnetid'];
		$sql="SELECT * FROM userlist WHERE netid='".$netid."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$slack_hook = $value['webhook'];

		$ch = curl_init($slack_hook);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		// curl -X POST -H 'Content-type: application/json' --data '{"text":"Hello, World!"}'
		https://hooks.slack.com/services/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
		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);
	}

	function addResourcePDF($info, $place){
		global $database;
		$netid = "mfrawley";
		$today = date('U');
		$sql = "INSERT INTO resources (";
	  	$sql .= "title, type_resource, description, tags, who, wupload, rlink";
	  	$sql .= ") VALUES ('";
	  	$sql .= $database->escape_value($info['title'])  ."', '";
      $sql .= $database->escape_value($info['type'])  ."', '";
      $sql .= $database->escape_value($info['description'])."', '";
			$sql .= $database->escape_value($info['tags']) ."', '";
			$sql .= $netid ."', '";
			$sql .= $today ."', '";
		  $sql .= $place ."')";
		$database->query($sql);
	}
}
?>
