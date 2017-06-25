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
$sessjack = $select1 = mysqli_query($conn, "SELECT username,password,admin FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$id = $row['id'];
$disabled = $row['disabled'];
$points = $row['points'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if(isset($_GET['tog'])) {
	$tog = $_GET['tog'];
	$tog = mysqli_real_escape_string($conn, $tog);
	$tog = htmlentities($tog, ENT_QUOTES);
}
else {
	$tog = '';
}
$idc2 = mysqli_query($conn, "SELECT id FROM status_log WHERE id='$did'");
$idc = mysqli_num_rows($idc2);
$row2 = mysqli_fetch_assoc($idc2);
if (isset($_GET['tog']) && $tog != '' && $tog != ' ' && $tog == 'on') {
$update = mysqli_query($conn, "UPDATE members SET status_auto='1' WHERE username='$logged'");
echo '<script>window.history.go(-1)</script>';
echo '<noscript><meta http-equiv="Refresh" content=\"0;url=/status"></noscript>';
exit;
}
else {
if (isset($_GET['tog']) && $tog != '' && $tog != ' ' && $tog == 'off') {
$update = mysqli_query($conn, "UPDATE members SET status_auto='0' WHERE username='$logged'");
echo '<script>window.history.go(-1)</script>';
echo '<noscript><meta http-equiv="Refresh" content=\"0;url=/status"></noscript>';
exit;
}
else {
echo '<script>window.history.go(-1)</script>';
echo '<noscript><meta http-equiv="Refresh" content=\"0;url=/status"></noscript>';
exit;
}
}
mysqli_close($conn);
?>
