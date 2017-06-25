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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,priv FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}

$priv = $row['priv'];
$priv = explode(',', $priv);
if(in_array('owner', $priv) || in_array('community_manager', $priv) || in_array('links_mod', $priv)) {
	$ispriv = 1;
}
else {
    $ispriv = 0;
} // UNUSED

mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");

if(isset($_GET['url'])) {
	$url = $_GET['url'];
	$url = urldecode($url);
	$url = htmlentities($url, ENT_QUOTES);
	$url = trim($url);
	if($url != '' && $url != ' ') {
		if (!preg_match("~^(?:https?://)~i", $url)) {
			$url = "http://" . $url;
		}
	}
}
if(isset($_GET['url']) && $url != '' && $url != ' '&& substr(strrchr($url, '.'), 1) != '' && substr(strrchr($url, '.'), 1) != ' ') {
	$urlv = $url;
	preg_match('@^(?:https?://)?([^/]+)@i', $urlv, $matches);
	$host = $matches[1];
	preg_match('/[^.]+\.[^.]+$/', $host, $matches);
	$urlv =  $matches[0];
	$urlv = strtolower($urlv);
	$success = True;
}
else {
	$success = False;
	$urlv = '';
}

/*
# $url can be used for the 'I'll take the risk' selection, if blacklisted or not on the list.
# $urlv is for placing in the database query, check if it matches either white or the black list.
# If it's the whitelist, do a staright redirect
*/
// WTF WAS I TRYING TO SAY HERE?
$urlinfom = mysqli_query($conn, "SELECT site,message,ver_type FROM link_verification WHERE site='$urlv'");
$urlinfo = mysqli_fetch_assoc($urlinfom);
$access = $urlinfo['ver_type'];
$exists = mysqli_num_rows($urlinfom);
if($exists != 0 && $access == 1) {
    mysqli_close($conn);
    $url = html_entity_decode($url, ENT_QUOTES);
    header('location:'.$url);
    exit;
}

$message = $urlinfo['message'];
?>
<!DOCTYPE html>
<head lang="en">
<title>SomeSN - Safelink</title>
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</div>
</head>
<body>
<div id="title"><a href="/"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Safelink</a></span></div>
<div class="menu"><span class="menutext"></span></div>
<div id="menulist">
<?php include '../elements/menu.php'; ?>
</div>
<div id="sep">&nbsp;</div>
<center>
<div id="barblock">
This is a URL verification tool, to try to keep you safe when browsing external links.
</div>
<?php
if(isset($_GET['url']) && $url != '' && $url != ' ' && $success === True) {
	echo '<div class="tab">';
	echo 'Info';
	echo '</div>';
	echo '<div id="content">';
    if($exists != 0) {
		if($message != '') {
    	    echo 'This link was blocked for the following reason:<br>'.nl2br($message);
		}
		else {
			echo 'This link was blocked as it violates the <a href="/tos" style="color:blue;">ToS</a>.';
		}
    }
    else { ?>
        You're now leaving SomeSN to visit an external site.
        <br>
	<?php
    }
    if($exists == 0) {
        echo '<br>';
        echo '<br>';
        echo '<a href="'.$url.'" style="color:blue;">I\'ll take my chances.</a>';
    }
    if($ispriv == 1) { ?>
        <br>
        <br>
        Bad link? Give a reason to block it.
    <?php
    }
}
elseif(isset($_GET['url']) && $url != '' && $url != ' ' && $success === False) { // WHY IS THE BOOLEAN NOT LOWERCASE ONLY?? >:(
	echo 'URL not valid.';
}
else {
	echo 'Please select a hyperlink posted on the site.';
}
?>
</div>
</center>
<div id="footer">
<?php include '../elements/usercounter.php'; // DO. CHECKS. ?>
</div>
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
