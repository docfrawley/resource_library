<? include_once("./includes/initialize.php");
session_start();

// remove all session variables
session_unset();

// destroy the session

session_destroy();

function redirect_to() {
	  header("Location: https://csrl.princeton.edu");
	  exit();
}
redirect_to();

?>