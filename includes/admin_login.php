<? include_once("initialize.php");
//session_start(); 

include_once('../../CAS.php');



phpCAS::client(CAS_VERSION_2_0,'fed.princeton.edu',443,'cas','true');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();


// $casnetid = phpCAS::getUser();

echo "Got here";
 //$casnetid = 'mfrawley';
									
$sql = "SELECT * FROM userlist WHERE netid = '".$casnetid."'";
$result_set = $database->query($sql);
$found_user = $database->fetch_array($result_set);


if(!isset($found_user['netid']))
{ ?><div>That user is not authorized to access this site.</div>
       <? 
        
} else {
	$_SESSION['casnetid'] = $casnetid;
	$_SESSION['fullname'] = $found_user['fname'].' '.$found_user['lname'];
    $_SESSION['level'] = $found_user['level'];
}
 //echo $casnetid;
?>