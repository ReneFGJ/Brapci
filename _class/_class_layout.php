<?
class layout
	{
		var $journal;
		var $menu_bg_color = '#000000'; 
		var $menu_hover_color = '#303030'; 
		var $menu_bar_color = '#00EA00'; 
		var $menu_color = '$FFFFFF';
		
		function BannerChamada()
			{
				
			}
		function BannerChamada_cp()
			{
				$cp = array();
				array_push($cp,array('$H4','journal_id','journal_id',False,False,''));
				array_push($cp,array('$S200','jn_title','Título da publicação',False,False,''));
				array_push($cp,array('$T60:8','jnl_html_cab','cabecalho',False,True,''));
				return($cp);
			}
		function HeaderLogo()
			{
				$img = '';
				$file = 'homeHeaderLogoImage.jpg';
				if (file_exists('img/'.$file)) { $img = 'img/'.$file; }
				
				if (strlen($img) > 0)
					{
						$img = '<img src="'.$img.'">';
					}
				return($img);
			}
		function TopMenu($menu)
			{
				global $id_menu,$messa;
				$id_menu++;
				$cr = chr(13).chr(10);
				$s = '<style>'.$cr;
				$s .= '#menu'.$id_menu.' ul { margin:0px; float: left; width: 100%; background-color:'.$this->menu_bg_color.'; list-style:none; color: #EDEDED; padding: 0px 0px 0px 0px; }'.$cr;
				$s .= '#menu'.$id_menu.' ul li a { padding: 4px 10px 4px 10px; float:left; background-color:'.$this->menu_bg_color.'; color: #EDEDED; text-decoration: none; border-top:3px solid '.$this->menu_bg_color.'; }'.$cr;
				$s .= '#menu'.$id_menu.' ul li a:hover { background-color:'.$this->menu_hover_color.'; color: '.$this->menu_color.'; border-top:3px solid '.$this->menu_bar_color.'; 	}'.$cr;
				$s .= '</style>'.$cr;
				$s .= '<div id="menu'.$id_menu.'">'.$cr;
				$s .= '		<UL>'.$cr;
				$s .= '			<LI>'.$cr;
				$s .= '			<a href="main.php"><img src="../img/icone_home.png" border=0></A>';
								for ($rm=0;$rm < count($menu);$rm++)
								{ $s .= '<A HREF="'.$menu[$rm][1].'">'.msg($menu[$rm][0]).'</A>'; }
				$s .= '			</LI>'.$cr;
				$s .= '		</UL>	'.$cr;
				$s .= '</div>'.$cr;					
				return($s);
			}
	}
