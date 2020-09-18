<?php
require_once __DIR__ . '/../app/config/database.php';

require_once __DIR__ . '/../app/functions/user.php';
?>

<?php
$_SESSION=array();
if (isset($_COOKIE[session_name()])==true){
	setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();
header('Location: index.php');
		exit;
		?>

