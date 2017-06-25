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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,admin,disabled FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack); // WHY WAS IT SET UP LIKE THIS
if($sesschecktwo == '0') {
    // THERE ARE SINGLE QUOTES SURROUNDING A NUMBER IT'S SUPPOSED TO BE CHECKING FOR. I SHIT YOU NOT.
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
    // FORGOT TO PLACE AN EXIT FOR THIS. NOT ONLY THAT BUT THE MYSQLI_CLOSE() FUNCTION IS NOT BEING USED TOO. *VERY* FATAL MISTAKE.
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
if ($admin > 3) {
	header('location:/');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
<title>SomeSN - Staff</title>
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Staff</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<div id="barblock">
This is the staff page, you can perform actions towards accounts here.
</div>
<div class="tab">Username <span class="checkNew"></span></div>
<div id="content">
<form name="userf" method="post" class="subformlock" action="menusections.php" autocomplete="off">
<div style="line-height:5px;">&nbsp;</div>
<input type="text" id="useri" onkeyup="checkNew(this.value)" name="user" placeholder="Username">
<div style="line-height:5px;">&nbsp;</div>
</form>
</div>
<div class="lockbuttons">
<?php
include 'menusections.php';
?>
</div>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/ajax/jquery.mobile.min.js"></script>
<script type="text/javascript">
	function imgError(image) {
		image.onerror = "";
		image.src = "/images/bimg.png";
		return true;
	}
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

checko = '';
function checkNew(value) {
    if(value != checko) {
			checko = value;
			$('.checkNew').html('*');
    }
    else {
			$('.checkNew').html('');
    }
};

$(document).ready(function () {
	$('.subformlock').submit(function () {
		$('.status').html('<div style="text-align:center;"><img src="/images/loading.gif" width="30px" height="30px"><br>Loading...</div>');
		var msg = document.getElementById("useri").value;
		var trimmed = $.trim(msg);
    if(trimmed != '') {
			checko = msg;
      $.post('menusections.php', $('.subformlock').serialize(), function (data, user) {
      	if(data != '') {
          $('.lockbuttons').html(data);
        }
      });
    }
		$("#msg").val('');
    return false;
	});
});
</script>
</body>
</html>
