<div id="main_menu">
	<h1><?php echo $title_menu;?></h1>
	<?php
	$xtit = '';
	$div = 0;
	for ($r = 0; $r < count($menu); $r++) {
		$tipo = $menu[$r][2];

		switch ($tipo) {
			case 'BTN' :
				echo '
				<a href="' . base_url('index.php' . $menu[$r][3]) . '" onclick="parent.location=\'' . $menu[$r][3] . '\'" class="no-undeline">     
					<div id="icone-cip-2" class="icone-iniciacao-cientifica icone-cip">
						<h2 class="icone-iniciacao-cientifica-cor">' . $menu[$r][0] . '</h2>
						<p>
							' . $menu[$r][1] . '
						</p>						
					</div>
				</a>
			';
				break;
			case 'BTA' :
				echo '
				<a href="' . base_url('index.php' . $menu[$r][3]) . '" onclick="parent.location=\'' . $menu[$r][3] . '\'" class="no-undeline">     
					<div id="icone-cip-2" class="icone-admin icone-cip">
						<h2 class="icone-admin-cor">' . $menu[$r][0] . '</h2>
						<p>
							' . $menu[$r][1] . '
						</p>						
					</div>
				</a>
			';
				break;
			case 'BTB' :
				echo '
				<a href="' . base_url('index.php' . $menu[$r][3]) .'" onclick="parent.location=\'' . $menu[$r][3] . '\'" class="no-undeline">     
					<div id="icone-cip-2" class="icone-green icone-cip">
						<h2 class="icone-green-cor">' . $menu[$r][0] . '</h2>
						<p>
							' . $menu[$r][1] . '
						</p>						
					</div>
				</a>
			';
				break;				
			case 'ITE' :
				$tit = $menu[$r][0];
				if ($tit != $xtit) {
					if ($div == 1) { echo '</div>';
					}
					echo '<div class="menu_item">';
					echo '<span class="lt3"><B>' . $tit . '</B></span><BR>';
					$xtit = $tit;
					$div = 1;
				}
				echo '
				<a href="' . base_url('index.php' . $menu[$r][3]) .'" onclick="parent.location=\'' . $menu[$r][3] . '\'" class="link no-undeline">     
					<span class="menu_item_li">' . $menu[$r][1] . '</span><BR>
				</a>
			';
			break;
			case 'ITS' :
				$tit = $menu[$r][0];
				if ($tit != $xtit) {
					if ($div == 1) { echo '</div>';
					}
					echo '<div class="menu_item">';
					echo '<span class="lt3"><B>' . $tit . '</B></span><BR>';
					$xtit = $tit;
					$div = 1;
				}
				echo '
				<a href="' . base_url('index.php' . $menu[$r][3]) .'" onclick="parent.location=\'' . $menu[$r][3] . '\'" class="link no-undeline">     
					<span class="menu_item_li_3">' . $menu[$r][1] . '</span><BR>
				</a>
			';
			break;			
		}
	}
	/* Fecha div */
	if ($div == 1) { echo '</div>';
	}
?>
</div>