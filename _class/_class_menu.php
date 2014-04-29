<?php
class menu
	{
	function menus_tipos()
		{
			$me = array();
			array_push($me,array(msg('publications'),'publications.php',1,'gr1'));
			$sql = "select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					array_push($me,array(trim($line['jtp_descricao']),'publications.php?dd0='.$line['jtp_codigo'],2,'gr1'));		
				}
			return($this->show_menu($me));
		}	
	function show_menu($mn)
		{
			$sx = '<div class="submenu">';
			for ($r=0;$r < count($mn);$r++)
				{
					$gr = $mn[$r][3];
					$nv = $mn[$r][2];
					$caption = $mn[$r][0];
					$link = $mn[$r][1];
					$linka = '<A HREF="'.$link.'">';
					
					$grp = $gr;
					$grn = $gr.$r;
					if ($nv==1)
						{
							if ($r > 0) { $sx .= '</ul>'; }
							$sx .= $caption;
							$sx .= '<UL id="'.$grp.'">';
						}
					if ($nv==2)
						{
							$sx .= '<li>';
							$sx .= $linka;
							$sx .= $caption;
							$sx .= '</A>';
							$sx .= '</li>';
						}
				}
			if ($r > 0) { $sx .= '</ul>'; }
			$sx .= '</div>';
			return($sx);
		}
	}
?>
