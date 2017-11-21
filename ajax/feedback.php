<?php include_once("../includes/initialize.php");

global $database;
$email= $database->escape_value($_GET['email']);
$name= $database->escape_value($_GET['name']);
$feedback= $database->escape_value($_GET['actual']);

$text = "We got FEEDBACK from ";
if ($name == ""){
    $text .="someone who didn't provide a name";
} else {
    $text .=$name;
}
if ($email == ""){
    if ($name==""){
        $text .=" nor an email address. ";
    } else {
        $text .=" who didn't provide an email address. ";
    }
} else {
    if ($name==""){
        $text .=" but did provide an email address: ";
    } else {
        $text .=" with an email address: ";
    }
    $text .=$email.". ";
}
$text .="Here's the feedback/question: ".$feedback;

$posta = array(
  'text' => "Super Duper Admins. {$text}'"
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
  'success'=>$text
);
echo json_encode($data);
?>