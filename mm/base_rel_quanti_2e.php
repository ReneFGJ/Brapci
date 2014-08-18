<?
$xsql = "select * from bp_section_tipo where st_ativo = 1 order by st_codigo ";
$xrlt = db_query($xsql);

while ($xline = db_read($xrlt))
	{
	$op .= '&';
	$op .= $xline['st_codigo'];
	$op .= ':';
	$op .= trim($xline['st_descricao']);
	}
// ar_section - ar_tipo

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano do cálculo',True,True,''));
array_push($cp,array('$Q jnl_nome:jnl_codigo:select * from brapci_journal where jnl_status = '.chr(39).'A'.chr(39).' and jnl_tipo='.chr(39).'J'.chr(39).' order by jnl_nome','Publicação',True,True,''));
$dd[1] = '1972';
$dd[2] = date("Y");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$tab_max = 450;
$http_redirect = '';
$titu[0] = 'Relatórios de Indicador '; 
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="900" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{
$sql = "select * from brapci_journal where jnl_codigo = '".$dd[3]."' ";
$rlt = db_query($sql);
$line = db_read($rlt);
$journal_name = $line['jnl_nome'];
$ano = round($dd[2]);
$sql = "
SELECT * FROM `indicador_citacao` WHERE 
`ic_journal` = '".$dd[3]."' and
ic_ano_citado >= '".($ano-15)."' and ic_ano_citado <= '".($ano)."'
and ic_autor = ''
order by ic_ano_citado desc
";
$rlt = db_query($sql);
$fi = array();
for ($r=0; $r <= 15;$r++)
	{
	array_push($fi,array('',0,0,0,0,0,0,0,0));
	}

while ($line = db_read($rlt))
	{
	$ida = $line['ic_ano_citado'];	
	$idp = $ano - $ida;
	$fi[$idp][0] = $line['ic_ano_citado'];
	$fi[$idp][1] = $line['ic_total'];
	}
	
$sql = "select * from indicador_producao ";
$sql .= " where ip_journal = '".$dd[3]."' ";
$sql .= " and ip_tipo = 'J' ";
$sql .= " and ip_ano >= '".($ano-15)."' and ip_ano <= '".($ano)."' ";
$sql .= " order by ip_ano desc ";
$rlt = db_query($sql);

echo '<HR><TT><FONT CLASS="lt4">Fator de Impacto<BR><B>'.$journal_name.'</B></FONT></TT><HR>';

while ($line = db_read($rlt))
	{
	$ida = $line['ip_ano'];	
	$idp = $ano - $ida;
	$fi[$idp][0] = $line['ip_ano'];
	$fi[$idp][2] = $line['ip_artigos'];	
	}

/* Fator de Impacto 2 anos */
$fi2  = ($fi[2][1]+$fi[3][1]);
$fi3  = $fi2+$fi[4][1];
$fi4  = $fi3+$fi[5][1];
$fi5  = $fi4+$fi[6][1];
$fi6  = $fi5+$fi[7][1];
$fi7  = $fi6+$fi[8][1];
$fi8  = $fi7+$fi[9][1];
$fi9  = $fi8+$fi[10][1];
$fi10 = $fi9+$fi[11][1];

$afi2  = ($fi[2][2]+$fi[3][2]);
$afi3  = $afi2+$fi[4][2];
$afi4  = $afi3+$fi[5][2];
$afi5  = $afi4+$fi[6][2];
$afi6  = $afi5+$fi[7][2];
$afi7  = $afi6+$fi[8][2];
$afi8  = $afi7+$fi[9][2];
$afi9  = $afi8+$fi[10][2];
$afi10 = $afi9+$fi[11][2];


echo '<TABLE width="800">';
echo '<TR>';
echo '<TH>Fator de Impacto</TH>';
echo '<TH>2 anos</TH><TH>3 anos</TH><TH>4 anos</TH><TH>5 anos</TH>';
echo '<TH>6 anos</TH><TH>7 anos</TH><TH>8 anos</TH><TH>9 anos</TH>';
echo '<TH>10 anos</TH>';
echo '</TR>';
echo '<TR class="lt2" align="center">';
echo '<TD>Citações Recebidas</TD>';
echo '<TD>'.$fi2.'</TD>';
echo '<TD>'.$fi3.'</TD>';
echo '<TD>'.$fi4.'</TD>';
echo '<TD>'.$fi5.'</TD>';
echo '<TD>'.$fi6.'</TD>';
echo '<TD>'.$fi7.'</TD>';
echo '<TD>'.$fi8.'</TD>';
echo '<TD>'.$fi9.'</TD>';
echo '<TD>'.$fi10.'</TD>';
echo '</TR>';

echo '<TR class="lt2" align="center">';
echo '<TD>Artigos publicados</TD>';
echo '<TD>'.$afi2.'</TD>';
echo '<TD>'.$afi3.'</TD>';
echo '<TD>'.$afi4.'</TD>';
echo '<TD>'.$afi5.'</TD>';
echo '<TD>'.$afi6.'</TD>';
echo '<TD>'.$afi7.'</TD>';
echo '<TD>'.$afi8.'</TD>';
echo '<TD>'.$afi9.'</TD>';
echo '<TD>'.$afi10.'</TD>';
echo '</TR>';

echo '<TR class="lt3" align="center">';
echo '<TD>FI</TD>';
if ($afi2 > 0)  { echo '<TD>'.number_format($fi2/$afi2,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi3 > 0)  { echo '<TD>'.number_format($fi3/$afi3,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi4 > 0)  { echo '<TD>'.number_format($fi4/$afi4,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi5 > 0)  { echo '<TD>'.number_format($fi5/$afi5,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi6 > 0)  { echo '<TD>'.number_format($fi6/$afi6,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi7 > 0)  { echo '<TD>'.number_format($fi7/$afi7,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi8 > 0)  { echo '<TD>'.number_format($fi8/$afi8,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi9 > 0)  { echo '<TD>'.number_format($fi9/$afi9,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
if ($afi10 > 0) { echo '<TD>'.number_format($fi10/$afi10,4).'</TD>'; } else { echo '<TD>0.0000</TD>'; }
echo '</TR>';

echo '</table>';
echo '<TABLE width="600">';
echo '<TR><TH>ano</TH><TH>Artigos</TH><TH>Citações</TH></TR>';
for ($r=0;$r < count($fi);$r++)
	{
	echo '<TR align="CENTER">';
	echo '<TD>'.$fi[$r][0].'</TD>';
	echo '<TD>'.$fi[$r][2].'</TD>';
	echo '<TD>'.$fi[$r][1].'</TD>';
	}
echo '</TABLE>';
}
?>
