<?php
require("cab.php");
require('../_class/_class_discentes.php');
$dis = new discentes;
require('../_class/_class_curso.php');
$cur = new curso;
$tab_max = "98%";

echo $cur->relatorio_escolas_cursos();

require("../foot.php");	?>