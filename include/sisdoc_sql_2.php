<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0d                       20/05/2008 //
// 0.0a                       25/02/2006 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (SQL)","0.0b",20080520)); }



function pg_email_erro($serro)
	{
	global $secu;
	$tee = '<table width="400" bordercolor="#ff0000" border="3">';
	$tee .= '<TR><TD bgcolor="#ff0000" align="center"><FONT class="lt2"><FONT COLOR=white><B>Erro PGSQL</TD></TR>';
	$tee .= '<TR><TD><B><TT>';
	mail('rene@sisdoc.com.br', 'Erros de SQL '.$secu, $tee, $headers);
	}

function pg_error()
	{
	echo '<table width="400" bordercolor="#ff0000" border="3">';
	echo '<TR><TD bgcolor="#ff0000" align="center"><FONT class="lt2"><FONT COLOR=white><B>Erro PGSQL</TD></TR>';
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
	global $base,$debug;	
//	if (strlen($debug) > 0) { echo '<HR>'.$rlt; }
	////////////////////////////// PostGre
	if ($base=='pgsql')
		{ 
		if (strlen($debug) > 0) { $xxx = pg_query($rlt) or die(pg_error() . '<BR>'.$rlt. .pg_email_erro($rlt) ); } else
		{ $xxx = pg_query($rlt) or die('Erro de base '.pg_email_erro() ); }
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