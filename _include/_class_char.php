<?php
/**
* Esta classe é a responsável pela conexão com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.14.17
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package INCLUDEs
* @subpackage sisdoc_char
*/
function customError($errno, $errstr, $errfile, $errline, $errcontext)
  {
  global $secu,$base,$base_name,$user_log,$debug,$ttsql,$rlt,$sql_query;
  if ($errno != '8')
  		{
		$email = 'rene@fonzaghi.com.br';
		$tee = '<table width="600" bordercolor="#ff0000" border="3" align="center">';
		$tee .= '<TR><TD bgcolor="#ff0000" align="center"><FONT class="lt2"><FONT COLOR=white><B>ERRO  -['.$base.']-'.$base_name.'-</B></TD></TR>';
		$tee .= '<TR><TD align="center"><B><TT>';
		$tee .= 'Erro Número #'.$errno;
		$tee .= '<TR><TD><TT>';
		$tee .= '<BR>Remote Address: '.$_SERVER['REMOTE_ADDR'];
		$tee .= '<BR>Metodo: '.$_SERVER['REQUEST_METHOD'];
		$tee .= '<BR>Nome da página: '.$_SERVER['SCRIPT_NAME'];
		$tee .= '<BR>Domínio: '.$_SERVER['SERVER_NAME'];
		$tee .= '<BR>Data: '.date("d/m/Y H:i:s");
		
		if (strlen(trim($user_log)) > 0) { $tee .= '<TR><TD><TT>'; $tee .= 'User: '.$user_log; }
		if ($base == 'pgsql') { $tee .= '<TR><TD><B><TT>'; $tee .= pg_last_error(); }
		
		if (strlen(trim($sql_query)) > 0) { $tee .= '<TR><TD><TT>SQL:'.$sql_query; $tee .= $ttsql; }

		$tee .= '<TR><TD><TT>';
		$tee .= '<BR>File: '.$errstr;
		$tee .= '<BR>Line: '.$errline;
		$tee .= '<BR>File: '.$errfile;
		$tee .= '<TR><TD><TT>';
		$tee .= '<BR>Request URI:'.$_SERVER['REQUEST_URI'];
		$tee .= '<TR><TD><TT>';
		$args = $_SERVER['argv'];
		for ($rrr=0;$rrr < count($args);$rrr++)
			{ $tee .= 'args['.$rrr.'] == '.$args[$rrr].'<BR>'; }

		
		$tee .= '</table>';
		if ($debug == 1) { echo $tee; }
//		if ($debug == 3) { echo 'Muitas conexões, aguarde....'; }
		
	
		$headers .= 'To: Rene (Monitoramento) <rene@fonzaghi.com.br>' . "\r\n";
		$headers .= 'From: BancoSQL (PG) <rene@sisdoc.com.br>' . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";		

		mail($email, 'Erros de Script'.$secu, $tee, $headers);
				
		die();
		}
  } 

function redireciona($pg) { redirecina($pg); }
function redirecina($pg)
	{
	header("Location: ".$pg);
	echo 'Stoped'; exit;
	}


function checkpost($_P)
	{
	global $secu;
	$chkp = substr(md5($_P.$secu),5,10);
	return($chkp);
	}
	
function dv($nrv)
	{
	$to = 0;
	$nrv = trim(sonumero($nrv));
	$trv = round(strlen($nrv));
	for ($tr=0;$tr < $trv;$tr++)
		{
		$ttr1 = ($tr/2);
		$ttr2 = round($tr/2);
		if ($ttr1 == $ttr2)
			{ $to = $to + round(substr($nrv,$tr,1)); }
			else
			{ $to = $to + round(substr($nrv,$tr,1))*7; }
		}
	while ($to > 10) { $to = $to - 10; }
	return($to);
	}
function splitx($in,$string)
	{
	$string .= $in;
	$vr = array();
	while (strpos(' '.$string,$in))
		{
		$vp = strpos($string,$in);
		$v4 = trim(substr($string,0,$vp));
		$string = trim(substr($string,$vp+1,strlen($string)));
		if (strlen($v4) > 0)
			{ array_push($vr,$v4); }
		}
	return($vr);
	}

