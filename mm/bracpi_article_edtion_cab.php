<?
		$sx .= '<TABLE width="100%" border="1">';
		$sx .= '<TR><TD>';
		$link_edition = '<A HREF="article_edition.php?dd0='.$line['id_ed'].'" class="lt2">';
		$sx .= '<font class="lt2">';
		$class = 'class="lt2"';
		require("bracpi_article_edtion_ref.php");
		//////////////////////////////////////////////////////////////
		$linkd = 'onclick="newxy2('.chr(39).'brapci_edition_ed.php?dd0='.intval($cod).'&dd10='.$key.chr(39).',640,150);"';
		$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="" border="0" '.$linkd.' >';
		$sx .= $sa1;
		
		//////////////////////////////////////////////////////////////
		$linkd = 'onclick="newxy2('.chr(39).'brapci_section_ed.php?dd0='.$cod.'&dd10='.$key.chr(39).',640,150);"';
		$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="" border="0" '.$linkd.' >';
		
		$sx .= '<TD align="center">'.$secao_no;
		if ($ed_editar == 1) 
			{ $sx .= tips($sa1,'Alterar seção da publicação'); }
		$sx .= '</TD>';
		$sx .= '<TD align="right"><I>status</I>&nbsp;<B>'.status_artigo($sta).'</B></TD>';
		$sx .= '</TR>';
		$sx .= '</TABLE>';
?>