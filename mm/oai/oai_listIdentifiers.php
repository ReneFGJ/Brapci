<?
require("oai.php");
$link .= "?verb=ListIdentifiers&metadataPrefix=oai_dc";

if (strlen($harvesting_last) > 0)
	{
		$hl = $harvesting_last;
		$hl = substr($hl,0,4).'-'.substr($hl,4,2).'-'.substr($hl,6,2);
		$link .= '&from='.$hl;
	} else {
		//$link .= '&from=1910-01-01';
	}
$rlt = fopen($link_oai.$link,'r') or die('Falha no acesso');
$s = '';
$t = 1;
while ($t > 0)
	{
	$sx = fread($rlt,1024);
	$s .= $sx;
	$t = strlen($sx);
	}
fclose($rlt);

if (oai_integridade($s))
	{ $ok = 1; $integro = '<font color="#00cc00"><B>SIM</B></font>'; } else
	{ $ok = 0; $integro = '<font color=Red><B>NÃO</B></font>'; } 
echo '<BR>Journal: <B>'.$jnome.'</B>';
echo '<BR>Journal link oai:'.$link_oai;
echo '<BR>Verbo: <B>ListIdentifiers</B>';
echo '<BR>Link: '.$link_oai.$link;
echo '<BR>Integridade :'.$integro;
echo '<BR>Tamanho: '.number_format(intval(strlen($s)/102.4)/10,1).'k Bytes';

if ($ok == 0)
	{
	echo '<BR>Interrompido!';
	exit;
	}
////////// recupera registros
$record = oai_string($s,'<header>','</header>');
echo '<BR>Total de registro(s): '.count($record);
$token = oai_content($s,'resumptionToken');
$novo = 0;
for ($r=0;$r < count($record);$r++)
	{
	$identifier = oai_identifier($record[$r]);
	$stamp = oai_content($record[$r],'datestamp');
	$setSpec = oai_content($record[$r],'setSpec');
	if (strlen($identifier) > 0)
		{
		$sql = "select * from oai_cache where cache_oai_id = '".$identifier."' ";
//		echo '[ '.$sql.' ]';
		$rlt = db_query($sql);
		if (!($line = db_read($rlt)))
			{
			echo '<BR><TT>'.$identifier.'</TT>';
			$sql = "insert into oai_cache ";
			$sql .= "( cache_oai_id, cache_status, cache_data, ";
			$sql .= "cache_content, cache_journal, cache_prioridade, ";
			$sql .= "cache_datastamp, cache_setSpec )";
			$sql .= " values ";
			$sql .= "('".$identifier."','@','".date("Ymd")."',";
			$sql .= "'','".$jid."','5',";
			$sql .= "'".$stamp."','".$setSpec."'";
			$sql .= ")";
			
			$rlt = db_query($sql);
			$novo++;
			}
		}
	}
echo '<BR>Total de <B>novo(s)</B> registro(s): '.$novo;
if (strlen($token) > 0)
	{
	echo '<BR>resumptionToken: '.$token.' next from: '.$stamp;
	}
$stamp = substr($stamp,0,4).substr($stamp,5,2).substr($stamp,8,2);

$total = 0;
$sql = "select count(*) as total from oai_cache where cache_journal = '".$jid."' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{ $total = $line['total']; }
echo '<BR>Total de trabalhos do <I>Journal</I>: '.$total;

//////////////////////////////// ATUALIZA BASE	
	
$sql = "update brapci_journal set ";
$sql .= "jnl_last_harvesting = '".$stamp."' ";
$sql .= ", jnl_token = '".$token."' ";
$sql .= ", jnl_artigos = '".$total."' ";
$sql .= "where jnl_codigo = '".$jid."' ";
$rlt = db_query($sql);
?>