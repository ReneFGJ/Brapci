<?php
class oauth {
	/* Google */
	var $auth_google = 1;
	var $google_redirect = 'http://www.brapci.inf.br/oauth_google.php';
	var $google_key = '205743538602-t6i1hj7p090g5jd4u70614vldnhe7143.apps.googleusercontent.com';
	var $google_key_client = 'AMhQ7Vfc7Lpzi_ZVZKq4wbWV';
	/* Windows */
	var $auth_microsoft = 1;
	var $microsoft_id = '0000000040124367';
	var $microsoft_key = 'JOlz8eVtECgfKt0MKTg0I-aXZrUboW21';

	/* Facebook */
	var $auth_facebook = 1;
	var $face_id = '547858661992170';
	var $face_app = '06d0290245ca0dad338d821792df96aa';
	var $face_url = 'https://www.facebook.com/dialog';
	var $face_redirect = 'http://www.brapci.inf.br/oauth_facebook.php';

	/* Linked in*/
	var $auth_linkedin = 1;
	var $linkedin_url = "https://www.linkedin.com/uas/oauth2/authorization";
	var $linkedin_token = "https://www.linkedin.com/uas/oauth2/accessToken";
	var $linkedin_key = '77rk2tnk7ykhoi';
	var $linkedin_key_user = '0f68b98f-4e38-4980-b631-4f64520c9c2e';
	var $linkedin_key_secret = '06fd1eff-0c5b-4d95-bb7b-681deb588919';
	var $linkedin_redirect = 'http://www.brapci.inf.br/oauth_linkedin.php';

	var $name;
	var $email;
	var $cidade;
	var $id;
	var $autenticado;
	
	var $tabela = "users";

	function recupera_id($email) {
		$sql = "select * from " . $this -> tabela . " where us_email = '" . $email . "'";
		$rlt = db_query($sql);
		return (trim($line['us_codigo']));
	}
	
	function logout()
		{
		$_SESSION['user_id'] ='';
		$_SESSION['user_nome'] ='';
		$_SESSION['user_localizacao'] ='';
		$_SESSION['user_email'] ='';
		return(1);			
		}

	function user_insert($email, $nome, $pais, $cidade, $link, $image, $genero, $autenticador) {
		if (strlen($email) == 0) {
			return ('');
		}
		$data = date("Ymd");
		$genero = trim($genero);
		if ($genero == 'male') { $genero = 'MASC';
		}
		if ($genero == 'female') { $genero = 'FEME';
		}
		$codigo = '';
		$sql = "insert into " . $this -> tabela . " 
					(
					us_nome, us_email, us_cidade,
					us_pais, us_codigo, us_link,
					us_ativo, us_nivel, us_image,
					us_genero, us_verificado, us_autenticador,
					us_cadastro
					) values (
					'$nome','$email','$cidade',
					'$pais','$codigo','$link',
					1,'0','$image',
					'$genero','1','$autenticador',
					$data
					)
			";
		$rlt = db_query($sql);
		$this -> updatex();
	}

	function updatex() {
		global $base;
		$c = 'us';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update " . $this -> tabela . " set $c2 = lpad($c1,$c3,0) where $c2='' ";
		if ($base == 'pgsql') { $sql = "update " . $this -> tabela . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' ";
		}
		$rlt = db_query($sql);
	}

	function token() {
		$this -> id = $_SESSION['user_id'];
		$this -> name = $_SESSION['user_nome'];
		$this -> cidade = $_SESSION['user_localizacao'];
		$this -> email = $_SESSION['user_email'];
		if (strlen($_SESSION['user_email']) > 0)
			{
				$this->autenticado = 1;
			} else {
				$this->autenticado = 0;
			}
		return(1);
	}

