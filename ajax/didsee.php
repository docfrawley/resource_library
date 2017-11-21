<?php require_once("../includes/initialize.php");


$params = json_decode(file_get_contents('php://input'),true);
$numid = $params['numid'];
$whichone = $params['whichone'];
$pressed = $params['pressed'];
$resource = new resObject($numid);
$resource->addOne($whichone, $pressed);
$data = array(
  'success'=>true
);

echo json_encode($data);





?>
