<?
require("oai.php");
$sql = "select * from oai_cache where cache_status = '@' ";
$sql .= " and (cache_journal = '".$jid."' or cache_journal = '".round($jid)."')";
$sql .= " order by cache_tentativas ";
$zrlt = db_query($sql);
echo '<table width="80%" border=1 ><TR><TD><TT>';
if ($line = db_read($zrlt))
	{
	$jid = $line['cache_journal'];
	$id = $line['id_cache'];
	$tentativas = intval('0'.$line['id_cache']);
	$get = trim($line['cache_oai_id']);
	$sql = "update oai_cache set cache_tentativas = ".($tentativas+1)." where id_cache = ".$id;
	$irlt = db_query($sql);
	
	$link = "?verb=GetRecord&metadataPrefix=oai_dc&identifier=".$get;
	$rlt = fopen($link_oai.$link,'r');
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

echo 'Nome da publicação: <B><U>'.$jnome.'</U></B>';
echo '<BR>Journal link oai:'.$link_oai;
echo '<BR>Verbo: <B>GetRecord</B>';
echo '<BR>Link: '.$link_oai.$link;
echo '<BR>Integridade :'.$integro;
echo '<BR>Tamanho: '.number_format(intval(strlen($s)/102.4)/10,1).'k Bytes';
echo '</table>';

	if ($ok == 0)
		{
		echo '<BR>Interrompido!';
		exit;
		}
	////////// recupera registros
	$s = troca($s,"'","´");
	$record = oai_string($s,'<record>','</record>');
	echo '<BR>Total de registro(s): '.count($record);
	if (count($record) > 0)
		{
		$sql = "update oai_cache set cache_content  = '".HtmltoChar($record[0])."', cache_status = 'A' where id_cache = ".$id;
		$irlt = db_query($sql);			
		$novo++;
		}
	}
echo '<BR>Total de Coletado: '.$novo;
echo '<BR><BR>Resumo';
$sql = "select count(*) as total, cache_status from oai_cache ";
$sql .= " where cache_journal = '".$jid."' ";
$sql .= " group by cache_status ";
$rlt = db_query($sql);

$coleta = 0;
while ($line = db_read($rlt))
	{
	if ($line['cache_status'] == '@') { $coleta = $line['total']; }
	echo '<BR><TT>'.$line['cache_status'].' ('.$line['total'].')</TT>';
	}
?>