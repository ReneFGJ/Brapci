<?php
require("cab.php");

echo '</center>';
echo '<H1>Identificar alunos sem código</h1>';

require("../_class/_class_docentes.php");
	$doc = new docentes;

require("../_class/_class_discentes.php");
	$dis = new discentes;
	
			/* Recupera nome dos alunos não inseridos */
			$crachas = $doc->docente_orientacao_sem_nome_aluno();
			if (count($crachas) > 0)
			{
				for ($r=0;$r < count($crachas);$r++) 
				{
					echo '<BR>Buscando '.$crachas[$r];
					$dis->consulta($crachas[$r]); 
				} 
			}
			/* Redirecionamento */

require("../foot.php");
?>
