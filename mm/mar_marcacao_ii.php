<?
require("mar_function.php");
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
$path = "mar_marcacao_ii.php";

///////////////////////////////////////////////////////////////////////////// INÍCIO DA PÁGINA
$titu[0] = 'Processar Linguagem de Marcação - Phase II'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<?
//////////////////////////////////// Busca próximo a processar
$sql = "select * from mar_works where m_status = 'A' limit 1 ";
$rlt = db_query($sql);
$refs = array();
$anos = array();
$loop = 0;
$online = 0;

$line = db_read($rlt);
$sql = "update mar_works set m_status = 'B' where m_status = 'A'";
$rlt = db_query($sql);
echo 'atualizado';
exit;

///////////////////////////////// Mostra datos
if ($line = db_read($rlt))
	{
	require("mar_marcacao_dados.php");
	$lx = ' '.trim($line['m_ref']);
	$lx = troca($lx,'et al',';ET Al');
	$lx = troca($lx,'(Org.)','');
	$lx = troca($lx,'(Comp.)','');
	$lx = troca($lx,'(Coord.)','');

	$lx = troca($lx,'(org.)','');
	$lx = troca($lx,'(comp.)','');
	$lx = troca($lx,'(coord.)','');

//	$lx = MAR_preposicao($lx);
	$a2 = MAR_autor_grava(MAR_autor($lx));
	}
//////////////////////////////////////////////////////////////////////////
if (count($a2) > 0)
	{
		$sql = "update mar_works set m_status = 'B', ";
		$sql .= " m_author_1 = '".$a2[0]."', ";
		$sql .= " m_author_2 = '".$a2[1]."', ";
		$sql .= " m_author_3 = '".$a2[2]."', ";
		$sql .= " m_author_4 = '".$a2[3]."', ";
		$sql .= " m_author_5 = '".$a2[4]."', ";
		$sql .= " m_author_6 = '".$a2[5]."' ";
		$sql .= " where id_m = ".$line['id_m'];
		$rlt = db_query($sql);
		$loop = 1;
	} else {
	if (count($anos) > 1)
		{
		for ($ra = 0;$ra < count($anos);$ra++)
			{
			echo '<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1='.$anos[$ra].'&dd2=A">'.$anos[$ra].'</a>';
			echo ' ';
			}
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1=0&dd2=Z">(Erro na referência)</a>';

		} else {
			echo '<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1=0&dd2=Z">(Erro na referência)</a>';
			echo ' ';
			$sql = "update mar_works set m_status = 'Z', m_ano = 0 where id_m = ".$line['id_m'];
			$rlt = db_query($sql);
			$loop = 1;
		}
	}

?>
<BR>
</DIV>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	echo '<META HTTP-EQUIV="Refresh" Content = "2; URL=mar_marcacao_ii.php?dda='.date("dmYHms").'">';
	}
?>
