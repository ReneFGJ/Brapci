<?php
require("cab.php");
require($include."sisdoc_debug.php");
require("../_class/_class_discentes.php");
$dis = new discentes;

$dis->limpar_turno_curso_estudante();

require("../foot.php");	?>