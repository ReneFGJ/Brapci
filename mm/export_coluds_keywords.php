<?
require("cab.php");
require($include.'sisdoc_debug.php');
require("brapci_autor_qualificacao.php");
require($include."sisdoc_colunas.php");
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">
EXPORTAÇÃO DA NÚVEM DE TAGS PARA O MÓDULO PESQUISADOR
<BR><BR><BR>
<?
// gerar Nuvem de tags

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'export_coluds_keywords.php';
$http_redirect = '';
$titu[0] = "Descritores mais encontrados nos trabalhos";
?>
<TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{

$sql = "select kw_word as c_texto, total from ( ";
$sql .= "SELECT count(*) as total, `kw_keyword` FROM `brapci_article_keyword` ";
$sql .= "inner join brapci_article on `kw_article` = ar_codigo ";
$sql .= "inner join brapci_edition on ar_edition = ed_codigo ";
$sql .= "where ar_status <> 'X' ";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= "group by `kw_keyword` ";
$sql .= ") as tabela  ";
$sql .= "inner join brapci_keyword on kw_keyword = kw_codigo ";
$sql .= " where kw_idioma = 'pt_BR' ";
$sql .= "order by total desc ";
$sql .= " limit 50 ";
$rlt = db_query($sql);

$rlt = db_query($sql);

$sn = '';
$text = 'x';
$a1 = array();
$a2 = array();
$a3 = array();

$ti=0;
$tim = 0;
while ($line = db_read($rlt))
	{
	$termo = LowerCase(trim($line['c_texto']));
	$termo = troca($termo,'"','');
	$termo = troca($termo,"'",'');
	$total = $line['total'];
	if ($total > 1)
	{
		if ($total <> $tim) { $ti++; $tim = $total; }
		$ter = substr($termo,0,10);
		if (($ter <> $terx) and (strlen($termo) < 20))
			{
			array_push($a1,$termo);
			array_push($a2,$total);
			array_push($a3,$ti);
			$terx = $ter;
			}
		$x = count($a1);
		$a2[$x] = $a2[x] + $total;
		}
	}
echo '>>>>'.$ti;
$aa = array();
for ($r=0;$r < count($a1);$r++)
	{ array_push($aa,trim($a1[$r]).';'.$a3[$r]); }
sort($aa);

$crnf = chr(13).chr(10);
$sn .= $crnf;
$sn .= '<style>'.$crnf;
$sn .= '.tagclouds { '.$crnf;
$sn .= '	color : Blue; '.$crnf;
//$sn .= '	background-color : #FFFFFF; '.$crnf;
$sn .= '	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif; '.$crnf;
$sn .= '} '.$crnf;
$sn .= '.tagclouds:hover {'.$crnf;
$sn .= '	color : White; '.$crnf;
$sn .= '	background-color : #0000FF; '.$crnf;
$sn .= '} '.$crnf;
$sn .= '</style>'.$crnf;
$sn .= $crnf;
$multi = 20/$ti;
for ($r=0;$r < count($aa);$r++)
	{
	$termo = substr($aa[$r],0,strpos($aa[$r],';'));
	$link = site."index.php?dd60=0&dd61=".trim($termo);
	$ta = substr($aa[$r],strpos($aa[$r],';')+1,10);
	$size = (9+round(($ti-$ta)*$multi));
	$sn .= '<A HREF="'.$link.'" class="tagclouds">';
	$sn .= '<font style="font-size: '.$size.'px; ">';
	$sn .= $termo;
	$sn .= '</font></A> ';
	echo '<BR>'.$aa[$r]; 
	}
	
$sn = '<table width="100%"><TR><TD bgcolor="#C0C2D6">Núvem de Tags - Termos mais utilizados nos artigos de '.$dd[1].' até '.$dd[2].'</TD></TR><TR><TD><div align="justify">'.$sn.'</div></TD></TR></table>';

$myFile = "could_tags_keyword.php";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $sn);
fclose($fh);
echo $sn;
}
?>
</div>
