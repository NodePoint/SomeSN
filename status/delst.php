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
if(IsTorExitPoint()) exit('You cannot perform actions while behind a proxy due to potential abuse. Please use a normal connection.'); // DEAD

$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,points,pointcount,postcount,token FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$pointsc = $row['points'];
$token = $row['token'];
$pointcount = $row['pointcount'];
$postcount = $row['postcount'];
if(isset($_GET['id']) && isset($_GET['token'])) {
	$did = $_GET['id'];
	$did = mysqli_real_escape_string($conn, $did);
	$did = htmlentities($did, ENT_QUOTES);
	$gettoken = $_GET['token'];
	$gettoken = mysqli_real_escape_string($conn, $gettoken);
	$gettoken = htmlentities($gettoken, ENT_QUOTES);
	// THERE WAS NO NEED TO CLEAN UP THE TOKEN INPUT AS IT WAS ONLY BEING COMPARED VIA IF STATEMENT
}
else {
	$did = '';
	$gettoken = '';
}
$idc2 = mysqli_query($conn, "SELECT id,username FROM status_log WHERE id='$did'");
$idc = mysqli_num_rows($idc2);
$row2 = mysqli_fetch_assoc($idc2);
$username = $row2['username'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if(isset($_GET['id']) && $did != '' && $did != ' ' && isset($_GET['token']) && $gettoken == $token) {
	if($idc == 1 && $admin < 4) {
		mysqli_query($conn, "DELETE FROM status_log WHERE id='$did'");
		mysqli_query($conn, "DELETE FROM status_comments WHERE sid='$did'");
		if($postcount > 0) {
			mysqli_query($conn, "UPDATE members SET postcount='$postcount'-1 WHERE username='$username'");
		}
		if($pointcount > 0) {
			mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'-1 WHERE username='$username'");
		if($pointcount == 0) {
			mysqli_query($conn, "UPDATE members SET points='$pointsc'-1 WHERE username='$username'");
		}
		mysqli_query($conn, "UPDATE members SET pointcount='10' WHERE username='$username'");
	}
	header('Location:/status');
	die();
}
else {
	if($username == $logged) {
		if ($pointcount > 0 && $pointsc != 0) {
		mysqli_query($conn, "UPDATE members SET pointcount='$pointcount'-1 WHERE username='$logged'");
		if($postcount > 0) {
			mysqli_query($conn, "UPDATE members SET postcount='$postcount'-1 WHERE username='$username'");
		}
		if($pointcount == 0 && $pointsc != 0) {
			mysqli_query($conn, "UPDATE members SET points='$pointsc'-1 WHERE username='$logged'");
		}
		$update = mysqli_query($conn, "UPDATE members SET pointcount='10' WHERE username='$logged'");
	}
	mysqli_query($conn, "DELETE FROM status_log WHERE id='$did'");
	mysqli_query($conn, "DELETE FROM status_comments WHERE sid='$did'");
	header('Location:/status');
	die();
}
else {
	header('Location:/status');
	die();
}
}
}
else {
	header('Location:/status');
	die();
}
mysqli_close($conn);
?>
