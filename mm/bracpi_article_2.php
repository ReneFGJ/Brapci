<?
		///////////////////////////////////////////////////////////////// idioma 
		$sx .= '<BR>';
		$sx .= '<B>'.$resumo_sel.'</B> ('.$idioma_rsm.')';
		if (strlen($idioma_sel) == 0) { $idiona_sel = 'en'; }
		if ($ed_editar == 1) 
			{ 		
			$link = 'onclick="newxy2('.chr(39).'brapci_resumo_ed.php?dd0='.$dd[0].'&dd1='.$idioma_sel.'&dd2='.$resumo_sel.'&dd3='.$id_sel.'&dd10='.$key.chr(39).',640,400);"';
			$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="" '.$link.' border=0 >';
			$sx .= tips($sa1,'Editar o resumo');
			}

			{
			$sx .= '<DIV id="resumo">';
			$sx .= $resumo_mst;
			$sx .= '</DIV>';
			}
		
		///////////////////// busca palavras chaves
		$sqlx = "select * from brapci_article_keyword ";
		$sqlx .= " inner join brapci_keyword on kw_codigo = kw_keyword ";
		$sqlx .= " where kw_article = '".$cod."' ";
		$sqlx .= " and kw_idioma = '".$idioma_sel."' ";
		$sqlx .= " order by kw_ord ";
		$rltx = db_query($sqlx);
		
		$sxa = '<font class="lt1">';
		$id7=1; // nova posicao
		while ($line = db_read($rltx))
			{
			$id7++;
			$link = '';
			if ($ed_editar == 1) 
				{ 
				$link = 'onclick="newxy2('.chr(39).'brapci_keywors_ed2.php?dd0='.$line['id_ak'].'&dd1='.$cod.'&dd2='.$line['kw_article'].'&dd3='.$id_sel.'&dd10='.$key.chr(39).',640,400);"';
				$link = '<A HREF="#'.$line['id_ak'].'" '.$link.'>'; 
				}
				
			$word = trim($line['kw_word']);
			$word = UpperCase(substr($word,0,1)).substr($word,1,strlen($word));
			$word = $link.trim($word).'</A>. &nbsp;';
			$sxa .= trim($word).'</A>';
			}
		$sxa .= '</font>';

		$link = 'onclick="newxy2('.chr(39).'brapci_keywors_ed.php?dd1='.$cod.'&dd2='.$idioma_sel.'&dd3='.$keyw[0].'&dd3='.$id_sel.'&dd7='.$id7.'&dd10='.$key.chr(39).',640,400);"';
		$sx .= '<B>'.$keywor_sel.'</B>';
		if ($ed_editar == 1) 
			{ 
			$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="" '.$link.'border=0 >'; 
			$sx .= tips($sa1,'Incluir palavra chave / keyword');
			}

		
		$sx .= $sxa;
?>