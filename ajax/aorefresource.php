<?php require_once("../includes/initialize.php");


$params = json_decode(file_get_contents('php://input'),true);
global $database;
$whatdo = $database->escape_value($params['whatDo']);
$numid = $database->escape_value($params['numid']);
$whichview = $database->escape_value($params['whichview']);
$date = $database->escape_value($params['edate']);

$fadmin = new fpAdmin();

switch ($whatdo) {
  case 'addP':
    $fadmin->addPrompts($date, $whichview);
    break;
  case 'editP':
    $fadmin->editPrompts($date, $whichview, $numid);
    break;
  case 'edit':
    $fadmin->editResource($date, $whichview, $numid);
    break;
  case 'add':
    $fadmin->addResource($date, $whichview, $numid);
    break;
  case 'delete':
    $fadmin->deleteResource($numid);
    break;
  default:
    # code...
    break;
}

$data = array(
  'success'=>true
);
echo json_encode($data);

?>
