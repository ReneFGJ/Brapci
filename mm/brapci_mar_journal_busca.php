<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('usu�rio','brapci_brapci_usuario.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de usu�rios';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?

$tabela = "mar_journal";
$idcp = "mj";
$label = "Cadastro de Publica��es";

$sql = "select * from mar_works where id_m = '".sonumero($dd[0])."' ";
$sql .= " and m_bdoi = '' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$s = trim($line['m_ref']);
		$ano = $line['m_ano'];
		$sx = $s;
	} else {
		echo 'N�o localizado ou j� processado';
	}

require("mar_marcacao_iv_a.php");
?>