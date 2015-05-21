<?php
class social extends CI_Controller {

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

	public function __construct() {
		parent::__construct();
		$this -> load -> helper('form');

		$this -> load -> library('session');
		$this -> load -> helper('url');
	}//end __construct()

	public function index() {
		print_r($_SESSION);
		echo '<HR>';
		print_r($this);
	}

	public function session($provider) {

		$this -> load -> helper('url_helper');
		//facebook
		if ($provider == 'facebook') {
			//$app_id = $this -> config -> item('fb_appid');
			$app_id = $this -> face_id;
			//$app_secret = $this -> config -> item('fb_appsecret');
			$app_secret = $this -> face_app;

			$provider = $this -> oauth2 -> provider($provider, array('id' => $app_id, 'secret' => $app_secret, ));
		}
		//google
		else if ($provider == 'google') {

			//$app_id = $this -> config -> item('googleplus_appid');
			$app_id = $this -> google_key;

			//$app_secret = $this -> config -> item('googleplus_appsecret');
			$app_secret = $this -> google_key_client;
			$provider = $this -> oauth2 -> provider($provider, array('id' => $app_id, 'secret' => $app_secret, ));
		}

		//foursquare
		else if ($provider == 'foursquare') {

			$app_id = $this -> config -> item('foursquare_appid');
			$app_secret = $this -> config -> item('foursquare_appsecret');
			$provider = $this -> oauth2 -> provider($provider, array('id' => $app_id, 'secret' => $app_secret, ));
		}

		if (!$this -> input -> get('code')) {
			// By sending no options it'll come back here
			$provider -> authorize();
			redirect('social?error');
		} else {
			// Howzit?
			try {
				$token = $provider -> access($_GET['code']);
				$user = $provider -> get_user_info($token);
				print_r($user);

				/* Ativa sessão ID */

				$ss_user = $user['name'];
				$ss_email = trim($user['email']);
				$ss_image = $user['image'];

				$sql = "select * from users where us_email = '$ss_email' ";
				$CI = &get_instance();
				$query = $CI -> db -> query($sql);
				
				$query = $query -> result_array();
				$data = date("ymd");

				if (count($query) > 1) {
					$sql = "update users set us_last = '$data' where us_email = '$ss_email' ";
					$CI -> db -> query($sql);
				} else {
					$sql = "insert into users 
						(
							us_nome, us_email, us_cidade, us_pais, us_codigo, 
							us_ativo, us_nivel, us_genero, us_verificado, 
							us_cadastro, us_last
						) values (
							'$ss_nome','$ss_email','','',
							1,0,1,
							$data,$data
						)";
					$CI -> db -> query($sql);
				}
				if ($this -> uri -> segment(3) == 'google') {
					//Your code stuff here
				} elseif ($this -> uri -> segment(3) == 'facebook') {
					//your facebook stuff here

				} elseif ($this -> uri -> segment(3) == 'foursquare') {
					// your code stuff here
				}

				$this -> session -> set_flashdata('info', $message);
				redirect('social?tabindex=s&status=sucess');

			} catch (OAuth2_Exception $e) {
				show_error('That didnt work: ' . $e);
			}

		}
	}

}
