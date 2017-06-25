<?php
# APPLICATION INITIALIZER

session_start();
require '../elements/conn.php';

if(isset($_COOKIE['username'])) {
	$logged = $_COOKIE['username'];
	$logged = mysqli_real_escape_string($conn, $logged);
	$logged = htmlentities($logged, ENT_QUOTES);
}
else {
	$logged = '';
}
if(isset($_COOKIE['password'])) {
	$password = $_COOKIE['password'];
	$password = mysqli_real_escape_string($conn, $password);
	$password = htmlentities($password, ENT_QUOTES);
}
else {
	$password = '';
}
$sessjack = $select =mysqli_query($conn, "SELECT username,password,disabled,points,rbe_token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location: /logout'); // MYSQLI CONNECTION IS NOT BEING CLOSED
	exit;
}

$row = mysqli_fetch_assoc($select);

$disabled = $row['disabled'];
$points = $row['points'];

## USER ROWS START - FOR APPLICATION USAGE
$rbe_token = $row['rbe_token'];
## USER ROWS END

mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}



if(isset($_GET['app']) && $_GET['app'] != '' && trim($_GET['app']) != ' ') {
	$app = $_GET['app'];
	$app = mysqli_real_escape_string($conn, $app);
	$app = htmlentities($app, ENT_QUOTES);
	$app_q = mysqli_query($conn, "SELECT id,path_title,path FROM app_list WHERE path_title='$app'");
	$exist = mysqli_num_rows($app_q);
}
else {
	mysqli_close($conn);
	exit('Your request cannot be blank or non-existent.');
}

if($exist == 1) {
	$app_data = mysqli_fetch_assoc($app_q);
	$app_id = $app_data['id'];
	$ownership = mysqli_query($conn, "SELECT username,app_id FROM app_log WHERE username='$logged' AND app_id='$app_id'");
	$ownership = mysqli_num_rows($ownership);
	if($ownership == 0) {
		mysqli_close($conn);
		exit('You don\'t own this app.'.$app_id);
	}
	$path = $app_data['path'];
	include $path;
}
else {
	mysqli_close($conn);
	exit('The app "'.$app.'" doesn\'t exist.');
}
exit; // NO MYSQLI CONNECTION IS CLOSED.. AGAIN
?>
