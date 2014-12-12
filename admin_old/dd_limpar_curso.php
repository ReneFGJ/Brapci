<?php
require("cab.php");
require('../_class/_class_discentes.php');
$dis = new discentes;
$tab_max = "98%";
?>
<TABLE width="98%" align="center" border="0">
<TR>
<?
$dis->limpar_turno_curso_estudante();
?>
</TABLE>
<? require("../foot.php");	?>