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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,priv FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$priv = $row['priv'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");

$priv = explode(',', $priv); // convert into array (helps detection)

echo var_dump($priv);
echo '<br>';

$priv2 = implode(',', $priv); // make into string (can be used in MySQL query)

echo var_dump($priv2);

echo '<br>';
if(in_array('community_manager', $priv)) {
	echo 'You\'re a community manager!';
}
else {
	echo 'Nope.';
}
echo '<br>';
echo '<br>';

# REMOVAL OF PRIV
$removepriv = array_search('owner', $priv); // check if a value exists
if($removepriv !== false) {
    unset($priv[$removepriv]); // remove value from array if found
}

# ADDING A PRIV
array_push($priv, 'banana'); // add in a value

$priv2 = implode(',', $priv); // turn into string again

echo var_dump($priv2);


?>
