<?
$link = "?verb=ListSets";
//$link .= "?verb=ListRecords&metadataPrefix=oai_dc";

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
echo '<BR>------------------------';
echo '<BR>Verbo: <B>ListSets</B>';
echo '<BR>Link: '.$link_oai.$link;
echo '<BR>Integridade :'.$integro;
echo '<BR>Tamanho: '.number_format(intval(strlen($s)/102.4)/10,1).'k Bytes';

if ($ok == 0)
	{
	echo '<BR>Interrompido!';
	exit;
	}
////////// recupera registros
$record = oai_string($s,'<set>','</set>');
echo '<BR>Total de registro(s): '.count($record);
$token = oai_content($s,'resumptionToken');
$novo = 0;
for ($r=0;$r < count($record);$r++)
	{
	$utf8 = utf8_detect($record[$r]);
	if ($utf8 > 0) { $record[$r] = utf8_decode($record[$r]); }
	
	$setName = oai_content($record[$r],'setName');
	$setDescription = oai_content($record[$r],'setDescription');
	$setSpec = oai_content($record[$r],'setSpec');
	if (strlen($setSpec) > 0)
		{
		$sql = "select * from oai_listsets where ls_setspec = '".$setSpec."' ";
		$sql .= " and ls_journal = '".$jid."' ";
		$rlt = db_query($sql);
		if (!($line = db_read($rlt)))
			{
			echo '<BR><TT>'.$setSpec.' - '.$setName.'</TT>';
			$sql = "insert into oai_listsets ";
			$sql .= "( ls_setspec, ls_setname, ls_setdescription, ";
			$sql .= "ls_journal, ls_status, ls_data ";
			$sql .= " )";
			$sql .= " values ";
			$sql .= "('".$setSpec."','".$setName."','$setDescription',";
			$sql .= "'".$jid."','A',".date("Ymd");
			$sql .= ")";
			
			$rlt = db_query($sql);
			$novo++;
			}
		}
	}
echo '<BR>Total de ListSets<B> novo(s)</B>: '.$novo;
?>