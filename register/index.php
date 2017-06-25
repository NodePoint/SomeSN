<?php
error_reporting(1); // ANYTHING BUT THIS. WHY?
session_start();
if(isset($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'])) {
	$_SERVER['DOCUMENT_ROOT'] = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT']; // BECAUSE I WANTED TO BE A BIT LAZY, I HAD TO USE THIS FIX FOR GODADDY AT THE TIME (ADD-ON DOMAIN THING) -- IT WAS SO THAT I DON'T NEED TO '../' EVERYTIME
}
require $_SERVER['DOCUMENT_ROOT'].'/elements/conn.php'; // SHOULD'VE HAD A CHECK ON IT
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
if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['pin']) && isset($_POST['passconf'])) {
	$user = $_POST['user'];
	$user = mysqli_real_escape_string($conn, $user);
	$user = htmlentities($user, ENT_QUOTES);
	$pass4 = $_POST['pass'];
	$pass3 = mysqli_real_escape_string($conn, $pass4);
	$pass2 = htmlentities($pass3, ENT_QUOTES);
	$pass = hash('sha256', sha1(md5('IDKBFoigbKIUEGIUgVB'.$pass2.'dKDksbvbiksKBFSFS$AFNLAjs')));
	$pin4 = $_POST['pin'];
	$pin3 = mysqli_real_escape_string($conn, $pin4);
	$pin2 = htmlentities($pin3, ENT_QUOTES);
	$pin = hash('sha256', sha1(md5('IDKBFoigbKIUEGIUgVB'.$pin2.'dKDksbvbiksKBFSFS$AFNLAjs')));
	$passconf4 = $_POST['passconf'];
	$passconf3 = mysqli_real_escape_string($conn, $passconf4);
	$passconf2 = htmlentities($passconf3, ENT_QUOTES);
	$passconf = hash('sha256', sha1(md5('IDKBFoigbKIUEGIUgVB'.$passconf2.'dKDksbvbiksKBFSFS$AFNLAjs')));
	// DO NOT USE THE METHOD OF HASHING AND SALTING USED HERE -- GO WITH A STRONGER HASH ONCE LIKE SHA512 AND USE SOMETHING LIKE BCRYPT
	// THERE IS NO NEED FOR HTMLENTITIES() AT ALL FOR ABOVE -- ALPHANUM CHECK IS DONE FOR USERNAMES LATER ON ANYWAY
	// $PASSCONF DOES NOT NEED TO BE ESCAPED AS THIS IS ONLY CHECKED AGAINST IN AN IF STATEMENT AND DOES NOT GO INTO ANY SQL QUERY
}
$ip = $_SERVER['REMOTE_ADDR'];
$useragent = $_SERVER['HTTP_USER_AGENT'];
$useragent = mysqli_real_escape_string($conn, $useragent);
$useragent = htmlentities($useragent, ENT_QUOTES);
$selectone = mysqli_query($conn, "SELECT * FROM members WHERE username='$logged'");
$select = mysqli_query($conn, "SELECT * FROM members WHERE username='$user' COLLATE utf8mb4_general_ci");
$count = mysqli_num_rows($select);
$row = mysqli_fetch_assoc($select);
$rowone = mysqli_fetch_assoc($selectone);
$disabled = $rowone['disabled'];
$n = mysqli_query($conn, "SELECT * FROM members WHERE ip='".$ip."'");
$i = mysqli_num_rows($n);
$spechars = ctype_alnum($user);
$pinc = ctype_digit($pin2);
if ($disabled == 'yes') {
	header('location:'.$denyaccessurl);
	exit;
}
if(isset($_COOKIE['username']) || isset($_COOKIE['password'])) {
	header('location: /');
	exit;
}
echo '<!DOCTYPE html>';
echo '<head>';
echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
echo '<title>SomeSN - Register</title>';
echo '<link rel="icon" type="image/png" href="/images/somesn.png">';
include $_SERVER['DOCUMENT_ROOT'].'/elements/font.php'; // WHY INCLUDE A FILE THAT DOESN'T ACTUALLY HAVE PHP WITHIN -- NO CHECKS FOR ACCESSIBILITY EITHER
echo '<link rel="stylesheet" type="text/css" href="/css/styles.css">';
echo '<meta name="description" content="SomeSN - Register">';
echo '<meta name="keywords" content="SomeSN, Social, Network, Mobile, Tablet, PC, Phone, Chat, Status, Apps">';
echo '</head>';
echo '<body>';
echo '<div id="title"><img id="logo" src="/images/somesn.png" alt="Logo"> <span id="titletext">Register</span></div>';
echo '<div id="sep">&nbsp;</div>';
echo '<center>';
if ($i > 1) {
    $msg = '<div id="error">2 Accounts are already assigned to your current IP, contact the owner to restore your account '.$ip.'</div><br><br>';
	$disable = 'disabled="disabled"';
	$disablesubmit = 'onclick="alert(\'The register is already disabled so I dont know what you are expecting.\');"';
	$disableph = 'placeholder="Disabled"';
	$colour = 'background-color:#ff5f5f;';
}
else {
	$disablesubmit = 'onclick="document.forms[\'registerform\'].submit();"';
	$colour = 'background-color:#48ff3d;';
}
// I WANT TO DIE JUST BY TRYING TO READ THROUGH THIS -- HOW DID I EVEN CREATE THIS MONSTROSITY??
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != $passconf2 && $count == '0')
{
$msg = '<div id="error">Password\'s don\'t match</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0' && $i > 1)
{
$msg = '<div id="error">Editing of source code is not permitted</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '1')
{
$msg = '<div id="error">Username already exists!</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0' && $spechars === FALSE)
{
$msg = '<div id="error">Username must be alphanumerical</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0'  && $spechars === TRUE && strlen($user) == 1)
{
$msg = '<div id="error">Username must be longer than 1 character</div><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0'  && $spechars === TRUE  && strlen($user) != 1 && $pin2 == '')
{
$msg = '<div id="error">PIN is blank</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0'  && $spechars === TRUE  && strlen($user) != 1 && $pin2 == ' ')
{
$msg = '<div id="error">PIN is blank</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0'  && $spechars === TRUE  && strlen($user) != 1 && $pin2 != '' && $pin2 != ' ' && $pinc === FALSE)
{
$msg = '<div id="error">PIN must be numerical</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0'  && $spechars === TRUE  && strlen($user) != 1 && $pin2 != '' && $pin2 != ' ' && $pinc === TRUE && strlen($pin2) != 4)
{
$msg = '<div id="error">PIN must be 4 numbers long</div><br><br>';
}
if(isset($_POST['user']) && $user != '' && $user != ' ' && $pass2 != '' && $pass2 != ' ' && $pass2 == $passconf2 && $count == '0'  && $spechars === TRUE  && strlen($user) != 1 && $pin2 != '' && $pin2 != ' ' && $pinc === TRUE && strlen($pin2) == 4)
{
function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
$srt = generateRandomString();
$insert = mysqli_query($conn, "INSERT INTO members (username, password, ip, useragent, pin, avatar, colour, icon, token, time) VALUES ('$user','$pass','$ip','$useragent','$pin','/images/avatarimg.png','#75c2ff','#8062ff;','".$srt."','".time()."')"); // SHOULD'VE USED THE UNIX_TIMESTAMP(NOW()) INSTEAD OF PHP'S TIME() FUNCTION
setcookie('username', $user, time()+10*365*24*60*60, '/', null, null, true); // COULD'VE ENABLED THE SECURE FLAG FOR THIS
setcookie('password', $pass, time()+10*365*24*60*60, '/', null, null, true);
$msg = '<meta http-equiv="Refresh" content="2;url=/"><div id="success">Registration successful, logging in...</div><br>'; // CHEAP WAY OF GETTING A REDIRECT DONE
$textcolour = 'font-color:#494949;';
$disablevalregone = 'value="'.$user.'"';
$disablevalregtwo = 'value="'.$pass4.'"';
$disablevalregthree = 'value="'.$pin2.'"';
}
echo $msg;
?>
<br>
<div id="content" style="padding:0px;text-align:center;">
<?php
switch($i) {
	case 0:
		echo '<div id="infobar">2 Accounts remaining</div><br>';
		break;
	case 1:
		echo '<div id="infobar"1 Account remaining</div><br>';
		break;
	case 2:
		echo '<div id="infobar">0 Accounts remaining</div><br>';
		break;
}
// DETECTION OF AMOUNT OF ACCOUNTS REGISTERED ON THE CURRENT IP
?>
<form method="post" action="" name="registerform">
Username: <input name="user" type="text" maxlength="15" style="<?php echo $textcolour ?>" <?php echo $disablevalregone ?> <?php echo $disableph ?> <?php echo $disable ?>>
<hr style="width:95%;">
Password: <input name="pass" type="password" maxlength="100" style="<?php echo $textcolour ?>" <?php echo $disablevalregtwo ?> <?php echo $disable ?> <?php echo $disableph ?>>
<hr style="width:95%;">
Confirm Password: <input name="passconf" type="password" maxlength="100" style="<?php echo $textcolour ?>" <?php echo $disablevalregtwo ?> <?php echo $disable ?> <?php echo $disableph ?>>
<hr style="width:95%;">
PIN Code (Remember this!): <input name="pin" type="number" maxlength="4" style="<?php echo $textcolour ?>" <?php echo $disablevalregtwo ?> <?php echo $disable ?> <?php echo $disableph ?>>
</form>
<div id="submissionbutton" style="<?php echo $colour ?>border-radius:0px 0px 8px 8px;" <?php echo $disablesubmit ?>>Register</div>
</div>
<br>
<div id="content" style="padding:0px;">
<div id="info" style="background-color:red;border-radius:5px 5px 0px 0px;">! IMPORTANT !</div>
<br>
<div style="padding:3px;">
Before you register, read the terms (link below) for the rules, platform support and more.
</div>
</div>
<div id="usercounter">
<?php include $_SERVER['DOCUMENT_ROOT'].'/elements/usercounter.php'; ?>
</div>
</center>
</body>
</html>
