<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////
$no_menu_left = 1;

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$B4','','Gerar análise >>',false,True,''));
if (strlen($dd[1]) == 0) { $dd[1] = '1972'; }
if (strlen($dd[2]) == 0) { $dd[2] = date("Y"); }

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'rel_metodologia_4.php';
$http_redirect = '';
$ttt = "aos seus fins da pesquisa";
$tit = "Relação da pesquisa quanto aos Fins e aos Meios ";
$metodologia = "<B>Metodologia:</B> nao definida.";


?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<BR><BR><font class="Lt3"><?=$tit;?></font>
<TABLE width="<?=$tab_max?>" align="center">
<TR><TD><?
editar();
?></TD></TR>
<TR><TD class="lt1"><?=$metodologia;?></TD></TR>
</TABLE>

<?
if ($saved > 0)
{
$dmax = $dd[2] - $dd[1];
if ($dmax > 40)
	{
	echo 'Diferença entre anos é superior a 40 anos. ';
	exit;
	}
$ano1 = intval($dd[1]);
$ano2 = intval($dd[2]);

$sql = "
select at_tecnica, count(*) as total, `at_tecnica` AS mt2,
 m2.`bmt_descricao` AS dt2
from (
select ar_section, ar_edition,`ar_codigo`,`at_tecnica_1` as at_tecnica from brapci_article as tb1 where not isnull(at_metodo_2 ) and ar_status <> 'X' and ar_journal_id = '0000003'
union 
select ar_section, ar_edition,`ar_codigo`,`at_tecnica_2` as at_tecnica from brapci_article as tb2 where not isnull(at_metodo_2 ) and ar_status <> 'X' and ar_journal_id = '0000003'
union 
select ar_section, ar_edition,`ar_codigo`,`at_tecnica_3` as at_tecnica from brapci_article as tb3 where not isnull(at_metodo_2 ) and ar_status <> 'X' and ar_journal_id = '0000003'
) as tabela
inner join brapci_edition on ar_edition = ed_codigo  
inner join brapci_section on ar_section = se_codigo  
LEFT JOIN brapci_metodologias AS m2 ON at_tecnica = m2.`bmt_codigo`
where (se_tipo = 'B' ) ";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= " group by at_tecnica ";


$rlt = db_query($sql);

$x = "X";
$to1 = 0;
$tot = 0;
while ($line = db_read($rlt))
	{
	$link = '<A HREF="rel_metodologia_2_detalhes.php?dd1='.$line['mt1'].'&dd2='.$line['mt2'].'&dd3='.$line['mt3'].'" target="_news" class="lt1">';
	$tp1 = $line['dt1'];
	if ($tp1 != $x)
		{
		if ($to1 > 0)
			{
			$sr .= '<TR class="lt1"><TD colspan="3" align="right"><B>Total de '.$to1.' trabalhos</B></TD></TR>';
			$to1 = 0;
			}
		$sr .= '<TR><TD class="lt4" colspan="3">'.$tp1.'</TD></TR>';
		$x = $tp1;
		}
	$sr .= '<TR '.coluna().'>';
	$sr .= '<TD>';
	$sr .= $link;	
	$sr .= $line['dt2'];	
	$sr .= '<TD>';
	$sr .= $link;	
	$sr .= $line['dt3'];	
	$sr .= '<TD align="right">';	
	$sr .= $line['total'];	
	$sr .= '</TR>';
	$tot = $tot + $line['total'];
	$to1 = $to1 + $line['total'];
	}
$sr = troca($sr,'-- sem classificacao --','-');
?>
<table width="90%" cellpadding="4" cellspacing="0" border="1">
<TR>
<TH width="45%">Meios</TH>
<TH width="45%">Enfoque</TH>
<TH width="10%">Quantidade</TH>
</TR>
<?=$sr;?>
<TR class="lt1"><TD colspan="3" align="right"><B>Total de <?=$to1;?> trabalhos</B></TD></TR>
<TR class="lt2"><TD colspan="3" align="right">Total de <?=$tot;?> trabalhos</TD></TR>
</table>
</DIV>
<? } ?>
