<?
function miolo($imenu, $tipo){
	echo '<TABLE border="0" align="center" width="750">';
	echo '<TR class="lt0" align="left"><TD>';
	for($pos=0; $pos<=sizeof($imenu); $pos++){
		if ($tipo==1){
			echo "<a href=".$imenu[$pos][0].">".$imenu[$pos][1]."</a>";
			if ($pos < (sizeof($imenu) -1)){echo " | ";}
		}
		
		if ($tipo==2){
			echo "<a href=".$imenu[$pos][0].">".$imenu[$pos][1]."</a>";
			if ($pos < (sizeof($imenu) -1)){echo " &raquo ";}
		}

		if ($tipo==3){
			if ($pos < (sizeof($imenu) -1)) 
				echo "<a href=".$imenu[$pos][0].">".$imenu[$pos][1]."</a>";
			else
				echo "<a href=".$imenu[$pos][0]."><strong>".$imenu[$pos][1]."</strong></a>";
			
			if ($pos < (sizeof($imenu) -1)){echo " &raquo ";}
		}
	}
	echo '</TD></TR>';
	echo '</table>';
}
?>