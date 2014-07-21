<?php
class face
	{
		var $appID = '284206068404926';
		var $secret = '8205ab12e027ac79b0fb1163730b1354';
		var $site = 'http://www.brapci.inf.br';
		var $erro_code = '';
		var $erro_msg = '';
		var $user;
		
		function userID()
			{
				$sx = '
						
						<script>(function(d, s, id) {
  						var js, fjs = d.getElementsByTagName(s)[0];
  						if (d.getElementById(id)) return;
  						js = d.createElement(s); js.id = id;
  						js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId='.$this->appID.'";
  						fjs.parentNode.insertBefore(js, fjs);
						}(document, \'script\', \'facebook-jssdk\'));</script>
					';
				$sx .= '<div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false"></div>
						<div id="fb-root"></div>
					';
				return($sx);
				}
		
		function start()
			{
				global $facebook;
			  if($user){
				//==================== Single query method ======================================
					try{
						// Proceed knowing you have a logged in user who's authenticated.
						$user_profile = $facebook->api('/me');
						$user_info	= $facebook->api('/' . $user);
						$feed		= $facebook->api('/' . $user . '/home?limit=50');
						$friends_list	= $facebook->api('/' . $user . '/friends');
						$photos		= $facebook->api('/' . $user . '/photos?limit=6');
						echo '--===>>'.$user_info;	
						error_log($e);
					}catch(FacebookApiException $e){
						error_log($e);
						$user = NULL;
					}
				//==================== Single query method ends =================================
				}
			  			  				
			}

		function file_get_contents($url)
			{
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024);
				$contents = curl_exec($ch);
				
				if (curl_errno($ch)) {
  					echo curl_error($ch);
	  				echo "\n<br />";
  					$contents = '';
				} else {
	  				curl_close($ch);
				}
				if (!is_string($contents) || !strlen($contents)) {
					echo "Failed to get contents.";
					$contents = '';
				}
				return($contents);				
			}
		
		
		function valida_login()
			{
				global $facebook;
				
				print_r($facebook);
				echo '<HR>';
				echo $facebook->getUser();
				exit;
				
				$code = $_GET['code'];
				$state = $_GET['state'];

				if(strlen($code) >0){
					$redirect_url = $this->site.'/login.php';
					$link="https://graph.facebook.com/oauth/access_token?canvas=1&client_id=".$this->appID."&redirect_uri=".urlencode($redirect_url)."&client_secret=".$this->secret."&code=".$code;
					$string = $this->file_get_contents($link);
					$ret = json_decode($string);
					$ret2 = $ret->error;
					
					$erro_code = $ret2->code;
					
					switch ($erro_code)
						{
						case '':
							
							$auth_token=splitx('&',$string.'&');
							$token = $auth_token[0];
							$auth_token=substr($token,13,300);	
							echo '--><B>'.$auth_token.'</B>';	
							// Set a new access token, by first getting it via means other than the SDK
							$facebook->setAccessToken($new_access_token);
							print_r($facebook);

							echo("Hello " . $user->name);
							break;
						case '100':
							$this->erro_code = '100';
							$this->erro_msg = 'Codigo de autorização expirou, faça login novamente';
							return(0);
							break;	
						default:
							echo '<HR>'.$string.'<HR>';
							$this->erro_code = $erro_code;
							$this->erro_msg = 'Sem mensagem '.$erro_code;
							
							
							
							return(0);
							break;									
						}
					
					echo '<HR>'.$string.'</hr>';
					exit;
					$auth_token=substr($string, 13, 150);

					$graph_url = "https://graph.facebook.com/me?access_token=".$auth_token;
					$content = $this->file_get_contents($graph_url);
					echo '<HR><PRE>'.$content.'</PRE><HR>';
					$user = json_decode($content);
					
					echo("Hello " . $user->name);
					}
			}
		
		function logout()
			{
				global $facebook;
				$params = array( 'next' => 'http://www.brapci.inf.br/logout.php' );
				$url = $facebook->getLogoutUrl($params); // $params is optional.
				$sx = '<A HREF="'.$url.'">sair</A>';
				return($sx);			
			}
		
		function login()
			{
				global $facebook;
				$params = array(
  					'scope' => '',
  					'redirect_uri' => 'http://www.brapci.inf.br/login.php'
					);

				$loginUrl = $facebook->getLoginUrl($params); 				
  				$sx = '<a href="' . $loginUrl . '">Logar com Facebook</a>';
			return($sx);				
			}
		
		function comentario($link='')
			{
				$sx = '
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
  						var js, fjs = d.getElementsByTagName(s)[0];
  						if (d.getElementById(id)) return;
    					js = d.createElement(s); js.id = id;
    					js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId='.$this->appID.'";
    					fjs.parentNode.insertBefore(js, fjs);
  					}(document, \'script\', \'facebook-jssdk\'));</script>				
				';
				$sx .= '<div class="fb-comments" data-href="'.$link.'" data-numposts="5" data-colorscheme="light"></div>';
				return($sx);				
			}
		function likeded()
			{
				
			}
		function liked()
			{
				$sx = '
				<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId='.$this->appID.'";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';
				/* NOVO */
				
				$sx .= '<div class="fb-like-box" data-href="https://www.facebook.com/brapci.ci" data-width="100%" data-height="240" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>';
				//$sx .= '<div class="fb-like" data-href="https://www.facebook.com/brapci.ci" data-layout="box_count" data-action="like" data-show-faces="true" data-share="true"></div>';
				return($sx);
			}
			
		function share()
			{
				$sx = '
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
  					var js, fjs = d.getElementsByTagName(s)[0];
  					if (d.getElementById(id)) return;
  					js = d.createElement(s); js.id = id;
  					js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId='.$this->appID.'";
  					fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';
					
				$sx .= '<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-width="300" data-type="button_count"></div>';
				return($sx);
			}				
	}
?>