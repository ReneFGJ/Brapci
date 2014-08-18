<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'brapci_rel_quanti_1.php';
$http_redirect = '';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{
$sql = "SELECT count(*) as total, jnl_nome, jnl_nome_abrev FROM brapci_article ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
$sql .= " left join brapci_section on ar_section = se_codigo ";
$sql .= " where (se_tipo = 'B'  and ar_status <> 'X' )";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= " group by jnl_nome_abrev, jnl_nome ";
$sql .= " order by total desc";

$rlt = db_query($sql);
$tot = 0;
$tota = 0;
while ($line = db_read($rlt))
	{
	$tota++;
	$tot = $tot + $line['total'];
	$sx .= '<TR '.coluna().'>';
	$sx .= '<TD>'.$line['jnl_nome'].'</TD>';
	$sx .= '<TD>'.$line['jnl_nome_abrev'].'</TD>';
	$sx .= '<TD align="right">'.$line['total'].'</TD>';
	$sx .= '</TR>';
	}
?>
<font class="lt3">Ano de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<table width="98%" class="lt2" align="center">
	<TR><TH>Publicação</TH><TH>Artigos</TH></TR>
	<?=$sx;?>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tot;?> em <?=$tota;?> publicações</B></TD></TR>
</table>

<?	
}


$titu[0] = 'Relatórios Quantitativos da Base BRAPCI - Quantidade de Artigos / Revista'; 
?>
</DIV>
