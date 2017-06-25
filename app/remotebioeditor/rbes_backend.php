<?php
session_start();
require '../../elements/conn.php';

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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,rbe_token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location: /logout');
	exit;
}

$row = mysqli_fetch_assoc($select);

$disabled = $row['disabled'];
$rbe_token = $row['rbe_token'];

mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}

# OWNERSHIP OF APP - START
	$app_q = mysqli_query($conn, "SELECT id,path_title FROM app_list WHERE path_title='RemoteBioEditorServ'");
	$exist = mysqli_num_rows($app_q);

if($exist == 1) {
	$app_data = mysqli_fetch_assoc($app_q);
	$app_id = $app_data['id'];
	$ownership = mysqli_query($conn, "SELECT username,app_id FROM app_log WHERE username='$logged' AND app_id='$app_id'");
	$ownership = mysqli_num_rows($ownership);
	if($ownership == 0) {
		mysqli_close($conn);
		exit('You don\'t own this app.');
	}
}
# OWNERSHIP OF APP - END

function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)]; // THIS COULD BE MADE MORE RANDOM IN PHP 7.0 AND BELOW BY USING MT_RAND()
    }
    return $randomString;
}

if(isset($_POST['info'])) {
	if($rbe_token == '') {
		$final_val = generateRandomString();
	}
	else {
		$final_val = '';
	}
	mysqli_query($conn, "UPDATE members SET rbe_token='$final_val' WHERE username='$logged'");
	if($final_val == '') {
		echo 'Turned off';
	}
	else {
		echo $final_val;
	}
}
else {
	echo 'Failed: Invalid request.';
}
mysqli_close($conn);
?>
