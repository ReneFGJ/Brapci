<?php
require("cab.php");

echo '</center>';
echo '<H1>Orientações duplicadas</h1>';

require("../_class/_class_docentes.php");
	$doc = new docentes;
	
			/* Recupera nome dos alunos não inseridos */
			echo $doc->docente_orientacao_duplicado();
			
require("../foot.php");
?>