function fmt($vlr=0,$dec=0)
	{
		if ($vlr == 0) { return('&nbsp;'); }
		$sx = number_format($vlr,$dec,',','.');
		return($sx);
	}
function fmt_data($data)
	{
		$data = round($data);
		if ($data < 19100000) { return(""); }
		$data = substr($data,6,2).'/'.substr($data,4,2).'/'.substr($data,0,4);
		return($data);
	}
function strzero($ddx,$ttz)
	{
	$ddx = round($ddx);
	while (strlen($ddx) < $ttz)
		{ $ddx = "0".$ddx; }
	return($ddx);
	}

function sonumero($it)
	{
	$rlt = '';
	for ($ki=0;$ki < strlen($it);$ki++)
		{
		$ord = ord(substr($it,$ki,1));
		if (($ord >= 48) and ($ord <= 57)) { $rlt = $rlt . substr($it,$ki,1); }
		}   
	return $rlt;
	}

function page()
	{
	$page = $_SERVER['SCRIPT_NAME'];
	while ($pos = strpos(' '.$page,'/'))
		{ $page = substr($page,$pos,strlen($page)); }
	return($page);
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
	
function UpperCaseSQL($d)
	{
	$qch1="ÁÉÍÓÚáéíóúàèìòùÀÈÌÒÙÂÊÎÔÛâêîôûÇçäëïöüÄËÏÖÜÃÕãõªº";
	$qch2="AEIOUaeiouaeiouAEIOUAEIOUaeiouCcaeiouAEIOUAOAOao";
	for ($qk=0;$qk < strlen($qch2);$qk++)
		{
		$d = troca($d,substr($qch1,$qk,1),substr($qch2,$qk,1));
		}
		 
	$d = strtoupper($d);
	return $d;
	}

function UpperCase($dx)
	{
	$qch1='ÁÉÍÓÚáéíóúàèìòùÀÈÌÒÙÂÊÎÔÛâêîôûÇçäëïöüÄËÏÖÜÃÕãõ';
	$qch2='ÁÉÍÓÚÁÉÍÓÚÀÈÌÒÙÀÈÌÒÙÂÊÎÔÛÂÊÎÔÛÇÇÄËÏÖÜÄËÏÖÜÃÕÃÕ';
	
	$dx = strtoupper($dx);
	
	for ($qk=0;$qk < strlen($qch2);$qk++)
		{
		$dx = troca($dx,substr($qch1,$qk,1),substr($qch2,$qk,1));
		}
	
	return $dx;
	}
	
function LowerCase($d)
	{
	$d = $d . ' ';
	$qch1='ÁÉÍÓÚáéíóúàèìòùÀÈÌÒÙÂÊÎÔÛâêîôûÇçäëïöüÄËÏÖÜÃÕãõ';
	$qch2='áéíóúáéíóúàèìòùàèìòùâêîôûâêîôûççäëïöüäëïöüãõãõ';
	
	$d = strtolower($d);
	for ($qk=0;$qk < strlen($qch2);$qk++)
		{
		$d = troca($d,substr($qch1,$qk,1),substr($qch2,$qk,1));
		}
		 
	return trim($d);
	}
		
function LowerCaseSQL($d)
	{
	$qch1="ÁÉÍÓÚáéíóúàèìòùÀÈÌÒÙÂÊÎÔÛâêîôûÇçäëïöüÄËÏÖÜÃÕãõªº";
	$qch2="aeiouaeiouaeiouaeiouaeiouaeiouccaeiouaeiouaoaoao";
	
	for ($qk=0;$qk < strlen($qch2);$qk++)
		{
		$d = troca($d,substr($qch1,$qk,1),substr($qch2,$qk,1));
		}
		 
	$d = strtolower($d);
	return $d;
	}		
	
?>