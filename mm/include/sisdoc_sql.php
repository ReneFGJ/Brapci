<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0F                       19/10/2010 // Correção da Mensagem Postgres
// 0.0e                       29/05/2009 // Envio por e-mail de erros
// 0.0d                       20/05/2008 //
// 0.0a                       25/02/2006 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (SQL)","0.0b",20080520)); }
global $sql_query;
function pg_email_erro($serro)
	{
	global $secu,$base,$base_name;
	$email = 'rene@fonzaghi.com.br';
	$tee = '<table width="400" bordercolor="#ff0000" border="3" align="center">';
	$tee .= '<TR><TD bgcolor="#ff0000" align="center"><FONT class="lt2"><FONT COLOR=white><B>Erro  -'.$base.'-['.$base_name.']-</TD></TR>';
	$tee .= '<TR><TD><B><TT>';
	$tee .= $serro;
	$tee .= '<TR><TD><B><TT>';
	$tee .= pg_last_error();
	$tee .= '<TR><TD><B><TT>';
	$tee .= '<BR>Remote Address: '.$_SERVER['REMOTE_ADDR'];
	$tee .= '<BR>Metodo: '.$_SERVER['REQUEST_METHOD'];
	$tee .= '<BR>Nome da página: '.$_SERVER['SCRIPT_NAME'];
	$tee .= '<BR>Domínio: '.$_SERVER['SERVER_NAME'];
	$tee .= '<BR>Data: '.date("d/m/Y H:i:s");
	$tee .= '</table>';

	$headers .= 'To: Rene (Monitoramento) <rene@fonzaghi.com.br>' . "\r\n";
	$headers .= 'From: BancoSQL (PG) <rene@sisdoc.com.br>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";		

	mail($to, $subject, $message, $headers);	
	mail($email, 'Erros de SQL '.$secu, $tee, $headers);
//	echo '<BR>e-mail enviado para '.$email ;
	}

function pg_error()
	{
	global $secu,$base;	
	echo '<table width="400" bordercolor="#ff0000" border="3" align="center">';
	echo '<TR><TD bgcolor="#ff0000" align="center"><FONT class="lt2"><FONT COLOR=white><B>Erro  -'.$base.'-</TD></TR>';
	echo '<TR><TD><B><TT>';
	}

function db_erro()
	{
	global $base,$rlt;
	if ($base=='pgsql') { return(pg_error() . '<BR>'.$rlt); }
	if ($base=='mssql') { return(mssql_get_last_message() . '<BR>'.$rlt); }
	}
function db_connect()
	{
	global $base;
	global $base_host;
	global $base_port;
	global $base_name;
	global $base_user;
	global $base_pass;
	$RST = '';
	if ($base=='pgsql')
		{
		$conn = "host=".$base_host." port=".$base_port." dbname=".$base_name." user=".$base_user." password=".$base_pass."";
		$db = pg_connect($conn);
		}
		
	if ($base=='mysql')
		{
		$conn=mysql_connect($base_host,$base_user,$base_pass) or die ("Erro de Conexão !");;
		$banco=mysql_select_db($base_name,$conn) or die ("Erro ao Selecionar o Banco !");
		$RST = 'MYSQL';
		}
		
	if ($base=='mssql')
		{
		$conn=mssql_connect($base_host,$base_user,$base_pass) or die ("Erro de Conexão !");;
		$banco=mssql_select_db($base_name,$conn) or die ("Erro ao Selecionar o Banco !");
		$RST = 'MSSQL';
		}	
	if ($base=='sybase')
		{
		$conn=sybase_connect($base_host,$base_user,$base_pass) or die ("Erro de Conexão !");;
		$banco=sybase_select_db($base_name,$conn) or die ("Erro ao Selecionar o Banco !");
		$RST = 'MSSQL';
		}				
	return($RST);
	}
	
function db_query($rlt)
	{
	global $base,$debug,$sql_query;	
	$sql_query = $rlt;
//	if (strlen($debug) > 0) { echo '<HR>'.$rlt; }
	////////////////////////////// PostGre
	if ($base=='pgsql')
		{ 
		if (strlen($debug) > 0) { $xxx = pg_query($rlt) or die($rlt . pg_email_erro($rlt) ); } else
		{ $xxx = pg_query($rlt) or die('Erro de base <BR>' . pg_email_erro($rlt)); }
		}
	////////////////////////////// MySQL
	if ($base=='mysql')
		{
		if (strlen($debug) > 0) { $xxx = mysql_query($rlt) or die(mysql_error() . '<BR>'.$rlt); }
		else {  $xxx = mysql_query($rlt) or die('Erro de base'); }
		}
		
	if ($base=='mssql')
		{
		if (strlen($debug) > 0)  { $xxx = mssql_query($rlt) or die(pg_error(). '<BR>'.$rlt); } else
		 { $xxx = mssql_query($rlt); }
		}
		 
	if ($base=='sybase')
		{ $xxx = sybase_query($rlt) or die(pg_error(). '<BR>'.$rlt); }
	return $xxx;
	}
	
function db_read($rlt)
	{
	global $base;
	if ($base=='pgsql')
		{ $xxx = pg_fetch_array($rlt); }
	if ($base=='mysql')
		{ $xxx = mysql_fetch_array($rlt); }
	if ($base=='mssql')
		{ $xxx = mssql_fetch_array($rlt,MYSQL_BOTH); }
	return($xxx);
	}
?>