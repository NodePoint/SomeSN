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

$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,admin,token,pin FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$admin = $row['admin'];
$disabled = $row['disabled'];
$token = $row['token'];
$pin = $row['pin'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
$login_attempts = mysqli_query($conn, 'SELECT id,type,username FROM security WHERE type="1" AND username="'.$logged.'" ORDER BY id DESC');
$login_attempts = mysqli_num_rows($login_attempts);
$other_ip = mysqli_query($conn, 'SELECT id,type,username FROM security WHERE type="2" AND username="'.$logged.'" ORDER BY id DESC');
$other_ip = mysqli_num_rows($other_ip);
$pass_attempts = mysqli_query($conn, 'SELECT id,type,username FROM security WHERE type="3" AND username="'.$logged.'" ORDER BY id DESC');
$pass_attempts = mysqli_num_rows($pass_attempts);
$pin_attempts = mysqli_query($conn, 'SELECT id,type,username FROM security WHERE type="4" AND username="'.$logged.'" ORDER BY id DESC');
$pin_attempts = mysqli_num_rows($pin_attempts);
$reg_attempts =  mysqli_query($conn, 'SELECT id,type,username FROM security WHERE type="5" AND username="'.$logged.'" ORDER BY id DESC');
$reg_attempts = mysqli_num_rows($reg_attempts);
$total = $login_attempts + $other_ip + $pass_attempts + $pin_attempts + $reg_attempts;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SomeSN - Security</title>
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Security</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<div id="content" style="text-align:center;">Security panel allows you to keep track and manage your account's security stats.</div>
<div class="tab">Stats</div>
<div id="content" style="margin-left:auto;margin-right:auto;text-align:center;">
<?php
if ($login_attempts <= 0 && $other_ip <= 0 && $pass_attempts <= 0 && $pin_attempts <= 0 && $reg_attempts <= 0) {
echo 'No records available.';
echo '<br>';
echo 'This will be enabled when suspicious activity goes on that regards your account directly.';
echo '</div>';
}
else {
// DON'T DO THIS !
echo '<div id="canvas-holder" style="width:100%;">';
echo '<canvas id="chart-area" height="200%">';
echo '</div>';
echo '<script type="text/javascript">';
echo 'var pieData = [';
echo '				{';
echo '				value: '.$login_attempts.',';
echo '				color: "#F7464A",';
echo '				highlight: "#FF5A5E",';
echo '				label: "Login attempts"';
echo '			},';
echo '				{';
echo '				value: '.$other_ip.',';
echo '				color: "#46BFBD",';
echo '				highlight: "#5AD3D1",';
echo '				label: "Login from other IP\'s"';
echo '				},';
echo '				{';
echo '				value: '.$pass_attempts.',';
echo '				color: "#FDB45C",';
echo '				highlight: "#FFC870",';
echo '				label: "Password change attempts"';
echo '			},';
echo '			{';
echo '				value: '.$pin_attempts.',';
echo '				color: "#949FB1",';
echo '				highlight: "#A8B3C5",';
echo '				label: "Pin change attempts"';
echo '			},';
echo '			{';
echo '				value: '.$reg_attempts.',';
echo '				color: "#4D5360",';
echo '				highlight: "#616774",';
echo '				label: "Register attempts (as \''.$logged.'\')"';
echo '			}';
echo '		];';
echo '		window.onload = function(){';
echo '			var ctx = document.getElementById("chart-area").getContext("2d");';
echo '			window.myPie = new Chart(ctx).Pie(pieData);';
echo '		};';
echo '</script>';
echo '<span style="font-size: 10px;">Tap or hover for results</span>';
echo '</div>';
echo '<div class="tab">List <span id="circleind">';
if($total > 99) {
echo '99+';
}
else {
echo $total;
}
echo '</span></div>';
echo '<div id="content">';
echo '<span style="background-color:#F7464A;width:13px;height:13px;display:inline-block;vertical-align:middle;">&nbsp;</span> Login Attempts: '.$login_attempts.' <input type="checkbox" class="checkbox" name="chkb" value="lattem" style="float:right;">';
echo '<br>';
echo '<span style="background-color:#46BFBD;width:13px;height:13px;display:inline-block;vertical-align:middle;">&nbsp;</span> Login from other IP\'s: '.$other_ip.' <input type="checkbox" class="checkbox" name="chkb" value="oip" style="float:right;">';
echo '<br>';
echo '<span style="background-color:#FDB45C;width:13px;height:13px;display:inline-block;vertical-align:middle;">&nbsp;</span> Password change attempts: '.$pass_attempts.' <input type="checkbox" class="checkbox" name="chkb" value="pattem" style="float:right;">';
echo '<br>';
echo '<span style="background-color:#949FB1;width:13px;height:13px;display:inline-block;vertical-align:middle;">&nbsp;</span> Pin change attempts: '.$pin_attempts.' <input type="checkbox" class="checkbox" name="chkb" value="piattem" style="float:right;">';
echo '<br>';
echo '<span style="background-color:#4D5360;width:13px;height:13px;display:inline-block;vertical-align:middle;">&nbsp;</span> Register attempts (as \''.$logged.'\'): '.$reg_attempts.' <input type="checkbox" class="checkbox" name="chkb" value="regattem" style="float:right;">';
echo '</div>';
echo '<div class="tab">Manage data</div>';
echo '<div id="content">';
echo '<form name="pin" action="subval/" method="post" autocomplete="off">';
echo '<input type="number" name="pin" placeholder="PIN">';
echo '<div style="line-height:5px;">&nbsp;</div>';
echo '<div class="radio">';
echo '<input type="radio" name="sectog" id="loggedip" value="loggedip" checked="checked"> <label for="loggedip" class="radiob">Display logged IP\'s</label>';
echo '<br>';
echo '<input type="radio" name="sectog" value="clear" id="clr"> <label for="clr" class="radiob">Clear stats</label>';
echo '<div style="line-height:5px;">&nbsp;</div>';
echo '<input type="submit" class="mainbutton" value="Apply">';
echo '</form>';
echo '</div>';
echo '</div>';
}
?>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</center>
<script type="text/javascript" src="Chart.js"></script>
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
