<?php include_once("initialize.php");

class fpAdmin {

	private $fp_array;
	private $prompts;
	private $tags;

	function __construct() {
    global $database;
    $this->fp_array = array();
		$this->prompts = array();
		$this->tags = array();
    $sql="SELECT * FROM fpage ORDER BY start_show DESC";
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			array_push($this->fp_array, $value);
		}

		$sql="SELECT * FROM tags ORDER BY tag";
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			$temp_array = array(
				"value"		=> strtolower($value['tag']),
				"display"	=> $value['tag'],
				"id"			=> $value['id'],
				"show" 		=> true
			);
			array_push($this->tags, $temp_array);
		}
	}

  function get_fpresource($which_one){
		global $database;
		$date = new DateTime();
		$today = $date->getTimestamp();
		foreach ($this->fp_array as $info){
			if ($info['start_show'] <= $today && $info['wview']=== $which_one){
				$value = $info;
				break;
			}
		}
		$numid = (int) $value['numid'];
		$resource = new resObject($numid);
    $title = $resource->get_title();
    $description = $resource->get_description();
    $rlink = $resource->get_rlink();
		$numviews = $resource->get_numviews();
		$thumbsup = $resource->get_thumbsup();
		$thumbsdown = $resource->get_thumbsdown();
    $temp_array = array(
			'title'       => $title,
      'rlink'       => $rlink,
			'numviews'		=> $numviews,
			'thumbsup'		=> $thumbsup,
			'thumbsdown'	=> $thumbsdown,
			'numid'				=> $numid
    );

    return $temp_array;
  }

	function get_FpageResource($numid){
		global $database;
    $sql="SELECT * FROM fpage WHERE numid='".$numid."'";
    $result_set = $database->query($sql);
		$info = $database->fetch_array($result_set);
		$year = date('Y', $info['start_show']);
		$month = date('m', $info['start_show']);
		$day = date('d', $info['start_show']);
		$returnArray = array (
			'year' => $year,
			'month' => $month,
			'day'		=> $day,
			'wview' => $info['wview']
		);
		return $returnArray;
	}

  function prepare_fpage(){
    $inspiration = $this->get_fpresource('inspiration');
    $advice = $this->get_fpresource('advice');
    // $opportunities = $this->get_fpresource('opportunities');
    $returnArray = array(
      'inspiration'   => $inspiration,
      'advice'     => $advice
    );
    return $returnArray;
  }

	function get_prompts(){
		global $database;
		$date = new DateTime();
    $today = $date->getTimestamp();
		$sql="SELECT * FROM announcements WHERE `sdate` <= ".$today." ORDER BY sdate DESC";
		$result_set = $database->query($sql);
    while ($value = $database->fetch_array($result_set)) {
			array_push($this->prompts, $value['statements']);
		}
		$announcements = $this->prompts[0];
		$returnArray = explode("|", $announcements);
		return $returnArray;
	}

	function get_tools(){
		global $database;
		$sql="SELECT * FROM tools";
		$result_set = $database->query($sql);
		$returnArray = array();
    while ($value = $database->fetch_array($result_set)) {
			$temp_array = array(
				'numid' => $value['numid'],
				'title' => $value['title'],
				'description' => $value['description'],
				'link' => $value['link']
			);
			array_push($returnArray, $temp_array);
		}
		return $returnArray;
	}

	function get_FpagePrompt($numid){
		global $database;
		$sql="SELECT * FROM announcements WHERE id ='".$numid."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$promptsArray = explode("|", $value['statements']);
		$start = date('m/d/Y', $value['sdate']);
		$year = date('Y', $value['sdate']);
		$month = date('m', $value['sdate']);
		$day = date('d', $value['sdate']);
		$returnArray = array (
			'year' => $year,
			'month' => $month,
			'day'		=> $day,
			'prompts' => $promptsArray,
			'start'		=> $start,
			'id'			=> $numid
		);
		return $returnArray;
	}

	function get_tags(){
		return $this->tags;
	}

	function get_dateset($which_type, $color){
		global $database;
		$sql="SELECT * FROM fpage WHERE wview='".$which_type."'";
		$result_set = $database->query($sql);
		$dates_array = array();
    while ($value = $database->fetch_array($result_set)) {
			$event = new resObject($value['numid']);
			$calendar = date('Y-m-d', $value['start_show']);
			$date_array = array(
				'title' => $event->get_title(),
				'start' => $calendar,
				'numid' => $value['numid'],
				'color'	=> $color
			);
			array_push($dates_array, $date_array);
		}
		$returnArray = array(
			'events' 		=> $dates_array,
			'color'			=> $color
		);
		return $returnArray;
	}

	function get_dates(){
		global $database;
		$inspiration = $this->get_dateset('inspiration', 'red');
		$advice = $this->get_dateset('advice', 'blue');
		$opportunities = $this->get_dateset('opportunities', 'green');
		$sql="SELECT * FROM announcements ORDER BY sdate";
		$result_set = $database->query($sql);
		$dates_array = array();
    while ($value = $database->fetch_array($result_set)) {
			$statements = explode("|", $value['statements']);
			$calendar = date('Y-m-d', $value['sdate']);
			$date_array = array(
				'title' => $statements,
				'start' => $calendar,
				'numid' => $value['id'],
				'color'	=> 'purple'
			);
			array_push($dates_array, $date_array);
		}
		$promptsArray = array(
			'events' 		=> $dates_array,
			'color'			=> 'purple'
		);
		$returningArray = array();
		array_push($returningArray, $inspiration);
		array_push($returningArray, $advice);
		array_push($returningArray, $opportunities);
		array_push($returningArray, $promptsArray);
		return $returningArray;
	}

	function editResource($edate, $wview, $numid){
		global $database;
		$sql = "UPDATE fpage SET ";
		$sql .= "wview='". $wview ."', ";
		$sql .= "start_show='". $edate ."' ";
		$sql .= "WHERE numid='". $numid. "' ";
		$database->query($sql);
	}

	function editPrompts($sdate, $statements, $id){
		global $database;
		$sql = "UPDATE announcements SET ";
		$sql .= "statements='". $statements ."', ";
		$sql .= "sdate='". $sdate ."' ";
		$sql .= "WHERE id='". $id. "' ";
		$database->query($sql);
	}

	function deleteResource($numid){
		global $database;
		$sql = "DELETE FROM fpage ";
	  	$sql .= "WHERE numid=". $numid;
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	}

	function addResource($adate, $wview, $title){
		global $database;
		$sql="SELECT * FROM resources WHERE title='".$title."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$numid = $value['numid'];
		$tdate = (int)$adate;

		$sql = "INSERT INTO fpage (";
	  	$sql .= "numid, wview, start_show";
	  	$sql .= ") VALUES ('";
	  	$sql .= $numid  ."', '";
      $sql .= $wview  ."', '";
      $sql .= $tdate ."')";
		$database->query($sql);
	}

	function addPrompts($date, $statements){
		global $database;
		$sql = "INSERT INTO announcements (";
	  	$sql .= "statements, sdate";
	  	$sql .= ") VALUES ('";
      $sql .= $statements  ."', '";
      $sql .= $date ."')";
		$database->query($sql);
	}

	function deleteprompt($id){
		global $database;
		$sql = "DELETE FROM announcements ";
	  	$sql .= "WHERE id=". $id;
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	}

}
?>
