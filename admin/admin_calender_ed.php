<?php
 /**
  * Admin Menu
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2012 - sisDOC.com.br
  * @access public
  * @version v0.13.46
  * @package ProEthos-Admin
  * @subpackage calender
 */
require("cab.php");
require("../_class/_class_calender.php");

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_form2.php');

	$cl = new calendar;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = page();
	$http_redirect = '';
	$label = msg('tit_'.$tabela);

	/** Comandos de Edi��o */
	
	echo '<h2>'.$label.'</h2>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			//$cl->updatex();
			redirecina('admin_calender.php');
		}
	echo '</div>';
echo $hd->foot();
?>
