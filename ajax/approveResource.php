<?php require_once("../includes/initialize.php");


$params = json_decode(file_get_contents('php://input'),true);
$tags = $params['tags'];
$title = $params['title'];
$numid = $params['numid'];
$description = $params['description'];

$resource = new resObject($numid);
$resource->approveResource($tags, $title, $description);

$data = array(
  'success'=>$numid
);
echo json_encode($data);

?>
