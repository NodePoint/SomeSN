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
$select = mysqli_query($conn,"SELECT username FROM members WHERE username='$logged'");
$row = mysqli_fetch_assoc($select);
$count = mysqli_num_rows($select);
$counter2 = mysqli_query($conn, 'SELECT id FROM members');
$counter = mysqli_num_rows($counter2);
$select2 = mysqli_query($conn, "SELECT username FROM members ORDER BY id DESC LIMIT 0 , 1");
$row2 = mysqli_fetch_assoc($select2);
$latest = $row2['username'];
$select3 = mysqli_query($conn, "SELECT username FROM members WHERE username='$latest'");
$count2 = mysqli_num_rows($select3);
$sessjack = mysqli_query($conn, "SELECT username,password FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = mysqli_num_rows($sessjack);
?>
<br>
<br>
SomeSN v1.0.0 DEV
<br>
<br>
Total users:
<?php
if ($counter == '0' || $counter == '') {
	echo '0';
}
else {
	echo $counter;
}
echo '<br>';
echo 'Latest user: ';
if($sesschecktwo > '0') {
$latesturl = '/user/'.$latest;
}
else {
$latesturl = 'javascript:void(0);';
}
if($count2 > 0) {
	echo '<a href="'.$latesturl.'" style="color:#4458ff;">'.$latest.'</a>';
}
else {
	echo '<span style="color:red;">N/A</span>';
}
?>
<br>
<br>
&copy; SomeSN 2017
<br>
All rights reserved.
<br>
<br>
<a href="/tos" style="color:#4458ff;" target="_blank">Terms of Service</a>
<br>
<br>
<?php
mysqli_close($conn);
?>
