<?php
require("cab.php");
require($include.'sisdoc_menus.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
require($include.'sisdoc_colunas.php');
echo '<div id="content">';
echo '<h2>'.msg('assign_to_user_profile').'</h2>';

//if (($perfil->valid('#ADM')) or ($perfil->valid('#MAS')))
 
	{
		echo $perfil->perfil_atribui_form();	
		$ss->user_codigo = $dd[1];
		
		echo '<HR>';
		echo 'o';
		$perfil->set($dd[1]);
		echo 'o';
		echo $perfil->display();
	}
echo '</div>';
require("foot.php");
?>
<script>
	$("#content").corner();
</script>
