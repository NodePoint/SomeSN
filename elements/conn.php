<?php
$conn = mysqli_connect('host', 'user', 'password', 'database');
if(!$conn) {
	exit('Oh crud! Something prevented us from communicating with the database!<br>No worries though, the details have been passed on to the site administrators.<br><br>Try again later.'); // THIS WAS REFERRING TO THE ERROR LOG AT THE TIME
}
mysqli_set_charset($conn, 'utf8mb4'); // THIS IS SOMETHING YOU SHOULD PROBABLY USE EVEN TO THIS DAY

$denyaccessurl = '/suspended';
?>
