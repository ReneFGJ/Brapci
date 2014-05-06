<?
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");

echo '<h1>Identificar tipo de publicação (Fase II)</h1>';

require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$proc = $cited->cited_II_recover();
echo '<BR><BR>Processadas '.$proc.' referências';
if ($proc > 0)
	{
			echo '<meta http-equiv="refresh" content="2;'.page().'" />';
	}

require("../foot.php");
exit;

require($include."sisdoc_debug.php");
require($include."sisdoc_windows.php");

if ($line = db_read($rlt))
	{
	$idc = $line['id_m'];

	require("mar_marcacao_dados.php");
	$lx = ' '.trim($line['m_ref']);
	$lx = troca($lx,chr(13),'');
	$lx = troca($lx,chr(10),'');
	$lx = troca($lx,chr(15),'');
//	$lx = MAR_preposicao($lx);
	$a2 = MAR_tipo($lx);
	$gr = 1;
	if (strlen($a2) == 0)
		{
		require("mar_marcacao_iii_cm.php");

		echo '<BR>'.$a2;
		echo '<BR><BR>PAREI AQUI';
		
		$gr = 0;
		$sql = "update mar_works set m_status = 'R', ";
		$sql .= "m_tipo = '".substr($a2,0,5)."' ,";
		$sql .= "m_journal = '".substr($a2,5,7)."' ";
		$sql .= " where id_m = ".$line['id_m'];
		$rlt = db_query($sql);	
		$loop = 1;	
		}
	}
	if ($gr == 1)
		{
		$sql = "update mar_works set m_status = 'C', ";
		$sql .= "m_tipo = '".substr($a2,0,5)."' ,";
		$sql .= "m_journal = '".substr($a2,5,7)."' ";
		$sql .= " where id_m = ".$line['id_m'];
		$rlt = db_query($sql);
		$loop = 1;
		}
?>
<BR>
</table>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	echo '<META HTTP-EQUIV="Refresh" Content = "1; URL=mar_marcacao_iii.php?dda='.date("dmYHms").'">';
	}
?>
