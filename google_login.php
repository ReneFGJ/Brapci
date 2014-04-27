<?php
require("cab.php");
require($include.'sisdoc_debug.php');
########## Google Settings.. Client ID, Client Secret #############
$google_client_id = '34789478983.apps.googleusercontent.com';
$google_client_secret = '6r1DSM8kQ2uEMA2Qy9fRz94N';
$google_redirect_url = 'http://www.brapci.inf.br/';
$google_developer_key = 'AIzaSyDJO_5uYJdPpi4Bpb0LyGh8QJcOAcXfIak';


##############################################

//include google api files
require_once '_class/src_google/Google_Client.php';
require_once '_class/src_google/contrib/Google_Oauth2Service.php';

//start session
session_start();

echo ($id++).'.';
$gClient = new Google_Client();
echo ($id++).'.';
$gClient->setApplicationName('Brapci');
echo ($id++).'.';
$gClient->setClientId($google_client_id);
echo ($id++).'.';
$gClient->setClientSecret($google_client_secret);
echo ($id++).'.';
$gClient->setRedirectUri($google_redirect_url);
echo ($id++).'.';
$gClient->setDeveloperKey($google_developer_key);
echo ($id++).'.';

$google_oauthV2 = new Google_Oauth2Service($gClient);
echo ($id++).'.';
//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset']))
{
unset($_SESSION['token']);
$gClient->revokeToken();
header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
}
echo ($id++).'.';
if (isset($_GET['code']))
{
$gClient->authenticate($_GET['code']);
$_SESSION['token'] = $gClient->getAccessToken();
header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
return;
}
echo ($id++).'.';
if (isset($_SESSION['token']))
{
$gClient->setAccessToken($_SESSION['token']);
}
echo ($id++).'.(FIM)';
if ($gClient->getAccessToken())
{
	//Get user details if user is logged in
	$user = $google_oauthV2->userinfo->get();
	$user_id = $user['id'];
	$user_name = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	$profile_url = filter_var($user['link'], FILTER_VALIDATE_URL);
	$profile_image_url = filter_var($user['picture'], FILTER_VALIDATE_URL);
	$personMarkup = "$email<div><img src='$profile_image_url?sz=50′></div>";
	$_SESSION['token'] = $gClient->getAccessToken();
}
else
{
	//get google login url
	$authUrl = $gClient->createAuthUrl();
}
echo ($id++).'.(A)';
//HTML page start

echo '<h1>Login with Google</h1>';

if(isset($authUrl)) //user is not logged in, show login button
{
echo '<a class="login" href="'.$authUrl.'"><img src="_class/src_google/img/google-login-button.png" /></a>';
}
else // user logged in
{
/* connect to mysql */

//compare user id in our database
$result = db_query("SELECT COUNT(google_id) FROM google_users WHERE google_id=$user_id");
if($result === false) {
die(mysql_error()); //result is false show db error and exit.
}

$UserCount = db_read($result);

if($UserCount[0]) //user id exist in database
{
echo 'Welcome back '.$user_name.'!';
}else{ //user is new
echo 'Hi '.$user_name.', Thanks for Registering!';
@mysql_query("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url')");
}

echo '<br /><a href="'.$profile_url.'" target="_blank"><img src="'.$profile_image_url.'?sz=50″ /></a>';
echo '<br /><a class="logout" href="?reset=1″>Logout</a>';

//list all user details
echo '<pre>';
print_r($user);
echo '</pre>';
}

echo '</body></html>';
?>