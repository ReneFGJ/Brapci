<?php
$tab_max = "776";
echo '--->'.$ip;
global $cnn,$db_public,$db_apoio;
		$base = "mysql";
		$base_user="sisdoc_sa";
		$base_port = '8130';
		$base_host="localhost";
		$base_name="brapci_base";
		$base_pass="448545ct";
		
		$base = "mysql";
		$base_user="root";
		$base_port = '8130';
		$base_host="localhost";
		$base_name="brapci_base";
		$base_pass="";
		
$db_base = 'brapci_base.';
$db_public = 'brapci_pubic.';
$db_apoio = 'brapci_apoio.';		

$ok = db_connect();
// n?>