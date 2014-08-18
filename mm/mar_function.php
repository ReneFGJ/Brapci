<?
function MAR_tipo($ref)
	{

	/////////////////////// SUBSTITUIÇÔES PADRAO 1
	$tp = '???';
	$ref = UpperCaseSql($ref);
	$ref = troca($ref,'[','');		
	$ref = troca($ref,']','');		
	$ref = troca($ref,' : ',': ');
	$ref = troca($ref,'º','.');		
	$ref = troca($ref,'?','');
		
//	$ref = troca($ref,',',':');		

	$ref = troca($ref,'VOL ','V. ');		
	$ref = troca($ref,'VOL. ','V. ');		
	$ref = troca($ref,'NUM. ','N. ');		
	$ref = troca($ref,'Nº ','N. ');		
	$ref = troca($ref,'NO. ','N. ');		
	$ref = troca($ref,' V0 ','V. ');		
	$ref = troca($ref,',VO ','V. ');		
	
	echo '<BR><table width="600" bgcolor="#808080" border="1" align="center"><TR><TD><TT>'.$ref.'</TD></TR></table>';
	
	$ok = '';
	if (strpos($ref,'TESE (DOUTORADO') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESE DE DOUTORADO') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESE(') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESE (') > 0)
		{ $ok = 'TESE 0000000'; }
		
	//// Dissertação
	if (strpos($ref,'(DISSERTACAO DE MESTRADO)') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'DISSERTACAO (MESTRADO') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'DISSERTATION (') > 0)
		{ $ok = 'DISSE0000000'; }

	if (strpos($ref,'#') > 0)
		{ $ok = 'NC0000000'; }


	if (strpos($ref,'ANAIS ..') > 0)
		{ $ok = 'ANAIS0000000'; }
	if (strpos($ref,'ANAIS..') > 0)
		{ $ok = 'ANAIS0000000'; }
	if (strpos($ref,'PROCEEDINGS..') > 0)
		{ $ok = 'ANAIS0000000'; }
	if (strpos($ref,'PROCEDINGS..') > 0)
		{ $ok = 'ANAIS0000000'; }
	if (strpos($ref,'PROCEEDINGS…') > 0)
		{ $ok = 'ANAIS0000000'; }
	if (strpos($ref,'PAPERS...') > 0)
		{ $ok = 'ANAIS0000000'; }


/////////////////// Preserva o conteúdo para revistas
	$xref = $ref;
	$ref = troca($ref,'JAN.','V. ');
	$ref = troca($ref,'ABR.','V. ');
	$ref = troca($ref,'MAR.','V. ');
	$ref = troca($ref,'JUL.','V. ');
	$ref = troca($ref,'JULY','V. ');
	$ref = troca($ref,'AUG.','V. ');
	$ref = troca($ref,'NOV.','V. ');
	$ref = troca($ref,'DEC.','V. ');
	$ref = troca($ref,'NOV.','V. ');
	$ref = troca($ref,'AGO.','V. ');
	$ref = troca($ref,'JUNHO','V. ');
	$ref = troca($ref,'OCT.','V. ');
	$ref = troca($ref,'OCTOBER','V. ');
	$ref = troca($ref,'AVRIL','V. ');
	$ref = troca($ref,'SUMMER','V. ');
	$ref = troca($ref,'WINTER','V. ');
	$ref = troca($ref,'VOLUME','V. ');
	$ref = troca($ref,'SEM.','V. ');

	$ref = troca($ref,'(','V. ');
	
	$v_vol = strpos($ref,'V.');
	$v_num = strpos($ref,'N.');
	
///////////////////////////////////////////////// PERIÓDICO
	if (($v_vol > 0) or ($v_num > 0))
		{

		echo 'Phase I<BR>';
		echo '['.$v_vol.'],['.$v_num.']<BR>';
		$sql = "select * from mar_journal where mj_tipo = 'PERIO' ";
		$rltx = db_query($sql);
		while ($xline = db_read($rltx))
			{
			$v_jou = trim(UpperCaseSql($xline['mj_nome']));
			$v_joa = trim(UpperCaseSql($xline['mj_abrev']));
			$pos2 = 0;
			$pos1 = strpos($ref,$v_jou);
			if (strlen($v_joa) > 0) { $pos2 = strpos($ref,$v_joa); }
			if (( $pos1 > 0) or ($pos2 > 0))
				{ $ok = 'ARTIC'.$xline['mj_use']; echo '<BR>'.$ok.' '.$xline['mj_nome'];}
			}
		}
