<?php
class arrow
	{
	var $menu;
	function mostrar($pag)
		{
			$sx .= '<Table cellpadding=0 cellspacing=0 border=0 WIDTH="100%">';
			$sx .= '<TR background="../img/icone_menu_03.png">';
			$menu = array('editor','autor','avaliador','editorar');
			$aa='00';
			$bb='02';
			$cc='05';
			for ($r=0;$r < count($menu);$r++)
				{			
					$sx .= '<TD width="10"><img src="../img/icone_menu_'.$aa.'.png">';
					$sx .= '<TD background="../img/icone_menu_'.$bb.'.png" align="center">';
					$sx .= $menu[$r];
					$sx .= '<TD width="10"	><img src="../img/icone_menu_'.$cc.'.png">';
					
					$aa='03';
					$bb='03';
					$cc='04';
				}			
			$sx .= '</table>';
			return($sx);
		}	
	}
?>
