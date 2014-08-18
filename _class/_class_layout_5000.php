<?php
/**
 * Layout 5000
 */

 class layout
 	{
	function mostrar_ensaio($size=400,$file='IMG_####.jpg',$seq1=0,$seq2=14)
		{
			for ($r=$seq1; $r <= $seq2; $r++)
				{
					global $jid;
					$img = troca($file,'####',strzero($r,4));
					$img = http.'public/'.$jid.'/images/'.$img;
					$sx .= '<img src="'.$img.'" height="'.$size.'" id="IMG'.$r.'">';
					
					return($sx);
				}
		} 		
	function capa()
		{
			global $pb;
			$sx = $pb->articles_resumo();
			return($sx);
		}
	function issues($jid=0)
		{
			global $path;
			$issue = new issue;
			$rn = $issue->publication_list($jid);
			
			for ($r=0;$r < count($rn);$r++)
				{
					$line = $rn[$r];
					$capa = trim($line['issue_capa']);					
					$link = '<A HREF="'.http.'pb/index.php/'.$path.'?dd99=issue&dd0='.$line['id_issue'].'">';

					$sx .= '<div class="capas"><center>';
					$sx .= $link;
					if (strlen($capa) > 0)
						{ $capa = '<img src="'.http.'public/'.$jid.'/capas/'.$capa.'" height="140" border=0><BR>';	}
					
					$sx .= $capa;
					$sx .= '</A>';
					$sx .= 'v.'.trim($line['issue_volume']);
					$sx .= ', n.'.trim($line['issue_number']);
					$sx .= ', '.trim($line['issue_year']);
					$sx .= '</div>';
				}
			return($sx);
		} 	
	function cab_idiomas()
		{
			global $path;
			$sx .= '<form method="post" action="'.page().'/'.$path.'">';
			$sx .= '<input type="image" src="images/login.jpg" alt="English" />';
			$sx .= '</form>';
		}	
	function foot()
		{
			$sx .= '</div></div>';
			return($sx);
		}
 	function sumario($txt)
		{
			return($txt);
		}
	function cab()
		{
			global $jid, $menu, $pb;
			
			$sx = '';
			$img = http.'public/'.$jid.'/images/homeHeaderLogoImage.png';
						
			/* TOPO */
			$sx .= '<div id="cab">'.chr(13);
			$sx .= '<center>'.chr(13);
			$sx .= '<IMG SRC="'.$img.'" width="870">'.chr(13);
			$sx .= '</div>'.chr(13);
			$sx .= '<center>'.chr(13);
			$sx .= '<div id="content">'.chr(13);
			$sx .= $this->cab_topmenu();
			
			$sx .= $this->cab_idiomas();
			return($sx);
		}
	function cab_topmenu()
		{
			global $menu,$path,$jid,$pb;
			//$jid = $this->jid;
			/* background */
			if (strlen($pb->cor_menu) > 0) { $sxt .= ' style="background-color: '.$pb->cor_menu.';" '; }
			$sx = '<div id="cssmenu" '.$sxt.'>'.chr(13);
			$sx .= '<UL>'.chr(13);
			for ($r=0;$r < count($menu);$r++)
				{
					$link = $path.'?dd99='.lowercasesql($menu[$r]);
					$sx .= '	<LI><A HREF="'.$link.'"><span>';
					$sx .= msg($menu[$r].'_'.strzero($jid,4));
					$sx .= '</span></A></LI>';
					$sx .= chr(13);
				}
			$sx .= '</UL>'.chr(13);
			$sx .= '</div>'.chr(13);
			$sx .= '<center>'.chr(13);
			$sx .= '<div id="content_center">'.chr(13);
			return($sx);
		}

		
 	} 
?>