/////////////////////////////// Recupera o conteúdo
	$ref = $xref;
///////////////////////////////////////////////// LIVROS
	if (strlen($ok) == 0)
		{
		echo 'Phase 01 - Busca por livros<BR>';
		$sql = "select * from mar_journal where mj_tipo = 'LIVRO' ";
		$rltx = db_query($sql);
		while ($xline = db_read($rltx))
			{
			$v_jou = trim(UpperCaseSql($xline['mj_nome']));
			$pos2 = 0;
			$pos1 = strpos($ref,$v_jou);
//			echo '<BR>'.$v_jou.' = '.$ref. ' ['.$pos1.' '.$pos2.']';
			if (( $pos1 > 0) or ($pos2 > 0))
				{ $ok = 'LIVRO'.$xline['mj_use']; echo '<BR>'.$ok.' '.$xline['mj_nome'];}
			}
		}
///////////////////////////////////////////////// ANAIS
	if (strlen($ok) == 0)
		{
		echo 'Phase Ia<BR>';
		$sql = "select * from mar_journal where mj_tipo = 'ANAIS' ";
		$rltx = db_query($sql);
		while ($xline = db_read($rltx))
			{
			$v_jou = trim(UpperCaseSql($xline['mj_nome']));
			$pos2 = 0;
			$pos1 = strpos($ref,$v_jou);
			if (( $pos1 > 0) or ($pos2 > 0))
				{ $ok = 'ANAIS'.$xline['mj_use']; echo '<BR>'.$ok.' '.$xline['mj_nome'];}
			}
		}
///////////////////////////////////////////////// RELATÓRIOS
	if (strlen($ok) == 0)
		{
		echo 'Phase Ia<BR>';
		$sql = "select * from mar_journal where (mj_tipo = 'JORNA') or (mj_tipo = 'RELAT')";
		$sql .= " or (mj_tipo = 'NORMA') ";
		$sql .= " or (mj_tipo = 'LEI  ') or (mj_tipo = 'LINK ') or (mj_tipo = 'RELAT') ";
		$rltx = db_query($sql);
		while ($xline = db_read($rltx))
			{
			$v_jou = trim(UpperCaseSql($xline['mj_nome']));
//			echo '<BR>'.$v_jou;
			$pos2 = 0;
			$pos1 = strpos($ref,$v_jou);
			if (( $pos1 > 0) or ($pos2 > 0))
				{ $ok = trim($xline['mj_tipo']).$xline['mj_use']; echo '<BR>'.$ok.' '.$xline['mj_nome'];}
			}
		}
/////////////////////////////////////////////// LINK DE INTERNET
	if (strlen($ok) == 0)
		{
			if (strpos($ref,'DISPONIVEL EM:') > 0) { $ok = 'LINK 0000000'; Echo '>>> LINK DE INTERNET'; }
			if (strpos($ref,'DISPONIEL EM:') > 0) { $ok = 'LINK 0000000'; Echo '>>> LINK DE INTERNET'; }
			if (strpos($ref,'AVAILABLE FROM:') > 0) { $ok = 'LINK 0000000'; Echo '>>> LINK DE INTERNET'; }
			if (strpos($ref,'AVAILABLE AT:') > 0) { $ok = 'LINK 0000000'; Echo '>>> LINK DE INTERNET'; }
			if (strpos($ref,'AVAILABLE IN:') > 0) { $ok = 'LINK 0000000'; Echo '>>> LINK DE INTERNET'; }
			if (strpos($ref,'DISPONIVEL EM') > 0) { $ok = 'LINK 0000000'; Echo '>>> LINK DE INTERNET'; }
		}

