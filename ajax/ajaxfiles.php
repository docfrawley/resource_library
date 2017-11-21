<?php include_once("../includes/initialize.php");

$taskx=isset($_GET['task']) ? $_GET['task'] : "" ;
global $database;
$task = $database->escape_value($taskx);

$fpage = new fpAdmin();
$admin = new resourceAdmin();
$users = new userAdmin();

switch ($task) {
  case 'latestAdditions':
    $temp_array = $admin->latestAdditions();
  case 'deleteprompt':
    $id = $database->escape_value($_GET['id']);
    $fpage->deleteprompt($id);
    $temp_array = array(
      'success'=>$id
    );
    break;
  case 'getTitles':
    $temp_array = $admin->get_titles();
    break;
  case 'getTools':
    $temp_array = $fpage->get_tools();
    break;
  case 'getFpageResource':
    $numid = $database->escape_value($_GET['numid']);
    $temp_array = $fpage->get_FpageResource($numid);
    break;
  case 'getFpagePrompt':
    $numid = $database->escape_value($_GET['numid']);
    $temp_array = $fpage->get_FpagePrompt($numid);
    break;
  case 'getDates':
    $temp_array = $fpage->get_dates();
    break;
  case 'getPending':
    $temp_array = $admin->get_pending();
    break;
  case 'getEditResources':
    $what = $database->escape_value($_GET['what']);
    $searching = $database->escape_value($_GET['searching']);
    $temp_array = $admin->get_edit_resources($what, $searching);
    break;
  case 'getUsers':
    $temp_array = $users->get_users();
    break;
  case 'getUser':
    $index = $database->escape_value($_GET['index']);
    $temp_array = $users->get_user($index);
    break;
  case 'deleteuser':
    $numindex = $database->escape_value($_GET['numindex']);
    $users->deleteuser($numindex);
    $temp_array = array(
      'success'=>$numindex
    );
    break;
  case 'netids':
    $temp_array = $users->get_netids();
    break;
  case 'getWho':
    $temp_array = $users->get_who();
    break;
  case 'frontpage':
    $temp_array = $fpage->prepare_fpage();
    break;
  case 'prompts':
    $temp_array = $fpage->get_prompts();
    break;
  case 'tags':
    $temp_array = $fpage->get_tags();
    break;
  case 'get_search':
    $how_show = $database->escape_value($_GET['what_kind']);
    $tag = $database->escape_value($_GET['tag']);
    $hsearch = $database->escape_value($_GET['hsearch']);
    $inTags = $database->escape_value($_GET['inTags']);
    $temp_array = $admin->get_search_results($tag, $hsearch, $how_show, $inTags);
    break;
  case 'deleteresource':
    $numid = $database->escape_value($_GET['numid']);
    $status = $database->escape_value($_GET['status']);
    $resource = new resObject($numid);
    $resource->deleteResource($status);
    $what = $resource->get_doshow();
    $temp_array = array(
      'success'=>$what
    );
    break;
  case 'dresource':
    $numid = $database->escape_value($_GET['numid']);
    $resource = new resObject($numid);
    $resource->dResource();
    $temp_array = array(
      'success'=>$numid
    );
    break;
  case 'getEvents':
    $ch = curl_init('https://princeton.joinhandshake.com/external_feeds/110/public.rss?token=lcPaHn3_enmsL1FlZRGpJTC8byycRwboBba4QWYCPpqozLQRMJFnaA');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $page = new SimpleXMLElement($response);
    $temp_array = array(
      'success'=>$page
    );
  break;  
  default:
    # code...
    break;
};

echo json_encode($temp_array);


?>
