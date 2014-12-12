<?php
require("cab.php");
require($include.'sisdoc_menus.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
require($include.'sisdoc_colunas.php');
echo '<div id="content">';
echo '<h2>Lista de membros por perfil</h2>';

//if (($perfil->valid('#ADM')) or ($perfil->valid('#MAS')))
 
	{
		echo $perfil->display_perfil_member();
	}
echo '</div>';
require("foot.php");
?>
<script>
	$("#content").corner();
</script>