///////////////////////////////////////////////// SITE DA INTERNET
		return($ok);
	}
////////////////////////////////////////////////// Proposição e Conjunção
function MAR_cuts($pr,$ar)
	{
	$tit = trim(substr($pr,strpos($pr,$ar)+1+strlen($ar),strlen($pr)));
	$tit = substr($tit,0,strpos($tit,'.'));
	
	$result = $tit;
	return($result);
	}
	

////////////////////////////////////////////////// Busca Título
function MAR_titulo($pr,$ar)
	{
	$aut = $ar[count($ar)-1];
	$tit = trim(substr($pr,strpos($pr,$aut)+1+strlen($aut),strlen($pr)));
	$tit = substr($tit,0,strpos($tit,'.'));
	return($tit);
	}
	
////////////////////////////////////////////////// Proposição e Conjunção
function MAR_preposicao($pr)
	{
		$result = 0;
		// fonte: http://pt.wikipedia.org/wiki/Preposi%C3%A7%C3%A3o
		$prep = array('a','ante','após','apos','com','contra',
				'de','desde','em','e','entre','para','per','perante',
				'por','sem','sob','sobre','trás','tras','da','do');
		if (in_array($pr, $prep))
			{ return(1); } else { return(0); }
	}
////////////////////////////////////////////////// AUTORES
function MAR_autor_grava($au)
	{
	for ($r=0;$r < count($au);$r++)
		{
		$aut = UpperCaseSql($au[$r]);
		$aut = troca($aut,' ','');
		$aut = troca($aut,'.','');
		$aut = troca($aut,',','');
		$aut = troca($aut,';','');
		
		$sql = "select * from mar_autor where a_nome_asc  = '".$aut."' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{ 
				$cod = $line['a_codigo']; 
			} else {
				$isql = "insert into mar_autor ";
				$isql .= "(a_nome,a_nome_asc,a_abrev,a_codigo,a_use,a_status) ";
				$isql .= " values ";
				$isql .= "('".$au[$r]."','".$aut."','".$au[$r]."','','','@')";
				$rlt = db_query($isql);
				
				$isql = "update mar_autor set a_use=lpad(id_a,'10','0') ,a_codigo=lpad(id_a,'10','0') where (length(a_codigo) < 10)";
				$rlt = db_query($isql);
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$cod = $line['a_codigo'];
			}
		$au[$r] = $cod;
		}
	return($au);
	}
	
function MAR_autor($ss)
	{
	$au = array();
	$sx = '';
	$ss = trim($ss);
	$ss = troca($ss,'et al','.');
	// analise
	for ($r=0;$r < strlen($ss);$r++)
		{
		$c = substr($ss,$r,1);
		$ok = 1;
		if (($c == '.') and (substr($ss,$r+2,1) == '.'))
			{
			$ok = 0;
			}
		///////////////////////////////////////////////////////// AUTORES
		if ((($c == '.') or ($c == ';')) and ($ok == 1))
			{
			///// Necessidade de ponto no final
			if ($c == '.') 
				{ 
				$ant = ord(substr($sx,strlen($sx)-1,1));
				if (($ant >= 65) and ($ant <= 90))
					{ $sx .= '.'; $c = ''; }
				}
			$sx = trim(troca($sx,';',''));
			if (strlen($sx) > 0) 
			{ 
				if (strlen($sx) > 3)
					{ array_push($au,$sx); } else
					{ $au[count($au)-1] .= $sx; }
			}
			$sx = '';
			}
		////////////////////////////////////////////////////////// FINALIZAR
		/////////////////////////////////////////////// Minuscula na Segunda
		if ($c == ' ')
			{
				$sp = substr($ss,$r+1,50);
				$sp = substr($sp,0,strpos($sp,' '));
				$sp = troca($sp,'.',''); // tirar ponto
				$sp = troca($sp,';',''); // tirar ponto e virgula
				$pre = MAR_preposicao($sp).',';
				if (((ord($sp) < 65) or (ord($sp) > 90)) and ($pre == 0))
					{
					return($au);
					}
			}
		$sx .= $c;
		}
	return($s);
	}
?>