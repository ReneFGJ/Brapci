<?php
/**
 * Class Login do Google
 * @category Login
 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
 * @copyright Copyright (c) 2011 - sisDOC.com.br
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @package Classe
 * @version 0.14.18
 */

	//include google api files
	require 'src_google/Google_Client.php';
	require 'src_google/contrib/Google_Oauth2Service.php';
  
class google_api
	{
	########## Google Settings.. Client ID, Client Secret #############
	
	var $google_client_id = '205743538602-t6i1hj7p090g5jd4u70614vldnhe7143.apps.googleusercontent.com';
	var $google_client_secret = 'AMhQ7Vfc7Lpzi_ZVZKq4wbWV';
	var $google_redirect_url = 'http://www.brapci.inf.br/';
	var $google_developer_key = 'AIzaSyCye-Cn7oVE4eAO5411u_FHd7HkhdDewXI';
	var $google_analytics_id = '';
		
	
	var $authUrl;
	var $it = 0;
	
	function analytics()
		{
			$sx = '
				<script type="text/javascript">
					var _gaq = _gaq || [];
  					_gaq.push([\'_setAccount\', \''.$this->google_analytics_id.'\']);
  					_gaq.push([\'_trackPageview\']);

  					(function() {
    					var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
						ga.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'stats.g.doubleclick.net/dc.js\';    
    					var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  					})();
				</script>
				';
				return($sx);
		}
	
	function start()
		{
			global $gClient;
			
			$this->it = 0;
			$gClient = new Google_Client();
			$gClient->setApplicationName('Brapci');
			$gClient->setClientId($this->google_client_id);
			$gClient->setClientSecret($this->google_client_secret);
			$gClient->setRedirectUri($this->google_redirect_url);
			$gClient->setDeveloperKey($this->google_developer_key);

			$google_oauthV2 = new Google_Oauth2Service($gClient);

			//If user wish to log out, we just unset Session variable
			if (isset($_REQUEST['reset']))
				{
					$this->it = 'reset';
					unset($_SESSION['token']);
					$gClient->revokeToken();
					header('Location: ' . filter_var($this->google_redirect_url, FILTER_SANITIZE_URL));
				}

			/* Autenticado com retorno do code */
			if (isset($_GET['code']))
				{
					$this->it = 'code';
					$gClient->authenticate($_GET['code']);
					$_SESSION['token'] = $gClient->getAccessToken();
					header('Location: ' . filter_var($this->google_redirect_url, FILTER_SANITIZE_URL));
					return;
				}

			if (isset($_SESSION['token']))
				{
					$this->it = 'token';
					$gClient->setAccessToken($_SESSION['token']);
				}

			if ($gClient->getAccessToken())
				{
					$this->it = 'AccessToken';
					//Get user details if user is logged in
					$this->user = $google_oauthV2->userinfo->get();
					$this->user_id = $user['id'];
					$this->user_name = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
					$this->email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
					$this->profile_url = filter_var($user['link'], FILTER_VALIDATE_URL);
					$this->profile_image_url = filter_var($user['picture'], FILTER_VALIDATE_URL);
					$this->personMarkup = "$email<div><img src='$this->profile_image_url?sz=20′></div>";
					$_SESSION['token'] = $gClient->getAccessToken();
				}
			else
				{
					$this->it = 'login';
					//get google login url
					$this->authUrl = $gClient->createAuthUrl();
				}
	return(1);
	}
}

/* Inincio do login da goolge */
$google = new google_api;
$google->start();
	
if(isset($google->authUrl)) //user is not logged in, show login button
	{
		if (!isset($google_login_link)) { $google_login_link = ''; }
		$google_login_link .= '<a class="login" href="'.$google->authUrl.'">
		<img src="'.$http.'_class/src_google/img/google-login-button.png" height="30" /></a><nobr>';		
	} else {
		$user = $google->user;
		$user_name  = $user['name'];
		$user_email = $user['email'];
		$user_image = $user['picture'];
		$link_logout = '<a href="index.php?reset=1"><font color="A0A0A0">Sair</FONT></a>';
		$email_valid = $user['verified_email'];
		$user_id = $user['id'];
	}
?>