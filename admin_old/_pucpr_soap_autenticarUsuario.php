<?php
require("_pucpr_login.php");
/*
 *	$Id: sslclient.php,v 1.1 2004/01/09 03:23:42 snichol Exp $
 *
 *	SSL client sample.
 *
 *	Service: SOAP endpoint
 *	Payload: rpc/encoded
 *	Transport: https
 *	Authentication: none
 */
require_once('include/nusoap/nusoap.php');
//$client = new soapclient('https://polux.pucpr.br:8084/servicePibic?wsdl');
//$client = new soapclient('https://10.96.210.20:8084/servicePibic?wsdl');

$client = new soapclient('https://200.192.112.23:8081/servicoLogin?wsdl');
$client->setCredentials($user, $pass); 

$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

$param = array('arg0' => $codigo, 'arg1' => $senha);
$result = $client->call('autenticarUsuario', $param,'http://servicos.apc.br/', '', false, true);
?>
