<?php require_once("../includes/initialize.php");

$tmp_file = $_FILES['file']['tmp_name'];
$target_file = basename($_FILES['file']['name']);
$the_place = "../pdfs/".$target_file;
if(move_uploaded_file($tmp_file, $the_place)) {
  $message = "File uploaded successfully.";
  $place = "pdfs/".$target_file;
} else {
  $error = $_FILES['file']['error'];
  $message = $upload_errors[$error];
}
$data = array(
  'link'=>$place,
  'message'=>$message
);

echo json_encode($data);

?>
