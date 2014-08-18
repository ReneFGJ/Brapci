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
$tp = '0:Todos&9:Corpus categorizado&A:Amostra categorizada';
$tp = ' :Sem classificação&1:Dúvida sobre classificação&2:Para revisar&7:Artigo revisado&8:Amostra Metodológica (revisar)&9:Amostra Metodológica (OK)&6:Amostra de qualificação';
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$O '.$tp,'','Universo',True,True,''));
array_push($cp,array('$B4','','Gerar análise >>',false,True,''));
if (strlen($dd[1]) == 0) { $dd[1] = '1972'; }
if (strlen($dd[2]) == 0) { $dd[2] = date("Y"); }

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'rel_metodologia_1.php';
$http_redirect = '';
$ttt = "aos seus fins da pesquisa";
$tit = "Quantidade de trabalhos quanto ".$ttt;
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

$aut1 = array();
$aut2 = array();
$aut3 = array();
$aut4 = array();
$aut5 = array();
$fid  = array();
for ($r=$ano1; $r <= $ano2; $r++)
	{
	array_push($aut1,0);
	array_push($aut2,0);
	array_push($aut3,0);
	array_push($aut4,0);
	array_push($aut5,0);
	array_push($fid,$r);
	}

///////////////////////////////////////////////////////////////////////////////////////

$cp = array('at_metodo_1','at_metodo_2','at_metodo_3','at_tecnica_1','at_tecnica_2','at_tecnica_3','at_analise_1','at_analise_2');

for ($t=0;$t < count($cp);$t++) { $flds .= $cp[$t].','; }
$sql = "SELECT ".$flds." at_metodologia_log, at_metodologia_status,ed_ano FROM brapci_article_author  ";
$sql .= "inner join brapci_article on ar_codigo = ae_article  ";
$sql .= "inner join brapci_edition on ar_edition = ed_codigo  ";
$sql .= "inner join brapci_section on ar_section = se_codigo  ";
$sql .= " where (se_tipo = 'B' and ar_status <> 'X' )";
$sql .= " and ar_journal_id = '0000003' ";
$sql .= " and at_metodo_1 <> '' ";
if (strlen(trim($dd[3])) > 0) { $sql .= " and at_mt = '".$dd[3]."' "; }
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= " order by ed_ano ";

$rlt = db_query($sql);
$tot = 0;

$max = 1;
$pmax = 65;
//////////////////// Modelo da Matriz de Anos até a ano atual
$rsp = array();
for ($r=1900;$r <= date("Y");$r++)
	{ array_push($rsp,0); }
	
$msql = "select * from brapci_metodologias ";
$msql .= "  inner join brapci_metodologias_tp on bmt_tipo = bmtf_codigo ";
$msql .= " where bmt_ativo = 1 ";
$msql .= " order by bmt_tipo, bmt_descricao, bmt_ordem ";
$mrlt = db_query($msql);

$metq = array(); // totalizado do método
$metd = array(); // Código e nome do método
while ($line = db_read($mrlt))
	{
	array_push($metd,array(trim($line['bmt_codigo']),trim($line['bmt_descricao']),trim($line['bmtf_descricao'])));
	array_push($metq,$rsp);
	}
/////////////////////////////////////////////////////////////
while ($line = db_read($rlt))
	{
	$ano = $line['ed_ano']-1900;
	if ($ano > 0)
		{
		for ($r=0;$r < count($cp);$r++)
			{
			$fld = $cp[$r];
			$mt1 = trim($line[$fld]);
			if (strlen($mt1) > 0)
				{
				$pos = -1; /// Item novo
				for ($t=0;$t < count($metd);$t++)
					{ if ($metd[$t][0] == $mt1) { $pos = $t; } }
				if ($pos == -1)
					{
						echo "Erro de localização [".$mt1."]";
						echo '<BR>';
						exit;
					} else {
						$vlr = $metq[$pos];
						$metq[$pos][$ano] = $metq[$pos][$ano] + 1;
					}
				}
			}
		}
	}
/////////////////////////////////////////////////////////////
$sc = "X";
for ($rr=0;$rr < count($metq);$rr++)
	{
	if ($sc != $metd[$rr][2])
		{ $s .= '<TR><TD class="lt2">'.$metd[$rr][2].'</TD></TR>'; $sc = $metd[$rr][2]; }
	$s .= '<TR>';
	$s .= '<TD>';
	$s .= $metd[$rr][1];
	$sm = $metq[$rr];
	$ti = 70; // ANO INICIAL
	for ($tt=$ti;$tt < count($sm);$tt++)
		{ 
		$sa = $sm[$tt];
		if ($sa == 0) { $sa = '-'; }
		$s .= '<TD align="center">'.$sa.'</TD>'; 
		}
	}
///////////////////////////////////////////////////// GRAFICO
echo '<table class="lt0">';
	for ($tt=$ti;$tt < count($sm);$tt++)
		{ $sh .= '<TH>'.(1900+$tt).'</TH>'; }
echo '<TR><TH>Metodologia</TH>';
echo $sh;
echo $s;
echo '</table>';
}	

?>
</DIV>
