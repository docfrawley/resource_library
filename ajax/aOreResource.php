<?php require_once("../includes/initialize.php");
session_start();

$params = json_decode(file_get_contents('php://input'),true);
$what = $params['what'];
$tags = $params['tags'];
$type = $params['type'];
$title = $params['title'];
$numid = $params['numid'];
$pdfFile = $params['pdfFile'];
$description = $params['description'];
$link = $params['link'];

if ($what=='add'){
  $resources = new resourceAdmin();
  $resources->addResource($tags, $type, $title, $description, $link);
} else {
  $resource = new resObject($numid);
  $resource->updateResource($tags, $type, $title, $description, $link, $pdfFile);
}



$posta = array(
  'text' => "Hey Super Admins. A new resource was added to the pending queue entitled '{$title}'"
  );
$post = json_encode($posta);

$ch = curl_init('https://hooks.slack.com/services/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');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// curl -X POST -H 'Content-type: application/json' --data '{"text":"Hello, World!"}'
https://hooks.slack.com/services/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
// execute!
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);



$data = array(
  'success'=>$response
);
echo json_encode($data);

?>
