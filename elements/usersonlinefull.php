<?php
require 'conn.php';

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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
if($sesschecktwo == '0') {
	echo '<meta http-equiv="Refresh" content="0;url=/logout">';
	exit;
}
if($disabled == 'yes') {
	echo '<meta http-equiv="Refresh" content="0;url='.$denyaccessurl.'">';
	exit;
}
$seconds2 = 60;
$msgs2 = mysqli_query($conn, "SELECT username,time,colour,disabled FROM members WHERE time > UNIX_TIMESTAMP(NOW())-$seconds2 ORDER BY username");
while($msgs = mysqli_fetch_assoc($msgs2)) {
$username = $msgs['username'];
$colourc = $msgs['colour'];
$disabledc = $msgs['disabled'];
if($disabledc != 'yes') {
echo '<span onclick="location.href=\'/user/'.$username.'\'" style="color:'.$colourc.';cursor:pointer;">'.$username.'</span><br>';
}
else {
echo '<span onclick="location.href=\'/user/'.$username.'\'" style="color:'.$colourc.';cursor:pointer;background-color:red;opacity:0.5;">&nbsp;'.$username.'&nbsp;</span><br>';
}
}
mysqli_close($conn);
?>
