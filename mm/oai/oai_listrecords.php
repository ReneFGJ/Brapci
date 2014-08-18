<?
require("oai.php");
$link = $link_oai;
$link .= "?verb=ListRecords&metadataPrefix=oai_dc&from=2010-10-01";

$rlt = fopen($link,'r');
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
echo '<BR>Journal link oai:'.$link_oai;
echo '<BR>Integridade :'.$integro;
echo '<BR>Tamanho: '.number_format(intval(strlen($s)/102.4)/10,1).'k Bytes';

if ($ok == 0)
	{
	echo '<BR>Interrompido!';
	exit;
	}
////////// recupera registros
$record = oai_string($s,'<record>','</record>');
echo '<BR>Total de registros: '.count($record);

for ($r=0;$r < count($record);$r++)
	{
	$identifier = oai_identifier($record[$r]);
	echo '<BR>---->'.$identifier;
	$sql = "select * from oai_cache ";
	$sql .= " where cache_oai_id = '".$identifier."' ";
	$rlt = db_query($sql);
	if ($vline = db_read($rlt))
		{
			$sta = trim($vline['cache_status']);
			if ($sta == '@')
				{
				$record[$r] = troca($record[$r],"'","´");
				$sql = "update oai_cache set cache_status = 'A', cache_content = '".$record[$r]."' ";
				$sql .= " where id_cache = ".$vline['id_cache'];
				$qrlt = db_query($sql);
				}
			echo ' = '.$sta;
		} else {
			$sql = "insert into oai_cache ";
			$sql .= "(cache_oai_id,cache_status,cache_data,cache_journal,cache_content)";
			$sql .= " values ";
			$sql .= "('".$identifier."','A','".date("Ymd")."','".strzero($jid,7)."','".$record[$r]."')";
			$qrlt = db_query($sql);
			echo '<BR>Not Found '.$identifier;
		}
	}
?>