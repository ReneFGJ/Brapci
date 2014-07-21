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

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$S7','','Informe o n. protocolo',True,True));
array_push($cp,array('$B8','','Buscar >>>',False,False));

$tela = $form->editar($cp,'');

echo '<h1>Exclusão de Arquivos de Bolsas PIBIC</h1>';
if ($form->saved > 0)
	{
		require("../_class/_class_pibic_bolsa_contempladas.php");
		$pb = new pibic_bolsa_contempladas;
		
		$proto = strzero($dd[1],7);
		$pb->le('',$proto);
		if (strlen($pb->line['pb_status']) > 0)
			{
				redirecina("pibic_bolsas_excluir_arquivos_a.php?dd0=".$dd[1]);				
			} else {
				echo $tela;
			}
		

	} else {
		echo $tela;
	}
echo $hd->foot();
?>



