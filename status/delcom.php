<?php
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

# TOR Exit Node Check
function ReverseIPOctets($inputip) {
	$ipoc = explode(".",$inputip);
	return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
}
function IsTorExitPoint(){
	if(gethostbyname(ReverseIPOctets($_SERVER['REMOTE_ADDR']).".".$_SERVER['SERVER_PORT'].".".ReverseIPOctets($_SERVER['SERVER_ADDR']).".ip-port.exitlist.torproject.org")=="127.0.0.2") {
		return true;
	} else {
		return false;
	} 
}
if(IsTorExitPoint()) exit('You cannot perform actions while behind a proxy due to potential abuse. Please use a normal connection.');
// WORTH NOTING THAT THIS TOR EXIT NODE CHECKING METHOD DOESN'T WORK ANYMORE -- THERE'S A WORKING ALTERNATIVE BUT IT INVOLVES CURL

$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,points,token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0')
{
	header('location:/logout');
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$points = $row['points'];
$token = $row['token'];
if(isset($_GET['token']) && isset($_GET['id']) && isset($_GET['sid'])) {
	$gettoken = $_GET['token'];
	$gettoken = mysqli_real_escape_string($conn, $gettoken);
	$gettoken = htmlentities($gettoken, ENT_QUOTES);
	$did = $_GET['id'];
	$did = mysqli_real_escape_string($conn, $did);
	$did = htmlentities($did, ENT_QUOTES);
	$sid = $_GET['sid'];
	$sid = mysqli_real_escape_string($conn, $sid);
	$sid = htmlentities($sid, ENT_QUOTES);
}
$idc2 = mysqli_query($conn, "SELECT id,user,pointcount,postcount,points FROM status_comments WHERE id='$did'");
$idc = mysqli_num_rows($idc2);
$row2 = mysqli_fetch_assoc($idc2);
$username = $row2['user'];
$pointcount = $row2['pointcount'];
$postcount = $row2['postcount'];
$pointsc = $row2['points'];
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
}
if(isset($_GET['id']) && $did != '' && $did != ' ' && isset($_GET['token']) && $gettoken == $token) {
	if ($idc == 1 && $admin < 4 && $username != $logged) {
		mysqli_query($conn, "DELETE FROM status_comments WHERE id='$did'");
		if ($postcount > 0) {
			$update = mysqli_query($conn, "UPDATE members SET postcount='$postcount'-1 WHERE username='$username'");
		}
		if ($pointcount > 0) {
			$update = mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'-1 WHERE username='$username'");
			if ($pointcount == 0) {
				$update = mysqli_query($conn, "UPDATE members SET points='$pointsc'-1 WHERE username='$username'");
				$update = mysqli_query($conn, "UPDATE members SET pointcount='10' WHERE username='$username'");
				}
		}
	header('Location: /status/status.php?id='.$sid);
	die();
}
else {
	if ($username == $logged) {
		// Point managing START
		if ($pointcount > 0 && $pointsc != 0) {
			$update = mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'-1 WHERE username='$logged'");
			if ($postcount > 0) {
				$update = mysqli_query($conn, "UPDATE members SET postcount='$postcount'-1 WHERE username='$logged'");
			}
			if ($pointcount == 0 && $pointsc != 0) {
				$update = mysqli_query($conn, "UPDATE members SET points='$pointsc'-1 WHERE username='$logged'");
			}
			$update = mysqli_query($conn, "UPDATE members SET pointcount='10' WHERE username='$logged'");
		}
	// Point managing END
	mysqli_query($conn, "DELETE FROM status_comments WHERE id='$did'");
	header('Location: /status/status.php?id='.$sid);
	die(); // CAN THIS HAPPEN TO ME PLEASE -- THIS IS A MESS
}
else {
	header('Location: /status/status.php?id='.$sid);
	die();
}
}
}
else {
	header('Location: /status/status.php?id='.$sid);
	die();
}
mysqli_close($conn);
?>
