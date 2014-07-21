<?php
    /**
     * Exclusão de Arquivos Postados em Bolsas IC
	 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.14.18
	 * @package Bolsas PIBIC Contempladas
	 * @subpackage classe
    */
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

echo '<h1>Exclusão de Arquivos de Bolsas PIBIC</h1>';

		require("../_class/_class_pibic_bolsa_contempladas.php");
		$pb = new pibic_bolsa_contempladas;
		
		$proto = strzero($dd[0],7);
		$pb->le('',$proto);
		if (strlen($pb->line['pb_status']) > 0)
			{
				/* Mostra Informacoes */
				echo $pb->mostar_dados();
				
				require("../pibic/_ged_config.php");
		 		echo '<fieldset><legend>Arquivos</legend>';

				$ged->protocol = trim($pb->pb_protocolo);
				$ged->file_delete();
				
				echo $ged->file_list();
			
				echo $ged->file_attach_form();
				
				echo '</fieldset>';				
				
			} else {
				redirecina("pibic_bolsas_excluir_arquivos.php");				
			}
		

echo $hd->foot();
?>



