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

$sessjack = $select3 = mysqli_query($conn, "SELECT username,password,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = mysqli_num_rows($sessjack);
$row = mysqli_fetch_assoc($select3);
$disabled = $row['disabled'];
if($sesschecktwo == '0') {
	echo '<meta http-equiv="Refresh" content="0;url=/logout">';
	exit;
}
if($disabled == 'yes') {
	echo '<meta http-equiv="Refresh" content="0;url='.$denyaccessurl.'">';
	exit;
}
$seconds = 60;
$countr2 = mysqli_query($conn, "SELECT time FROM members WHERE time > UNIX_TIMESTAMP(NOW())-$seconds ORDER BY id");
$countr = mysqli_num_rows($countr2);
if($countr <= 99) {
echo $countr;
}
else {
echo '99+';
}
mysqli_close($conn);
?>
