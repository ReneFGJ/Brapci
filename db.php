<?php
    /**
     * DB
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2014 - sisDOC.com.br
	 * @access public
     * @version v0.14.17
	 * @package System
	 * @subpackage Database connection
    */
    if (!isset($include)) { $include = '_include/'; }
	else { $include .= '_include'; }

    ob_start();
	session_start();

	/* Noshow Errors */
	$debug = 255; 
	$debug = 0; 
	
	
	ini_set('display_errors', $debug);
	ini_set('error_reporting', $debug);
	
	/* Path Directory */
	$path_info = trim($_SERVER['PATH_INFO']);
	
	/* Set header param */
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);	
	
    $ip = $_SERVER['SERVER_ADDR'];
	if ($ip == '::1') { $ip = '127.0.0.1'; }
	
	$charset = 'utf-8';
	header('Content-Type: text/html; charset='.$charset);
	
	/* Include */
	require($include.'sisdoc_sql.php');	
	
	
	/* Leituras das Variaveis dd0 a dd99 (POST/GET) */
	$vars = array_merge($_GET, $_POST);
	for ($k=0;$k < 100;$k++)
		{
		$varf='dd'.$k;
		$varf=$vars[$varf];
		$dd[$k] = security_post($varf);
		}	
	
	/* Data base */
	$filename = "_db/db_mysql_".$ip.".php";
	if (file_exists($filename))
		{
			require($filename);

		} else {		
			if ($install != 1) 
				{
				require($db_config);
				
				if (!file_exists($file))
					{
						echo '<H1>Configuração do sistema</h1>';
						require("db_config.php");
						exit;
					} else {
						echo 'Contacte o administrador, arquivo de configuração inválido';
					}
				
		}
	
	}	
	
function security_post($s)
	{
		$s = troca($s,'<','&lt;');
		$s = troca($s,'>','&gt;');
		$s = troca($s,'"','&quot;');
		//$s = troca($s,'/','&#x27;');
		$s = troca($s,"'",'&#x2F;');
		return($s);		
	}
function troca($qutf,$qc,$qt)
	{
	if (is_array($qutf))
		{
			print_r($qutf);
			exit;
		}
	return(str_replace(array($qc), array($qt),$qutf));
	}	
    
?>
	