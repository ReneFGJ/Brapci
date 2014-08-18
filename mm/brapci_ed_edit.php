<?
$debug = true;
require("cab.php");
require($include."sisdoc_editor.php");
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">
<?	
	$cpn = $dd[99];
	
	if ($cpn == 'diris')
		{
			require("../db_diris.php");
		}
	require($include.'sisdoc_colunas.php');
	require($include.'sisdoc_form2.php');
	require('cp/cp_'.$cpn.'.php');
	require($include.'cp2_gravar.php');
	$http_edit = 'brapci_ed_edit.php?dd99='.$dd[99];
	$http_redirect = 'updatex.php?dd0='.$dd[99];
	$tit = strtolower(troca($dd[99],'_',' '));
	$tit = strtoupper(substr($tit,0,1)).substr($tit,1,strlen($tit));
	echo '<CENTER><font class=lt1>Cadastro de '.$tit.'</font></CENTER>';
	?><TABLE width="<?=$tab_max?>" align="center" class="lt0"><TR><TD><?
	editar();
	?></TD></TR></TABLE>
</TD>
</TR>
</TABLE>
</DIV>