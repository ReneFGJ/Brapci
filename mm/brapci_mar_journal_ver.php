<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('usuário','brapci_brapci_usuario.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de usuários';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?

$tabela = "mar_journal";
$idcp = "mj";
$label = "Cadastro de Publicações";

$sql = "select * from mar_works where m_journal = '".strzero($dd[0],7)."' ";
$sql .= " and m_bdoi = '' ";
$sql .= " order by m_ano, m_ref  ";
$rlt = db_query($sql);
echo '<TABLE width="98%" cellpadding="0" cellspacing="1" border="1">';
while ($line = db_read($rlt))
	{
	echo '<TR>';
	echo '<TD>';
	echo $line['m_ref'];
	echo '<TD>';
	echo '<span onclick="newxy2('.chr(39).'mar_marcacao_editar.php?dd0='.$line['id_m'].chr(39).',700,300);"><img src="img/icone_editar.gif"></span>';
	echo '<TD>';
	echo '<A HREF="brapci_mar_journal_busca.php?dd0='.$line['id_m'].'" target="_new"><NOBR>XX</A></TD>';

	}
echo '</table>';
?></DIV>