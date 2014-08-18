<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('palavras-chave','brapci_brapci_keyword.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de palavras chaves';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "tci_keyword";
$idcp = "kw";

$sql = "select * from ".$tabela." where id_kw = ".$dd[0];
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$cdo = $line['kw_codigo'];
	
	$sql = "select * from tci_conceito_relacionamento ";
	$sql .= "left join tci_keyword on kw_codigo = crt_termo ";
	$sql .= " where crt_conceito = '".$cdo."' ";
//	$sql .= " order by crt_descricao ";
	echo $sql;
	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
		echo '<font class="lt3">'.$line['kw_word'].'</font>';
		}
	}

echo '</table>';
?></DIV>