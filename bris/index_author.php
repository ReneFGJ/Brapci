<?php
require("cab.php");
require($include."_class_form.php");
$form = new form;

require("../_class/_class_bris.php");
$br = new bris;
	
/* Atualização de dados */
if ($dd[1]=='1')
	{
		for ($r=1972;$r <= date("Y");$r++)
			{
				echo '<BR>Processando dados '.$r;
				$br->ranking_author_create($r);
				echo ' Gerando dados';
			}
	} else {
		$anoi = $dd[1];
		$anof = $dd[2];
		if (strlen($anoi) == 0) { $anoi = '1972'; }
		if (strlen($anof) == 0) { $anof = date("Y"); }
		echo '<H1>Autor Ranking '.$anoi.' - '.$anof.'</h1>';
		echo '<div id="relatorio">';
		echo $br->ranking_author($anoi,$anof);
		echo '</div>';
	}


?>

