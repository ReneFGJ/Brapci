<?php

class menus
	{
		var $mn;

		function type_03()
			{
				$menu = $this->mn;
				$tps = 0;
				$seto = '';
				for ($x=0;$x <= count($menu); $x++)
					{
					$xseto = $menu[$x][0];
					if (!($seto == $xseto)) { $tps++; $seto = $xseto; }
					}
				/* menus */
				$col = 0;
				$cola = 0;
				$mcol = intval($tps/2);
				$cm1 = '';
				$cm2 = '';
				$seto = 'x';
				for ($x=0;$x <= count($menu); $x++)
					{
					$xseto = $menu[$x][0];
					if (!($seto == $xseto))
						{
						$cola++;
						if ($cola > $mcol) { $col = 1; }
						$seto = lowercasesql($xseto);
						$seto = troca($seto,' ','_');
						$img_icone = 'img/icone_'.$seto.'.png';
						$updir = $_SERVER['SCRIPT_FILENAME'];
						$xx = strlen($updir);
						while ($xx > 0)
							{
							if (substr($updir,$xx,1) == '/') 
								{ $updir = substr($updir,0,$xx); $xx = 0; }
							$xx--;
							}
						$image = trim($updir) . '/'.$img_icone;
	
						if (!(file_exists($image)))
							{ 	$img_icone = $include.'img/icone_noimage.png'; 	}
							
						////////////////////////////////////////////
						$sc = '<TR><TD colspan="10">';
						$sc .= '<TABLE width="100%" cellpadding="0" cellspacing="2" border="0"  class="menu_tit">';
						$sc .= '<TR><TD rowspan="2" width="48"><img src="'.$img_icone.'" width="48" height="48" alt=""></TD>';
						$sc .= '<TD width="80%"><BR><NOBR><B>'.$xseto.'&nbsp;</TD>';
						$sc .= '<TR><TD><HR width="100%" size="2"></TD></TR>';
						$sc .= '</TABLE>';
						$sc .= '<TR class="lt1"><TD><UL>';
						$seto = $xseto;
						$xcol=0;
					} else  { $sc = ''; }
						if (isset($menu[$x][2]))		
					{ 
						$link = '<A href="'.$menu[$x][2].'" class="menu_item">';
						if (strlen(trim($menu[$x][2])) == 0) { $link = ''; }
						$pre = ''; $pos = '';
						
						//////////////////////////////////////////////////////////////////////////// Título Em BOld
						if ((substr($menu[$x][1],0,2) != '__') and (strlen($link) == 0))
							{ $menu[$x][1] = '<B>'.$menu[$x][1].'</B>'; }
						
						if (substr($menu[$x][1],0,2) == '__')
							{ $menu[$x][1] = substr($menu[$x][1],2,100); $pre = '<UL>'; $pos = '</UL>'; }
					if ($col == 0)
						{
							$cm1 .= $sc;
							$cm1 .= $pre.'<LI class="menu_li">'.$link.$menu[$x][1].'</A><BR>'.$pos; 
						} else {
							$cm2 .= $sc;
							$cm2 .= $pre.'<LI class="menu_li">'.$link.$menu[$x][1].'</A><BR>'.$pos; 
						}
					}
				}
				$sm = '<TABLE width="'.$tab_max.'" border=0 align="center">';
				$sm .= '<TR valign="top">';
				$sm .= '<TD width="48%"><table width="100%">'.$cm1.'</table></TD>';
				$sm .= '<TD width="4%"></TD>';
				$sm .= '<TD width="48%"><table width="100%">'.$cm2.'</table></TD>';
				$sm .= '</TR>';
				$sm .= '</TABLE>';
				return($sm);				
			}
		
		function type_04()
			{
				
				/* mostra */
				$menu = $this->mn;
				$tps = 0;
				$st = '';
				$seto = '';
				$js = '';
				$sh = '<div id="nav"><ul>';	
				for ($x=0;$x <= count($menu); $x++)
					{
					$seto = $menu[$x][0];
					
					if ($seto != $xseto) 
						{
							if (strlen($st) > 0) { $st .= '</DIV>'.chr(13); }

							$xseto = $seto;
							$seto = troca(lowercasesql($seto),' ','_');
							$seto = troca($seto,'!','i');

							if ($x==0) { $class = 'class="active"'; } else { $class=""; }
							$sh .= '<li><a  href="#" onClick="goto(\'#'.$seto.'\', this); return false">';
							$sh .= $xseto;
							$sh .= '</a></li>';
							
							
							$st .= '<div id="'.$seto.'" class="contentbox">';
							$st .= '<H3>'.$xseto.'</h3>';
							$js .= '';
						}
					$caption = $menu[$x][1];
					if (substr($caption,0,2) == '__')
						{ $class = "nav_item_half"; } else { $class = "nav_item"; }
					$onclick = "window.location = '".$menu[$x][2]."'; ";
					$st .= '<div class="'.$class.'" onclick="'.$onclick.'">';
					$caption = troca($caption,'__','');
					$st .= $caption;
					$st .= '</div>';
					}
				if (strlen($st) > 0) { $st .= '</DIV>'.chr(13); }
				$sh .= '</ul></div>';
				
				/* Content */
			
				/* Menu */
				$sx = '						
						<div id="content">
						<div class="contentbox-wrapper">
						';
				$sx .= $st;
				$sx .= '
						</div></div>';
				
				/* menus */
				$sx = $sh . $sx;
			
				$sx .= '<style>
						#content{		
							overflow:hidden;
							-moz-box-shadow: 0 0 2px 2px #ccc;
							-webkit-box-shadow: 0 0 2px 2px #ccc;
							box-shadow: 0 0 2px 2px #ccc;
							height: 400px;
							}		
						.nav_item { float: left; width: 120px; height: 120px; background-color: #ECECEC; margin: 5px; padding: 10px 0px 10px 30px; }
						.nav_item_half { float: left; width: 120px; height: 40px; background-color: #ECECEC; margin: 5px; padding: 10px 0px 10px 30px; }
						.nav_item:hover { background-color: #DCDCDC; cursor: pointer; }
						.nav_item_half:hover { background-color: #DCDCDC; cursor: pointer; }
						.contentbox-wrapper{			
							position:relative; 
							left:0; 
							width:300px; 
							height:100%;
							}
						.contentbox{
							width:580px; 
							height:100%; 
							float:left; 
							padding:10px;
							background:#fff;
							}						
						#nav {
							margin-top:10px;
							background: url("navbg.png") repeat-x center bottom;
							border-bottom: 1px solid #DDDDDD;
							padding: 5px 10px;
							}
						#nav ul li
							{
							display:inline;
							margin-right:10px;
							}
						#nav a.active {
							font-weight:bold;
						}
						</style>
						
						<script>
							function goto(id, t){	
							$(".contentbox-wrapper").animate({"left": -($(id).position().left)}, 600);
							$(".contentbox-wrapper").animate({"top": -($(id).position().top)}, 600);

							// remove "active" class from all links inside #nav
    						$(\'#nav a\').removeClass(\'active\');
		
							// add active class to the current link
    						$(t).addClass(\'active\');	
							}
						</script>						
						';
				return($sx);
			}
		
	}

function menus($mn,$tipo)
	{
		$menu = new menus;
		$menu->mn = $mn;
		$sx .= $menu->type_04();
		
		echo $sx;
	}
?>	



