<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_debug.php");

$tabela = "";
$cp = array();
$op = ' : ';
$op .= '&ARTIC:Artigos';
$op .= '&LIVRO:Livros';
$op .= '&LINK:Link de Internet';
$op .= '&NORMA:Normas técnicas';
$op .= '&TESE:Teses';
$op .= '&DISSE:Dissertação';
$op .= '&TCC:Trabalhos de conclusão';
$op .= '&ANAIS:Anais de congresso';
$op .= '&RELAT:Relatórios técnicos';
$op .= '&LEI:Leis e resoluções';
$op .= '&JORNA:Jornais diários';

array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$O '.$op,'','Tipo de fonte',True,True,''));
array_push($cp,array('$O 1:todos&2:somente as com *+(n. ou v.)','Filtro',True,True,''));
array_push($cp,array('$B4','','Gerar análise >>',false,True,''));
if (strlen($dd[1]) == 0) { $dd[1] = '1972'; }
if (strlen($dd[2]) == 0) { $dd[2] = date("Y"); }

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'google_chart.php');
$tab_max = 450;
$http_edit = 'mar_marcacao_tipo_ano.php';
$http_redirect = '';
$tit = "Relação de obras citadas por categoria";
$metodologia = "<B>Metodologia:</B> Identificar as obras citadas por categoria.";


?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit;?></center>
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
	
	echo '</center>';
	$sql = "select * from mar_works 
	where (m_ano >= ".$ano1." and m_ano < ".$ano2.") 
	and m_tipo = '".$dd[3]."'
	order by m_ref";
	$rlt = db_query($sql);
	echo '<TABLE width="800" align="center" class="lt1"><TR><TD>';
	echo '<UL>';
	$tot = 0;
	while ($line = db_read($rlt))
		{
		$ref = $line['m_ref'];
		$ref = troca($ref,'<','&lt;');
		$ref = troca($ref,'>','&gt;');

		$link = 'http://www.brapci.ufpr.br/mm/mar_marcacao_editar.php?dd0='.$line['id_m'];
		$link = '<A HREF="'.$link.'" target="newxy">';
		$link2 = '<span onclick="newxy2('.chr(39).'http://www.brapci.ufpr.br/mm/mar_marcacao_remove_link.php?dd0='.$line['id_m'];
		$link2 .= chr(39).',300,300);"><font color="#ff8040">[Remova Link]</font></span>';
		$link3 = '';
		if (strpos($ref,' v.') > 0) { $link3 = $link2; }
		if (strpos($ref,', v.') > 0) { $link3 = $link2; }
		if (strpos($ref,',n.') > 0) { $link3 = $link2; }
		if (strpos($ref,' n.') > 0) { $link3 = $link2; }
		if (strpos($ref,'Dispon') > 0) {} else {$link3 = ''; }
		if ((($dd[4] == 2) and (strlen($link3) > 0)) or ($dd[4] == 1))
			{
			echo '<LI><font color="#808080">'.$ref.'</font>'.$link.'<font color="#6f6f6f">[ed]</font></A>'.$link3.'</LI>';
			}
		$tot++;
		}
	echo '</UL>';
	echo 'Total de '.$tot.' referências nesta delimitação';
	echo '</TD></TR></table>';
}	

?>
</DIV>
