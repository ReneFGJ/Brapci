<?php
require("cab.php");
require($include."sisdoc_debug.php");

require("../_class/_class_discentes.php");
$dis = new discentes;

echo '<h1>Limpa alunos sem código</h1>';
$dis->limpar_aluno_sem_codigo();
echo '<h3>FIM</h3>';
require("../foot.php");	?>