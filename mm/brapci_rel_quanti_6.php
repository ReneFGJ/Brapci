<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////
$titu[0] = 'Relatórios Quantitativos da Base BRAPCI - Quantidade de Artigos / Revista'; 

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
$http_edit = 'brapci_rel_quanti_6.php';
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
$sql = "SELECT count(*) as total, se_codigo, se_descricao, se_tipo FROM brapci_article ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
$sql .= " left join brapci_section on ar_section = se_codigo ";
$sql .= " where (se_tipo <> 'X'  and ar_status <> 'X' )";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= " group by se_codigo, se_descricao, se_tipo ";
$sql .= " order by total desc";

$rlt = db_query($sql);
$tot = 0;
while ($line = db_read($rlt))
	{
	$tot = $tot + $line['total'];
	$sx .= '<TR '.coluna().'>';
	$sx .= '<TD>--'.$line['se_descricao'].'</TD>';
	$sx .= '<TD>'.$line['se_codigo'].'</TD>';
	$sx .= '<TD>'.$line['se_tipo'].'</TD>';
	$sx .= '<TD align="right">'.$line['total'].'</TD>';
	$sx .= '</TR>';
	}
?>
<font class="lt3">Ano de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<table width="98%" class="lt2" align="center">
	<TR><TH>Publicação</TH><TH>Artigos</TH></TR>
	<?=$sx;?>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tot;?></B></TD></TR>
</table>

<?	
$sql = "SELECT count(*) as total, se_tipo FROM brapci_article ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
$sql .= " left join brapci_section on ar_section = se_codigo ";
$sql .= " where (se_tipo <> 'X'  and ar_status <> 'X' )";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= " group by se_tipo ";
$sql .= " order by total desc";
$rlt = db_query($sql);
$sx = '';
while ($line = db_read($rlt))
	{
	$sx .= '<TR><TD align="center">';
	$sx .= $line['se_tipo'].'<TD align="right">'.$line['total'];
	}
?>
<table width="150" class="lt2" align="center" border=1>
	<TR><TH>Grupo</TH><TH>Total</TH></TR>
	<?=$sx;?>
</table>
<DIV align="justify">
<BR><BR>
<B>METODOLOGIA</B>: Os tipos de seções utilizadas pelos periódicos são preservador. Dentro da Brapci são criados grupos das seções
para reunir seções, destas somentes a do grupo "B" são consideradas como Artigos Científicos e são exportadas para o módulo 
pesquisador.
<BR><BR>
</DIV>
<?
}
?>


</DIV>
