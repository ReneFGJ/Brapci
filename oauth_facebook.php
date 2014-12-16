<?php
require("cab.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])) {
	// Informe o seu App ID abaixo
	$appId = $user->face_id;
	// Digite o App Secret do seu aplicativo abaixo:
	$appSecret = $user->face_app;
	// Url informada no campo "Site URL"
	$redirectUri = urlencode($user->face_redirect);
	// Obtém o código da query string
	$code = $_GET['code'];
	// Monta a url para obter o token de acesso e assim obter os dados do usuário
	$token_url = "https://graph.facebook.com/oauth/access_token?" . "client_id=" . $appId . "&redirect_uri=" . $redirectUri . "&client_secret=" . $appSecret . "&code=" . $code;
	$contents = fileload($token_url);

	if ($contents) {
		$params = null;
		parse_str($contents, $params);
		if (isset($params['access_token']) && $params['access_token']) {
			$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
			$content = fileload($graph_url);
			
			$userx = json_decode($content);
			
			// nesse IF verificamos se veio os dados corretamente
			if (isset($userx -> email)) {
				$email = trim($userx -> email);
				$id = $user->recupera_id($email);
				$_SESSION['user_id'] = $id;
				$_SESSION['user_email'] = $email;
				$_SESSION['user_nivel'] = $nivel;
				$_SESSION['user_nome'] = $userx -> name;
				$_SESSION['user_genero'] = $userx -> gender;
				$_SESSION['user_idioma'] = $userx -> locale;
				$_SESSION['user_localizacao'] = $userx -> location -> name;
				$_SESSION['uid_facebook'] = $userx -> id;
				$_SESSION['user_facebook'] = $userx -> username;
				$_SESSION['link_facebook'] = $userx -> link;
				if (strlen($id)==0)
					{
						$user->user_insert($email, $userx -> name, $pais, $userx -> location -> name, $userx -> link, $image, $userx -> gender, 'F');
						$user->updatex();
						$id = $user->recupera_id($email);						
					}
				redirecina("http://www.brapci.inf.br/index.php");
			}
		} else {
			echo "Erro de conexao com Facebook";
			exit(0);
		}

	} else {
		echo "Erro de conexao com Facebook - 2";
		exit(0);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])) {
	echo 'Permissao nao concedida';
}

function fileload($token_url)
	{
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $token_url);
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
?>
