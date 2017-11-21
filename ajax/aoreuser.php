<?php require_once("../includes/initialize.php");


$params = json_decode(file_get_contents('php://input'),true);
global $database;
$whatdo = $database->escape_value($params['whatDo']);
$numindex = $database->escape_value($params['numindex']);
$fname = $database->escape_value($params['fname']);
$lname = $database->escape_value($params['lname']);
$netid = $database->escape_value($params['netid']);
$level = $database->escape_value($params['level']);
$webhook = $database->escape_value($params['webhook']);

$users = new userAdmin();
if ($whatdo == 'add'){
  $users->addUser($fname, $lname, $netid, $level, $webhook);
} else {
  $users->editUser($numindex, $fname, $lname, $netid, $level, $webhook);
}

$data = array(
  'success'=>$numindex
);
echo json_encode($data);

?>
