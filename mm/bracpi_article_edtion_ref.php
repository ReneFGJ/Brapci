<?		$link_edition = '<A HREF="article_edition.php?dd0='.$line['id_ed'].'" '.$class.'>';
		$sx .= $link_edition;
		$sx .= trim($jnome_abrev).', ';
		if (strlen($ed_vol) > 0) { $sx .= 'v. '.$ed_vol.', '; }
		if (strlen($ed_nr) > 0)  { $sx .= 'n. '.$ed_nr.', '; }
		if (strlen($pgfim.$pgini) > 0)
			{ 
			if ((round($pgini) > 0) and (round($pgfim) > 0)) {  $sx .= 'p. '.$pgini.'-'.$pgfim.', '; }
			if ((round($pgini) > 0) and (round($pgfim) == 0)) {  $sx .= 'p. '.$pgini.', '; }
			if ((round($pgini) == 0) and (round($pgfim) > 0)) {  $sx .= 'p. '.$pgfim.', '; }
			}
		if (strlen($ed_mes) > 4)
			{ $sx .= $ed_mes.' '; }
		$sx .= $ed_ano;
		if ((strlen($ed_nr) == 0) and (strlen($ed_vol) == 0))
			{ $sx .= '<BR><img src="include/img/icone_alert.jpg" width="30" height="30" alt="" border="0" align="absmiddle"> Edição não cadastrada, ';}
		$sx .= '</A>';
?>