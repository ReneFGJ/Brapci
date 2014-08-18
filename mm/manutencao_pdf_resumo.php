<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));

array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Gestão da Base de Dados - PDF Quebrado';

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<H2>Verifica Integridade dos Links em PDF</H2>
<table width="100%" class="lt1">
<TR><TH>Arquivo</TH><TH colspan="2"><I>Status</I></TH></TR>
<?
	$sql = "delete from brapci_article_suporte ";
	$sql .= " where bs_status = 'X' ";
//	$sql .= " and bs_journal_id = '".strzero($dd[0],7)."' ";
	$rlt = db_query($sql);

	$sql = "select ar_titulo_1, bs_type, id_bs, ";
	$sql .= "ed_codigo, ed_ano, ed_vol, ed_nr, bs_status, ";
	$sql .= " ar_codigo, bs_type, id_ar  ";
	$sql .= " from brapci_article ";
	$sql .= " left join brapci_article_suporte on ar_codigo = bs_article ";
	$sql .= " inner join brapci_edition on ed_codigo = ar_edition ";
	$sql .= " where ar_status <> 'X' ";
	$sql .= " and ar_journal_id = '".strzero($dd[0],7)."' ";
	$sql .= " order by ed_ano, ed_vol, ed_nr, ar_codigo, bs_type  ";
	$rlt = db_query($sql);
	
	$ed = 'x';
	$xcod = 'x';
	while ($line = db_read($rlt))
		{
		$id = $line['id_ar'];
		$edi = $line['ed_codigo'];
		$cod = $line['ar_codigo'];
		$link = '<A HREF="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd0='.$id.'" target="_new">';
		if ($edi != $ed)
			{ 
			echo '<TR class="lt3"><TD colspan="4">'.$line['ed_ano'].', vol. '.$line['ed_vol'].', nr. '.$line['ed_nr'].'</TD>'; 
			$ed = $edi;
			}
		if ($cod != $xcod)
			{
				echo '<TR '.coluna().'>';
				echo '<TD>';
				echo $link;
				echo $line['id_ar'];
				echo '<TD>';
				echo $line['ar_titulo_1'];
				echo '<TD>';
				echo $line['bs_type'];
				echo '(';
				echo $line['bs_status'];
				echo ')';
				$xcod = $cod;
			} else {
				echo '<TD colspan="3">';
				echo '<TD>';
				echo $line['bs_type'];
				echo '(';
				echo $line['bs_status'];
				echo ')';
			}
		
		}
?>
</table>
</DIV>