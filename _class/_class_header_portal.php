<?php
class header
	{
		function cab()
			{
				global $institution_name;
				$sx .= '
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
					<title>'.$institution_name.'</title>
					<link rel="stylesheet" type="text/css" media="screen" href="'.http.'css/font.css" />
					<link rel="shortcut icon" type="image/x-icon" href="favicon.png" />  
				</head>
				';
				return($sx);
			}
		
		function body()
			{
				$sx .= '<div id="content">';
				return($sx);
			}
		
		function box_gray($title,$sxt)
			{
				$sx = '<div class="box_gray">';
				$sx .= '<h4>'.$title.'</h4>';
				$sx .= $sxt;
				$sx .= '</div>';
				return($sx);
			}
		function header_info()
			{
				$img = substr(date("s"),0,1);
				$img = 'img/banner_0'.$img.'.png';
				$sx .= '<div id="header">';
				$sx .= '<img src="img/banner_TOP.png" width="800" id="header_top">';
				$sx .= '<img src="'.$img.'" id="header_img" width="800">';
				$sx .= '</div>';
				return($sx);
			}
			
		function menu_top()
			{
			$sx .= '<div id="menu_top">';
				$menu = array();
				array_push($menu,array('<img src="img/menu_home.png" border=0">',http.'portal.php'));
				array_push($menu,array('HOME',http.'portal.php'));
				array_push($menu,array('A EDITORA',http.'portal.php'));
				array_push($menu,array('PERIÓDICOS',http.'portal.php'));
				array_push($menu,array('ANAIS DE EVENTOS',http.'portal.php'));
				array_push($menu,array('LIVROS ELETRONICOS',http.'portal.php'));
				array_push($menu,array('CONTATO',http.'portal.php'));
				$sx .= '<UL>';
				for ($r=0;$r < count($menu);$r++)
					{
						$sx .= '<LI>';
						$sx .= '<a href="#">';
						$sx .= (trim($menu[$r][0]));
						$sx .= '</A>';
						$sx .= '</LI>';
					}
				$sx .= '</UL>';		
			$sx .= '</div>';
			return($sx);
			}
			
		function foot()
			{
				$sx .= '<div id="foot">';
				$sx .= '&copy '.date("Y").' - Reserved Rights';
				$sx .= '</div>';
				return($sx);
			}
	}
?>
