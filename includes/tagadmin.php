<? include_once("initialize.php");

class tagAdmin {

    private $tags;

	function __construct() {
    $this->tags = array();
    }
    
    function get_tags(){
        global $database;
        $this->tags = array();
        $sql="SELECT * FROM tags ORDER BY tag";
		$result_set = $database->query($sql);
        while ($value = $database->fetch_array($result_set)) {
			$temp_array = array(
				"value"		=> strtolower($value['tag']),
				"display"	=> $value['tag'],
                "id"		=> $value['id'],
                "hits"      => $value['hits'],
				"show" 		=> true
			);
			array_push($this->tags, $temp_array);
        }
        return $this->tags;
    }

    function add_hit($tagid){
        global $database;
        $date = date('m/d/Y');
        $sql = "SELECT * from taghits WHERE tagid = '".$tagid."' AND hitdate= '".$date."'";
        $result_set = $database->query($sql);
        if ($database->num_rows($result_set) > 0){
            $value = $database->fetch_array($result_set);
            $numHits = $value['hits'] +1;
            $sql = "UPDATE taghits SET ";
            $sql .= "hits='". $numHits ."' ";
            $sql .= "WHERE tagid='". $tagid . "' ";
            $database->query($sql);
        } else {
            $sql = "INSERT INTO taghits (";
            $sql .= "tagid, hits, hitdate";
            $sql .= ") VALUES ('";
            $sql .= $tagid ."', '";
            $sql .= 1 ."', '";
		    $sql .= $date ."')";
		    $database->query($sql);
        }        
    }
    
    function tag_number($tag){
		// global $database;
		// $which_tag = strtolower($tag);
		// $sql="SELECT * FROM tags WHERE tag='".$which_tag."'";
		// $result_set = $database->query($sql);
    	// $value = $database->fetch_array($result_set);
		// return $value['id'];
    }
    
    function register_notag($tag, $temp_array){
        global $database;
        if (count($temp_array)>0) {
            $resources = '';
            foreach($temp_array as $value){
                $resources .= $value['numid'].',';
            }
            $resources = substr($resources, 0, -1);
        } else {
            $resources = '0';
        }
        $date = date('m/d/Y');
        $sql = "SELECT * from notaglist WHERE what_searched = '".$tag."' AND hitdate= '".$date."'";
        $result_set = $database->query($sql);
        if ($database->num_rows($result_set) > 0){
            $value = $database->fetch_array($result_set);
            $numindex = $value['numindex'];
            $numHits = $value['hits']+ 1;
            $sql = "UPDATE notaglist SET ";
            $sql .= "hits='". $numHits ."', ";
            $sql .= "served_resources='". $resources ."' ";
            $sql .= "WHERE numindex='". $numindex . "' ";
            $database->query($sql);
        } else {
            $sql = "INSERT INTO notaglist (";
            $sql .= "hitdate, what_searched, hits, served_resources";
            $sql .= ") VALUES ('";
            $sql .= $date ."', '";
            $sql .= $tag ."', '";
            $sql .= 1 ."', '";
		    $sql .= $resources ."')";
		    $database->query($sql);
        }
    }

}