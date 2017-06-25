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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled,points,avatar,website,profile,grouptype FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout'); // MYSQLI CONNECTION IS NOT BEING CLOSED
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$points = $row['points'];
$avatar = $row['avatar'];
$grouptype = $row['grouptype'];
$website = $row['website'];
$profile = $row['profile'];
if($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}

$multi = '';

if(isset($_POST['avatarbox']) || isset($_POST['groupbox']) || isset($_POST['websitebox'])) {
	$avatarbox1 = $_POST['avatarbox'];
	$avatarbox = mysqli_real_escape_string($conn, $avatarbox1);
	$avatarbox = htmlentities($avatarbox, ENT_QUOTES);
	$avatarbox = str_replace('­','', $avatarbox); // REMOVAL OF A DASH? WHY?? REALLY SHOULD'VE DOCUMENTED THIS AT THE TIME
	$groupbox1 = $_POST['groupbox'];
	$groupbox = mysqli_real_escape_string($conn, $groupbox1);
	$groupbox = htmlentities($groupbox, ENT_QUOTES);
	$websitebox1 = $_POST['websitebox'];
	$websitebox = mysqli_real_escape_string($conn, $websitebox1);
	$websitebox = htmlentities($websitebox, ENT_QUOTES);
	$websitebox = str_replace('&amp;&amp;', '%26%26', $websitebox); // WHY THE DUCK WAS THIS AN ISSUE TO BEGIN WITH?
	$profilebiobox1 = $_POST['profilebiobox'];
	$profilebiobox = mysqli_real_escape_string($conn, $profilebiobox1);
	$profilebiobox = htmlentities($profilebiobox, ENT_QUOTES);
}
$multi .= "UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged';";
if(isset($_POST['sub']) && $avatarbox != ' ' && $avatarbox != '') {
	$multi .= "UPDATE members SET avatar='$avatarbox',grouptype='$groupbox',website='$websitebox',profile='$profilebiobox' WHERE username='$logged';";
echo '<center><div id="success">Saved Changes</div></center>';
}
if (isset($_POST['sub']) && $avatarbox == ' ' || isset($_POST['sub']) && $avatarbox == '') {
	echo '<center><div id="error">Failed to save changes</div></center>';
}

mysqli_multi_query($conn, $multi);
?>
<!DOCTYPE html>
<head>
<title>SomeSN - Account</title>
<link rel="icon" type="image/png" href="/images/somesn.png">
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Settings</span></a></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<?php
if(isset($_POST['sub'])) {
echo '<br>';
}
// FOLKS, DO NOT GO ECHOING A TON OF HTML LIKE THAT. IT'S A HORRIBLE THING TO DO.
if(isset($_POST['sub']) && $avatarbox != ' ' && $avatarbox != '') {
	$profilebiobox = str_replace(array('\\n','\\r'),array(''), $profilebiobox);
	echo '<div id="content" style="padding:0px;text-align:center;">';
	echo '<br>';
	echo '<form method="post" action="" name="profile">';
	echo 'Avatar: <input type="text" name="avatarbox" placeholder="Enter avatar URL" value="'.$avatarbox.'">';
	echo '<hr>';
	echo 'Group: <input type="text" name="groupbox" placeholder="Group name" value="'.$groupbox1.'">';
	echo '<hr>';
	echo 'Website: <input type="text" name="websitebox" placeholder="Website..." value="'.$websitebox1.'">';
	echo '<hr>';
	echo 'Biography:';
	echo '<br>';
	echo '<br>';
	echo '<textarea name="profilebiobox" style="resize:none;height:120px;width:320px;" placeholder="Profile biography">';
	echo $profilebiobox1;
	echo '</textarea>';
}
else {
	echo '<div id="content" style="padding:0px;text-align:center;">';
	echo '<br>';
	echo '<form method="post" action="" name="profile">';
	echo 'Avatar: <input type="text" name="avatarbox" placeholder="Enter avatar URL" value="'.$avatar.'">';
	echo '<hr>';
	echo 'Group: <input type="text" name="groupbox" placeholder="Group name" value="'.$grouptype.'">';
	echo '<hr>';
	echo 'Website: <input type="text" name="websitebox" placeholder="Website..." value="'.$website.'">';
	echo '<hr>';
	echo 'Biography:';
	echo '<br>';
	echo '<br>';
	echo '<textarea name="profilebiobox" style="resize:none;height:120px;width:320px;" placeholder="Profile biography">'.$profile.'</textarea>';
}
?>
<input type="text" name="sub" style="display:none;">
</form>
<div id="submissionbutton" name="submit" style="border-radius:0px 0px 5px 5px;" onclick="document.forms['profile'].submit();">Update</div>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script type="text/javascript">
// MENU CODE START
$(document).ready(function () {
    $('.menu').on('tap', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	$('#menulist').stop(true,false).slideToggle(350);
	updateMenu();
    })
});
function updateMenu()
{
var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp = new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange = function()
{
if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
{
document.getElementById("menulist").innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET","/elements/menu.php",true);
xmlhttp.send(null);
}
// MENU CODE END
</script>
</body>
</html>
