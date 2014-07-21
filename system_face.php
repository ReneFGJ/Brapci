<?php
require("_class/_class_face.php");
print_r($facebook);
echo '<HR>';
var_dump($facebook);
// Get User ID
$user = $facebook->getUser();

echo "<br />";
echo '<font color="red">';
var_dump($user);
echo '</font>';

if ($user) {
try {
    $user_profile = $facebook->api('/me');
} catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
}
}

if ($user) {
	echo 'LOGADO';
    $logoutUrl = $facebook->getLogoutUrl();
} else {
	echo 'NÃO LOGADO';
    $loginUrl = $facebook->getLoginUrl();
	echo '<A HREF="'.$loginUrl.'">logar</A>';
}

$naitik = $facebook->api('/naitik');
?>
<font color="blue">
<?php var_dump($user); ?>
</font>

<h1>php-sdk</h1>

<?php if ($user): ?>
<a href="<?php echo $logoutUrl; ?>">Logout</a>
<?php else: ?>
<?php echo "LoginURL:"; var_dump($loginUrl);?>
<div>
 <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>

</div>
<?php endif ?>

<h3>PHP Session</h3>
<pre><?php print_r($_SESSION); ?></pre>

<?php if ($user): ?>
<h3>You</h3>
<img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

<h3>Your User Object (/me)</h3>
<pre><?php print_r($user_profile); ?></pre>
<?php else: ?>
<strong><em>You are not Connected.</em></strong>
<?php endif ?>

<h3>Public profile of Naitik</h3>
<img src="https://graph.facebook.com/naitik/picture">
<?php echo $naitik['name']; ?>