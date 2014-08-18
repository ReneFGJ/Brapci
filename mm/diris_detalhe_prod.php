<?
$op3 = '';
$op2 = '';
$op1 = '';
$sx .= '<TR><TD align="right">Eq.Brapci</TD><TD><I>'.$eqbp.'</I></TD></TR>';
if (strlen($eqbp) == 0)
	{
	require($include.'sisdoc_search.php');
	$cp_nome = "autor_nome_asc";
	$op4 = uppercasesql(sisdoc_search($nome,$cp_nome));
	$sql = "select * from brapci_autor ";
	$sql .= " where ".$op4;

	$prlt = db_query($sql);

	if ($pline = db_read($prlt))
		{
		$eqbp = $pline['autor_alias'];
		$psql = "update diris set di_eq_bp = '".$eqbp."' where id_dis = 0".sonumero($dd[0]);
		$grlt = db_query($psql);
		}
	}
$sx .= '<tr><TH colspan="2"><font class="lt2">Produção na Base</font></TH></tr>';

if (strlen($eqbp) == 0) { $eqbp = 'XXXXXXX'; }

$sql = "select * ";
//$sql .= "ae_article, ";
$sql .= " from brapci_article_author ";
$sql .= " inner join brapci_autor   on 	autor_codigo = ae_author ";
$sql .= " inner join brapci_article on 	ar_codigo = ae_article ";
$sql .= " inner join brapci_journal on 	jnl_codigo = ar_journal_id ";
$sql .= " inner join brapci_edition on ar_edition = ed_codigo ";
$sql .= " where (ae_author = '".$eqbp."') or (autor_alias = '".$eqbp."' ) ";
$sql .= " order by ed_ano desc, ed_data_publicacao desc ";

$prlt = db_query($sql);
$artigos = array();
$journal = array();

$ano = array();
$sec = array();
$amin = date("Y");
while ($pline = db_read($prlt))
	{
	if (strlen($op3) > 0) { $op3 .= ' or '; }
	$op3 .= "( ar_codigo = '".$pline['ar_codigo']."') ";	
	if (strlen($op2) > 0) { $op2 .= ' or '; }
	$op2 .= "( ae_article = '".$pline['ar_codigo']."') ";	
	if (strlen($op1) > 0) { $op1 .= ' or '; }
	$op1 .= "( kw_article = '".$pline['ar_codigo']."') ";	

	$jnl = trim($pline['jnl_nome']);
	$jnlo = 0;
	for ($nx=0;$nx < count($journal);$nx++)
		{
		if (($journal[$nx][1] == $jnl) and ($jnlo == 0))
			{ $journal[$nx][0] = $journal[$nx][0] + 1; $jnlo = 1; }
		}
	if ($jnlo == 0) { array_push($journal,array(1,$jnl)); }
	
	$vano = round($pline['ed_ano']);
	array_push($artigos,$pline['ae_article']);
	array_push($ano,$vano);
	array_push($sec,$pline['ar_section']);
	$xl = $pline;
	if ($vano < $amin) { $amin = $vano; }
	}

asort($journal);
$sp = '';
$jt = 0;
for ($nr=0;$nr < count($journal);$nr++)
	{
	$jt = $jt + $journal[$nr][0];
	$sp .= '<TR '.coluna().'>';
	$sp .= '<TD>'.$journal[$nr][1].'</TD>';
	$sp .= '<TD align="center">'.$journal[$nr][0].'</TD>';
	$sp .= '</TR>';
	}
$sp .= '<TR><TD align="right"><B>Total</B></TD><TD align="center"><B><nobr>'.count($journal).' / '.$jt.'</B></TD></TR>';
	
/** Contagem de produção  por ano */
$anos = array();
for ($ra = $amin; $ra <= date("Y");$ra++)
	{ array_push($anos,array($ra,0)); }

$sg = '';
$st = '';
for ($ra=0;$ra < count($ano);$ra++)
	{
	$xano = ($ano[$ra]-$vano);
	$anos[$xano][1] = $anos[$xano][1]+1;
	}

$total = 0;
for ($ra=0;$ra < count($anos);$ra++)
	{
	 $total = $total + $anos[$ra][1];
	 $sg .= 'data.addRow(["'.$anos[$ra][0].'", '.$anos[$ra][1].']);';
	 $sg .= chr(13).chr(10);
	 if ($anos[$ra][1] > 0)
	 	{ $st .= '<TR '.coluna().'><TD align="center">'.$anos[$ra][0].'</TD><TD align="center">'.$anos[$ra][1].'</TD></TR>'; }
	}
$st .= '<TR '.coluna().'><TD align="center"><B>Total</B></TD><TD align="center">'.$total.'</TD></TR>';
/*** Gera o gráfico **/
?>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script> 
    <script type="text/javascript"> 
      google.load('visualization', '1', {packages: ['corechart']});
    </script> 
    <script type="text/javascript"> 
      function drawVisualization() {
  // Create and populate the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'x');
  data.addColumn('number', 'Artigos');
  <? echo $sg; ?>
  // Create and draw the visualization.
  new google.visualization.LineChart(document.getElementById('visualization')).
      draw(data, {curveType: "function",
                  width: 800, height: 400,
                  vAxis: {maxValue: 10}}
          );
}
 
 
      google.setOnLoadCallback(drawVisualization);
    </script> 
<table>
<TR valign="top"><TD>
<div id="visualization" style="width: 800px; height: 400px;"></div> 
<TD>
<Table width="100" class="lt0">
<TR><TH>Ano</TH><TH>Produção</TH></TR>
<?=$st;?>
</TABLE>

<TD>
<Table width="300" class="lt0">
<TR><TH>Local de publicação</TH><TH>Quant.</TH></TR>
<?=$sp;?>
</TABLE>

</TD>
</TD></TR>
</table>

<?
/** Palavras-chave **/
require("diris_detalhe_prod_keyword.php");
require("diris_detalhe_prod_colaboracao.php");
require("diris_detalhe_prod_referencia.php");
?>


