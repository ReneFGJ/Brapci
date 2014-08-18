<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('ontologia','brapci_brapci_ontologia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Ontologias';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?
$sql = "select * from tci_conceito_relacionamento ";
$sql .= "inner join tci_thema on crt_tema = tci_codigo ";
$sql .= "inner join tci_keyword on crt_termo = kw_codigo ";
$sql .= "where id_crt = ".$dd[0];
$sql .= " and crt_rela = 'PD' ";
$rlt = db_query($sql);
$ss = '';
if ($line = db_read($rlt))
	{
	$ss = '<I>'.trim($line['tci_descricao']).'</I>';
	$ss .= '<BR>';
	$ss .= '<font class="lt3">'.$line['kw_word'].'</font>';
	
///////////////////////////////////////////////////////////////////////////////////////
	$sql = "select * from tci_conceito_relacionamento ";
	$sql .= "inner join tci_thema on crt_tema = tci_codigo ";
	$sql .= "inner join tci_keyword on crt_termo = kw_codigo ";
	$sql .= "where crt_conceito = '".strzero($dd[0],7)."' ";
	$sql .= " and crt_rela <> 'PD' ";
	$sql .= " order by crt_rela ";
	echo $sql;
	$rlt = db_query($sql);	
	$ss .= '<UL>';
	while ($line = db_read($rlt))
		{
		$termo = trim($line['kw_word']);
		$rela = trim($line['crt_rela']);
		$ss .= '<LI>'.$termo.' ('.$rela.')</LI>';
//		print_r($line);
//		echo '<HR>';
		}
	$ss .= '</UL>';
	}

	echo '<HR>';
echo $ss;

if ($user_nivel == 1)
	{
	require("brapci_brapci_ontologia_edit.php");
	}

?></DIV>