	function get_oauth2_token($code) {
		$oauth2token_url = "https://accounts.google.com/o/oauth2/token";
		$clienttoken_post = array("code" => $code, "client_id" => $this -> google_key, "client_secret" => $this -> google_key_client, "redirect_uri" => $this -> google_redirect, "grant_type" => "authorization_code");

		$curl = curl_init($oauth2token_url);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$json_response = curl_exec($curl);
		curl_close($curl);

		$authObj = json_decode($json_response);

		if (isset($authObj -> refresh_token)) {
			//refresh token only granted on first authorization for offline access
			//save to db for future use (db saving not included in example)
			global $refreshToken;
			$refreshToken = $authObj -> refresh_token;
		}
		$accessToken = $authObj -> access_token;
		$_SESSION['access_token'] = $accessToken;
		$_SESSION['token_type'] = $authObj -> token_type;
		$_SESSION['token_refesh'] = $authObj -> refresh_token;
		return $accessToken;
	}

	function login() {
		$sx = '';
		if ($this -> auth_google == 1) { $sx .= $this -> google_login();
		}
		if ($this -> auth_microsoft == 1) { $sx .= $this -> microsoft_login();
		}
		if ($this -> auth_facebook == 1) { $sx .= $this -> facebook_login();
		}
		if ($this -> auth_linkedin == 1) { $sx .= $this -> linkedin_login();
		}
		return ($sx);
	}

	/* Google Development */

	function google_login_link() {
		$link = 'https://accounts.google.com/o/oauth2/auth?';
		$link .= 'response_type=code&';
		$link .= 'redirect_uri=' . $this -> google_redirect;
		$link .= '&client_id=' . $this -> google_key;
		//$link .= '&scope=https%3A//www.googleapis.com/auth/userinfo.profile+https%3A//www.googleapis.com/auth/userinfo.email';
		$link .= '&scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email';
		$link .= '&access_type=offline';
		$link .= '&approval_prompt=force';
		/* auto ou force */
		$link = '<A HREF="' . $link . '">';
		return ($link);
	}

	function google_login() {
		$img = '<img src="img/login_google_en.png" border="0">';
		$link = $this -> google_login_link();
		$sx = $link . $img . '</A>';
		return ($sx);
	}

	/* Facebook */
	function facebook_login_link() {
		$csrf = md5('linked' . date("ymd"));
		$_SESSION['linkid'] = $csrf;
		$link = $this -> face_url . '/oauth?response_type=code';
		$link .= '&client_id=' . $this -> face_id;
		$link .= '&redirect_uri=' . $this -> face_redirect;
		$link .= '&scope=email,read_friendlists,user_location';
		$link .= '&response_type=token';

		$link = 'https://www.facebook.com/dialog/oauth?';
		$link .= 'app_id=' . $this -> face_id;
		$link .= '&client_id=' . $this -> face_id;
		$link .= '&display=page';
		//$link .= '&domain=faceconn.com&e2e=%7B%7D';
		//$link .= '&locale=en_US&origin=1';
		$link .= '&redirect_uri=' . $this -> face_redirect;
		//$link .= '&response_type=token%2Csigned_request';
		$link .= '&scope=email,user_location&sdk=joey';
		$link = '<A HREF="' . $link . '">';
		return ($link);
	}

	function facebook_login() {
		$img = '<img src="img/login_facebook_en.png" border="0">';
		$sx = $this -> facebook_login_link() . $img . '</A>';
		return ($sx);
	}

	function linkedin_login_link() {
		$csrf = md5('linked' . date("ymd"));
		$_SESSION['linkid'] = $csrf;
		$link = $this -> linkedin_url . '?response_type=code';
		$link .= '&client_id=' . $this -> linkedin_key;
		$link .= '&scope=r_basicprofile';
		$link .= '&state=' . $csrf;
		$link .= '&redirect_uri=' . $this -> linkedin_redirect;
		$link = '<A HREF="' . $link . '">';
		return ($link);
	}

	function linkedin_login() {
		$img = '<img src="img/login_linkedin_en.png" border="0">';
		$sx = $this -> linkedin_login_link() . $img . '</A>';
		return ($sx);
	}

	/* Microsoft */
	function microsoft_login() {
		$img = '<img src="img/login_microsoft_en.png" border="0">';
		$sx = $img;
		return ($sx);
	}
}
?>
