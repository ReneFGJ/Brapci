<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////
$no_menu_left = 1;

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_debug.php");

$tabela = "";
$cp = array();
$tp = '0:Todos&9:Corpus categorizado&A:Amostra categorizada';
$tp = ' :Sem classificação&1:Dúvida sobre classificação&2:Para revisar&7:Artigo revisado&8:Amostra Metodológica (revisar)&9:Amostra Metodológica (OK)&6:Amostra de qualificação';
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$H4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$H4','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$O '.$tp,'','Universo',True,True,''));
array_push($cp,array('$Q nr_descricao:nr_referencia:select * from brapci_norma_referencia','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$Q bmt_descricao:bmt_codigo:select * from brapci_metodologias where bmt_ativo = 1 order by bmt_descricao','','Tipo',True,True,''));
array_push($cp,array('$C8','','Todas as referências desta amostra',false,True,''));
array_push($cp,array('$B4','','Gerar referências >>',false,True,''));
if (strlen($dd[1]) == 0) { $dd[1] = '1972'; }
if (strlen($dd[2]) == 0) { $dd[2] = date("Y"); }

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'rel_metodologia_ref.php';
$http_redirect = '';
$ttt = "aos seus fins da pesquisa";
$tit = "Referências";
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
$ano1 = intval($dd[1]);
$ano2 = intval($dd[2]);

///////////////////////////////////////////////////////////////////////////////////////

$sql = "SELECT ar_codigo FROM brapci_article  where ";
$sql .= " at_mt = '".$dd[3]."' ";

			$cps = array('at_metodo_1','at_metodo_2','at_metodo_3','at_tecnica_1',
			'at_tecnica_2','at_tecnica_3','at_analise_1','at_analise_2');
			$tp = $dd[5];
			$wh = "";
			for ($r=0;$r < count($cps);$r++)
				{
					if (strlen($wh) > 0) { $wh .= ' or '; }
					$wh .= $cps[$r]." = '$tp' ";
				}
			$wh = ' and ('.$wh." ) and ar_status <> 'X' ";
if (strlen($dd[6]) == 0)
	{ $sql .= $wh; }
$rlt = db_query($sql);
$tot = 0;
$max = 1;
$pmax = 65;
//////////////////// Modelo da Matriz de Anos até a ano atual
$qsql = '';
$tot = 0;
while ($line = db_read($rlt))
	{
	if (strlen($qsql) > 0) { $qsql .= ' or '; }
	$qsql .= "(artigos.ar_codigo = '".$line['ar_codigo']."') ";
	}
	
	

echo '<HR>';	
	$tsql .= "select ar_ano, count(*) as total from brapcipublico.artigos ";
	$tsql .= "where ";
	$tsql .= $qsql;
	$tsql .= " group by ar_ano ";
	$rlt = db_query($tsql);
	$col = 0;
	$tota = 0;
	while ($line = db_read($rlt))
		{
			$tot = $tot + $line['total'];
			if (($col == 0) or ($col > 10))
				{ $st .= '<TR>'; $col = 0;}
			$col++;
			$st .= '<TD align="center">'.$line['ar_ano'];
			$st .= '<BR><B>'.$line['total'];
		}
	echo '<h1>'.$tot.'</h1>';
			
	echo '<TABLE width="100%">';
	echo $st;
	//echo '<TR><TD colspan=5>Total de artigos:'.$tota;
	echo '</table>';
echo '<HR>';

if ($tot > 0)
{
	require("../db_pesquisador.php");
	$ysql .= "select *,artigos.ar_codigo as ar_codigo_art,artigos.ar_doi as ar_doi2 from artigos ";
	$ysql .= "left join brapci2009.brapci_section on se_cod = artigos.ar_section ";
	$ysql .= "left join brapci2009.brapci_article on artigos.ar_codigo = brapci2009.brapci_article.ar_codigo ";
	$ysql .= "where ";
	$ysql .= $qsql;
	$ysql .= " order by artigos.ar_section, ar_asc ";
	
	$rlt = db_query($ysql);
	echo '<DIV style="text-align: left; padding: 10px;">';
	$tp = 'X';
	$ft=0;
	while ($line = db_read($rlt))
		{
		$ds = 2;
		$fc = '<font color="black">';
		$met1 = trim($line['at_metodo_1']);
		$met2 = trim($line['at_metodo_2']);
		if (($met1=='0000060') and ($met2=='0000060')) { $fc = '<font color="red">'; $ft++; $ds=0; }
		
		if ($tp != $line['se_descricao'])
			{
				$tp = $line['se_descricao'];
				echo '<HR>'.$tp.'<HR>';
			}
		$ref = $dd[4];

			//$vlink = '<span onclick="newxy2('.chr(39);
			//$vlink .= '_temp.php?dd1='.$ds.'&dd0='.$line['id_ar'].chr(39);
			//$vlink .= ',200,200);">[X]</span>';


		require('../referencia_mst.php');
		echo '</font>';
		echo $ref;
		$link = 'http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd1='.$line['ar_codigo_art'];
		$link = '<A HREF="'.$link.'" target="new">'.$fc;
		
		echo ' ('.$link.trim(substr($line['ar_doi2'],0,12)).'</A></font>)';
		echo $vlink;
		echo '<BR>';
		echo '<BR>';
		}
	}
echo '</DIV>';
echo 'Total de '.$ft.' não categorizado.';
}

?>
</DIV>
