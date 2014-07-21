<?
/*** Modelo ****/
require("cab.php");
ini_set('max_execution_time','2360');
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_pibic_mirror.php');

	$clx = new mirror;
	$cp = $clx->cp();
	
if (strlen($dd[1]) > 0)
	{
	$mes = 1;
	$clx->edital = $dd[3];
	$clx->gerar_espelho($dd[2],$dd[1]);
	
	$clx->espelho_geral_ano();
	} else {
		echo '<form method="post" action="'.page().'">';
		echo '<table align="center">';
		echo '<TR>';
		echo '<TD>';
		echo msg('month').':';
		echo '<select name="dd1">';
		for ($m=1;$m <= 12;$m++)
			{
				echo '<option value="'.$m.'">'.$m.'</option>';
			}	
		echo '</select>';

		echo '<TD>';
		echo msg('year').':';
		echo '<select name="dd2">';
		for ($m=date("Y");$m >= 2009;($m = $m - 1))
			{
				echo '<option value="'.$m.'">'.$m.'</option>';
			}	
		echo '</select>';
		
		echo '<TD>';
		echo msg('edital').':';
		echo '<select name="dd3">';
		for ($m=date("Y");$m >= 2009;($m = $m - 1))
			{
				echo '<option value="'.$m.'">'.$m.'</option>';
			}	
		echo '</select>';

		echo '<TD>';
		echo '<input type="submit" name="acao" value="Processar dados>>>" id="acao"/>';
		echo '</table>';
	}
	
require("../foot.php");		
?> 
