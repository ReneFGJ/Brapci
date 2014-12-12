<?php
/**
 * Header Fz
 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
 * @copyright Copyright (c) 2014 - sisDOC.com.br
 * @access public
 * @version v.0.14.21
 * @package _class
 * @subpackage _class_header.php
 */

class header {
	//var $charcod = "UTF-8";
	var $charcod = "ISO-8859-1";

	/* Google Ids */
	var $google_id = '';
	/* Ex: UA-12712904-10 */
	var $login_api = '';
	var $expire_session = 0;

	/* About this site */
	var $title = ':: Project Name ::';
	var $description = '';
	var $http = '';
	var $path = '';
	
	var $email = '';
	var $email_type = '';
	var $email_name = '';
	var $email_smtp = '';
	
	var $cab_html='';
	

	function foot() {
		$sx = '';
		$sx .= '<div id="foot">';
		$sx .= '&copy; '.date("Y").'- sisDOC ';
		$sx .= '<div id="foot">';
		return ($sx);
	}

	function head() {
		global $LANG, $http, $style_add, $js_add,$include;
		$cr = chr(13) . chr(10);
		$pth = $this -> path;

		$sx = '<head>' . $cr;
		if ($this->expire_session > 0)
			{
				$sx .= '<META HTTP-EQUIV=Refresh CONTENT="3600; URL=' . $http . 'logout.php">' . $cr;
			}

		/* Charset */
		header('Content-type: text/html; charset=' . $this -> charcod);
		$sx .= '<meta http-equiv="Content-Type" content="text/html; charset=' . $this -> charcod . '" />';

		/* Meta Information */
		$sx .= '<meta name="description" content="' . $this -> description . '">' . $cr;
		$sx .= '<link rel="shortcut icon" type="image/x-icon" href="' . $http . 'favicon.ico" />' . $cr;

		/* Add Style File */
		$style = array('prj_style.css','brapci_admin.css');
		for ($r = 0; $r < count($style); $r++) { $sx .= '<link rel="STYLESHEET" type="text/css" href="' . $http . 'css/' . $style[$r] . '">' . $cr;
		}

		/* Style Additional */
		if (is_array($style_add)) {
			for ($r = 0; $r < count($style_add); $r++) { $sx .= '<link rel="STYLESHEET" type="text/css" href="' . $http . 'css/' . $style_add[$r] . '">' . $cr;
			}
		}

		/* Add Java script */
		$js = array('jquery.js','jquery.maskedit.js','jquery.maskmoney.js','windows.js');
		for ($r = 0; $r < count($js); $r++) 
			{ $sx .= '<script type="text/javascript" src="' . $http . 'js/' . $js[$r] . '"></script>' . $cr;
		}

		/* Style Additional */
		if (is_array($js_add)) {
			for ($r = 0; $r < count($js_add); $r++) { $sx .= '<script type="text/javascript" src="' . $http . 'js/' . $js_add[$r] . '"></script>' . $cr;
			}
		}

		$sx .= '<title>' . $this -> title . '</title>' . $cr;
		$sx .= '</head>';

		return ($sx);
	}

	function cab() {
		$sx = $this -> head();
		$sx .= $this -> api_google;
		$sx .= '<div id="cab">';
		$sx .= $this->cab_html;
		$sx .= $this->menu_top();
		$sx .= '</div>';
		return ($sx);
	}

	function menu_top()
		{
			return('xx');
		}

	function retornar_para_pagina_principal() {
		global $cr;
		$sx = '<script>';
		$sx .= 'opener.location.reload();';
		$sx .= 'window.close();';
		$sx .= '</script>';
		echo $sx;
		return (true);
	}

}
?>