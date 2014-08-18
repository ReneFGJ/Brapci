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
	$sql = "select * from brapci_article_suporte ";
	$sql .= " inner join brapci_article on bs_article = ar_codigo ";
	$sql .= " where bs_type = 'PDF' and bs_status <> 'V' ";
	$sql .= " and ar_status <> 'X' ";
	$sql .= " order by id_ar ";
	$sql .= " limit 500 ";
	$rlt = db_query($sql);
	
	while ($line = db_read($rlt))
		{
		$addr = trim($line['bs_adress']);
		$id = $line['id_ar'];
		$link = '<A HREF="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd0='.$id.'" target="_new">';
		echo '<TR><TD>'.$link.$addr;
		echo '<TD>';
		echo $line['ar_status'];
		echo '<TD>';
		if (file_exists($uploaddir.$addr))
			{
			echo '<font color=green>OK</font>';
			$sql = "update brapci_article_suporte set bs_status = 'V' where id_bs = ".$line['id_bs'];
			$xrlt = db_query($sql);
			} else {
			$tot++;
			echo '<font color=red>ERRO!</font>';
			$sql = "update brapci_article_suporte set bs_status = 'X' where id_bs = ".$line['id_bs'];
			$xrlt = db_query($sql);
//			$sql = "delete from brapci_article_suporte where bs_status = 'X' ";
//			$xrlt = db_query($sql);
			}
			
		}
?>
<TR><TD colspan="2">Total de <?=$tot;?> links quebrados.</TD></TR>
</table>
</DIV>