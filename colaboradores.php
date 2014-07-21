<?php
require("cab.php");
require($include.'sisdoc_debug.php');
/* Dados do Usuario */
	echo '<table width="100%"><TR><TD>';	
	echo $user->listar_colaboradores();
	
	echo '</TD></TR></TABLE>';
	
require("foot.php");
?>