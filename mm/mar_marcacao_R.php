<?
require("mar_function.php");
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_windows.php");

$titu[0] = 'Processar Linguagem de Marcação - Phase III'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<?
$path = "mar_marcacao_R.php";

$sql = "select * from mar_works where m_status = 'R' order by m_ref limit 1 ";
$rlt = db_query($sql);
$refs = array();
$anos = array();
$loop = 0;
$online = 0;

if ($line = db_read($rlt))
	{
	$idc = $line['id_m'];
	
	///////////////////////////////////////////////////////////////////////////////////////
	$complement = '';
	$complement .= '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_iii_mk.php?dd0='.$idc.'&dd1=LINK'.chr(39).',400,400);" title="Link de Internet">';
	$complement .= '<img src="img/icone_cited_internet.jpg" width="48" alt="" border="0">';
	$complement .= '</A>';
	$complement .= '&nbsp;';
	$complement .= '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_iii_mk.php?dd0='.$idc.'&dd1=NC'.chr(39).',400,400);" title="Sem Classificação definida, revisar">';
	$complement .= '<img src="img/icone_cited_notcatalog.png" width="48" height="46" alt="" border="0">';
	$complement .= '</A>';

	$complement .= '<BR><BR>';
	$complement .= '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_iii_mk.php?dd0='.$idc.'&dd1=TESE'.chr(39).',400,400);" title="TESE">';
	$complement .= '<img src="img/icone_cited_these.png" width="24" alt="Tese" border="0">';
	$complement .= '</A>';

	$complement .= '&nbsp;';
	$complement .= '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_iii_mk.php?dd0='.$idc.'&dd1=DISSE'.chr(39).',400,400);" title="DISSERTACAO">';
	$complement .= '<img src="img/icone_cited_dissetacao.png" width="24" alt="Dissertacao" border="0">';
	$complement .= '</A>';

	$complement .= '&nbsp;';
	$complement .= '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_iii_mk.php?dd0='.$idc.'&dd1=TCC'.chr(39).',400,400);" title="TCC">';
	$complement .= '<img src="img/icone_cited_dissetacao.png" width="24" alt="TCC" border="0">';
	$complement .= '</A>';

	$complement .= '&nbsp;';
	$complement .= '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_iii_mk.php?dd0='.$idc.'&dd1=RELAT'.chr(39).',400,400);" title="RELATÓRIO">';
	$complement .= '<img src="img/icone_cited_dissetacao.png" width="24" alt="Relatório" border="0">';
	$complement .= '</A>';

	/////////////////////////////////////////////////////////////////////////////////////////

	require("mar_marcacao_dados.php");
	$lx = ' '.trim($line['m_ref']);
	$lx = troca($lx,chr(13),'');
	$lx = troca($lx,chr(10),'');
	$lx = troca($lx,chr(15),'');
//	$lx = MAR_preposicao($lx);
	$a2 = MAR_tipo($lx);
	echo '===>'.$a2;
	$gr = 1;
	if (strlen($a2) == 0)
		{
		require("mar_marcacao_iii_cm.php");

		echo '<BR>'.$a2;
		echo '<BR><BR>PAREI AQUI - iii';
		exit;
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
</DIV>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	echo '<META HTTP-EQUIV="Refresh" Content = "1; URL=mar_marcacao_R.php?dda='.date("dmYHms").'">';
	}
?>
