<?php
session_start();
require '../elements/conn.php';

$logged = $_COOKIE['username'];
$logged = mysqli_real_escape_string($conn, $logged);
$logged = htmlentities($logged, ENT_QUOTES);
$password = $_COOKIE['password'];
$password = mysqli_real_escape_string($conn, $password);
$password = htmlentities($password, ENT_QUOTES);
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
if(isset($_GET['token'])) {
	$gettoken = $_GET['token'];
	$gettoken = mysqli_real_escape_string($conn, $gettoken);
	$gettoken = htmlentities($gettoken, ENT_QUOTES);
    // NO NEED TO CLEAN THE DATA FROM $GETTOKEN
}
$token = $row['token'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
    exit;
}
if($sesschecktwo == '1') {
	if($gettoken == $token) {
		$logout = '';
		setcookie('username', $logout, 1, '/');
		setcookie('password', $logout, 1, '/');
		setcookie('PHPSESSID', $logout, 1, '/');
		header('location:/');
        mysqli_close($conn);
        exit;
	}
	else {
		header('location:/');
        mysqli_close($conn);
        exit;
	}
}
else {
	$logout = '';
	setcookie('username', $logout, 1, '/');
	setcookie('password', $logout, 1, '/');
	setcookie('PHPSESSID', $logout, 1, '/');
	header('location:/');
    mysqli_close($conn);
    exit;
}
?>
