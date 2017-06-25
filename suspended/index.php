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
$sessjack = $select = mysqli_query($conn, "SELECT username,password,disabled,disableduser,disabledcode FROM members WHERE username='$logged' AND password='$password'");
$sesschecktwo = $count = mysqli_num_rows($sessjack);
if($sesschecktwo == '0') {
	header('location:/logout');
	exit;
}
$row = mysqli_fetch_assoc($select);
$disabled = $row['disabled'];
$whodisabled = $row['disableduser'];
$disabledcode = $row['disabledcode'];
$disabledcode = mysqli_real_escape_string($conn, $disabledcode);
$disabledcode = htmlentities($disabledcode, ENT_QUOTES);
if(isset($_POST['code'])) {
    $code = $_POST['code'];
    $code = mysqli_real_escape_string($conn, $code);
    $code = htmlentities($code, ENT_QUOTES);
}
else {
    $code = '';
}
if ($disabled == '') {
	header('location:/');
	exit;
}
if(isset($_POST['code']) && $disabledcode == $code && $code != '' && $code != ' ') {
mysqli_query($conn, "UPDATE members SET disabled='' WHERE id='1'");
mysqli_query($conn, "UPDATE members SET disabledreason='' WHERE id='1'");
mysqli_query($conn, "UPDATE members SET disableduser='' WHERE id='1'");
mysqli_query($conn, "INSERT INTO logger (uid, user, userinvolved, activity, reason) VALUES ('1','$logged','[SELF]','[OWNER ENABLED]','Code valid')");
header('location:/');
exit;
}
// ONE OF THE SERIOUS FLAWS -- DELETE THIS -- WE DON'T WANT *ANYONE* TO BE ABLE TO UNSUSPEND THEMSELVES THANKS TO BRUTEFORCE AGAINST A SET CODE

mysqli_query($conn,"UPDATE members SET time=UNIX_TIMESTAMP(NOW()) WHERE username='$logged'");
?>
<!DOCTYPE html>
<head>
<title>SomeSN - Account Suspended</title>
<?php include '../elements/font.php'; ?>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="UTF-8">
</head>
<body>
<div id="title"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Suspended</span></div>
<div id="sep">&nbsp;</div>
<center>
<div id="content" style="padding:0px;text-align:center;">
<div id="barblock">
Your account was suspended.
</div>
<div class="tab">Affected account</div>
<?php
echo $logged;
if($logged == 'HARDCODED_ADMIN_USERNAME') { // DON'T DO THIS -- AT LEAST MAKE IT DYNAMIC IF YOU DO. KTHX
echo '<div class="tab">Who did this?</div>';
echo '<div id="content" style="padding:0px;text-align:center;">';
echo $whodisabled;
echo '</div>';
}
?>
<div class="tab">Why?</div>
<div style="padding:3px;text-align:left;">
The activity under your account has violated the terms of service.
<br>
<br>
If caught bypassing this suspension, your main will get an extended suspension on your main account if it's not permanent. Addionally, the account used to bypass will get permanently suspended.
</div>
<div class="tab">
How can I appeal?
</div>
<div style="padding:3px;text-align:left;">
You can email us at <a href="mailto:abuse@example.com" style="color:blue;">abuse@example.comk</a> in regards to the suspension, along with the suspension ID.
<br>
From there, the suspension can be discussed.
</div>
</div>
<?php
if($logged == 'HARDCODED_ADMIN_USERNAME') { // SAME AS BEFORE -- DON'T DO THIS
    echo '<div id="content" style="padding:0px;text-align:center;">';
    echo '<div class="tab">Unlock</div>';
    echo '<br>';
    echo '<form method="post" action="" name="enable">';
    echo '<input type="password" name="code" placeholder="Enter code">';
    echo '<br>';
    echo '<div id="submissionbutton" onclick="document.forms[\'enable\'].submit();">Unlock</div>';
    echo '</form>';
    echo '</div>';
} 
?>
</center>
<div id="footer">
<?php include '../elements/usercounter.php'; ?>
</div>
</body>
</html>
