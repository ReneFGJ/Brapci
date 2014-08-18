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
require('../classe/_class_metodologia.php');
$met = new metodologia;
$cp = $met->cp_filtro();

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
$tab_max = 450;
$http_edit = 'rl_met_1.php';
$http_redirect = '';
$metodologia = "<B>Metodologia:</B> nao definida.";

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu;?></center>
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
		$rst = $met->relatorio_01('MF');
		echo $met->mostra_relatorio($rst);		
	}
require("../foot.php");
?>
