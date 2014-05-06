<?php
/*
 * Versão 0.14.02
 */
 
class cited
	{
	var $online=0;

	var $tabela = "mar_works";
	
	function le($id)
		{
			$sql = "select * from ".$this->tabela." where id_m = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
				}
			return(1);
		}
	
	function journal_insert($nome,$tipo)
		{
			$sql = "select * from mar_journal where mj_nome = '".$nome."'";
			$rlt = db_query($sql);
			if (!($line = db_read($rlt)))
				{
					$nome_asc = uppercasesql($nome);
					$sql = "insert into mar_journal 
							(mj_codigo, mj_nome, mj_abrev, mj_nome_asc,
							mj_issn, mj_ativo, mj_cidade,
							mj_tipo, mj_use, m_use_base,
							m_processar 
							) values (
							'','$nome','','$nome_asc',
							'',1,'',
							'$tipo','','',
							'N')
					";
					$rlt = db_query($sql);
					$this->mar_updatex();
					echo '<BR>Saved';
				} else {
					return(0);
				}
		}
	function mar_updatex()
			{
				global $base;
				$c = 'mj';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update mar_journal 
						set $c2 = lpad($c1,$c3,0),
						mj_use = lpad($c1,$c3,0)
						where $c2='' ";
				$rlt = db_query($sql);
				return(1);
			}
				
	function journal_form_insert($journal='')
		{
			$sx = '<form method="post" action="'.page().'">
					<input type="text" name="dd10" value=""  size="100">
					<input type="submit" name="dd11" value="cadastrar revista >>>">
					</form>
			';
			
			if (strlen($journal) > 0)
				{
					echo '<BR>-->'.$journal;
					$this->journal_insert($journal,'ARTIC');
				}
			return($sx);
		}
	function book_form_insert($journal='')
		{
			$sx = '<form method="post" action="'.page().'">
					<input type="text" name="dd12" value=""  size="100">
					<input type="submit" name="dd13" value="cadastrar book >>>">
					</form>
			';
			
			if (strlen($journal) > 0)
				{
					echo '<BR>-->'.$journal;
					$this->journal_insert($journal,'LIVRO');
				}
			return($sx);
		}		
	
	function MAR_tipo($ref)
	{

	/* Padronizacao automatica */
	$tp = '???';
	$ref = UpperCaseSql($ref);
	$ref = troca($ref,' ,',',');
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
	
	echo '<BR><table width="99%" bgcolor="#FFFFFF" 
				border="1" align="center"
				cellpadding=4 cellspacing=0 >
			<TR><TD><TT>'.$ref.'</TD></TR>
		</table>';
	
	$ok = '';
	if (strpos($ref,'TESE (DOUTORADO') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESE DE DOUTORADO') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESE(') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESE (') > 0)
		{ $ok = 'TESE 0000000'; }
	if (strpos($ref,'TESIS (') > 0)
		{ $ok = 'TESE 0000000'; }
		
	//// Dissertação
	if (strpos($ref,'(DISSERTACAO DE MESTRADO)') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'DISSERTACAO (MESTRADO') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'DISSERTACAO (') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'DISSERTATION (') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'DISSERTATION (') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'. MESTRADO (') > 0)
		{ $ok = 'DISSE0000000'; }
	if (strpos($ref,'. DISSERTATION ') > 0)
		{ $ok = 'DISSE0000000'; }

	if (strpos($ref,'#') > 0)
		{ $ok = 'NC0000000'; }
		
	if (strpos($ref,'TRABALHO DE CONCLUSÃO DE CURSO') > 0)
		{ $ok = 'TCC  0000000'; }	
	if (strpos($ref,'(MONOGRAFIA)') > 0)
		{ $ok = 'TCC  0000000'; }	
	if (strpos($ref,'MONOGRAFIA (') > 0)
		{ $ok = 'TCC  0000000'; }	
				

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
		echo 'Volume ('.$v_vol.'), Numero ('.$v_num.')<BR>';
		
		$sql = "select * from mar_journal where 
			(mj_tipo = 'PERIO' or mj_tipo = 'ARTIC')";
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

	function cited_IIv()
		{
			global $dd;
			if (strlen($dd[1]) > 0)
				{
					$sql = "update mar_works set m_status = 'C', ";
					$sql .= "m_tipo = '".$dd[1]."' ,";
					$sql .= "m_journal = '0000000' ";
					$sql .= " where id_m = ".$dd[0];
					$rltx = db_query($sql);	
					redirecina(page());					
					return(1);
				}
			
			$sql = "select * from mar_works 
						where m_status = 'V' 
						order by m_ref limit 1 ";
			$rlt = db_query($sql);
			$proc = 0;
			while ($line = db_read($rlt))
				{
					$refc = $line['id_m'];
					$mano = trim($line['m_ano']);
					$mref = $line['m_ref'];
					$pos = strpos($mref,'  ');
					$mref = troca($mref,' :',':');
					$mref = troca($mref,' ,',',');
					$mref = troca($mref,'&','and');
					while ($pos > 0)
						{
							$mref = troca($mref,'  ',' ');
							$pos = strpos($mref,'  ');
						}
					
					$sql = "update mar_works set m_ref = '".$mref."' where id_m = ".$line['id_m'];
					$rlt = db_query($sql);
					
					$link = '<A HREF="#" onclick="newxy2(\'article_ref_edit.php?dd0='.$line['id_m'].'\',800,200);">';
					$link = '['.$link.'editar</A>]';
					
					echo '<BR><PRE>'.$mref.$link.'</PRE>';
					
					$sql = "select * from mar_tipo 
							order by mt_descricao
					";
					$rlt = db_query($sql);
					
					echo '<BR><BR>';
					echo '<B>Tipo de publicação</B>:';
					echo '<UL>';
					while ($line = db_read($rlt))
						{
							$link = '<A HREF="'.page().'?dd0='.$refc.'&dd1='.$line['mt_codigo'].'" class="link" >';
							echo '<LI>';
							echo $link.$line['mt_descricao'].'</A>';
							echo '</LI>';
						}
					echo '</UL>';
					echo '<BR><BR>';
					return($this->cited_II_recover($refc));
				}	
		}
	
	function cited_II_recover($id=0)
		{
			$wh = " m_status = 'A' ";
			if ($id > 0) { $wh = ' id_m = '.$id; }
			$sql = "select * from mar_works 
						where $wh
						order by m_ref limit 10 ";
			$rlt = db_query($sql);
			$proc = 0;
			while ($line = db_read($rlt))
				{
					if ($id==0) { $proc++; }
					$refc = $line['id_m'];
					$mano = trim($line['m_ano']);
					$mref = $line['m_ref'];
					
					$tipo = $this->MAR_tipo($mref);
					echo '===>'.$tipo;
					
					if (strlen($tipo) > 0)
						{
							if ($id > 00) { $proc++; }
							$sql = "update mar_works set m_status = 'C', ";
							$sql .= "m_tipo = '".substr($tipo,0,5)."' ,";
							$sql .= "m_journal = '".substr($tipo,5,7)."' ";
							$sql .= " where id_m = ".$line['id_m'];
							$rltx = db_query($sql);							
						} else {
							$this->ref_save_year($refc,$mano,'V');
						}
					echo '<BR><BR><BR>';
				}
				
			return($proc);
			
		}
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_m','',False,True));
			array_push($cp,array('$S12','m_work','',True,True));
			array_push($cp,array('$T70:5','m_ref','',True,True));
			array_push($cp,array('$HV','m_status','@',True,True));
			array_push($cp,array('$S4','m_ano','',True,True));
			return($cp);
		}
	
	function resumo()
		{
			$sql = "select count(*) as total, m_status from 
					".$this->tabela." 
					group by m_status 
					order by m_status ";
			$rlt = db_query($sql);
			
			
			$st = array('@'=>'Nova',
						'A'=>'Ano Identificado',
						'Z'=>'Problema',
						'X'=>'Cancelado',
						'Y'=>'Mais de um ano',
						'V'=>'Não identificado tipo',
						'C'=>'Identificado tipo de publicação'
						);
			$sl = array('@'=>'cited_process_01.php',
						'Z'=>'cited_process_01z.php',
						'Y'=>'cited_process_01y.php',
						'V'=>'cited_process_02v.php'
						);						
			
			$sx = '<table width="99%" align="center" 
					class="tabela00" style="border: 1px solid #505050;">';
			$sx .= '<TR>';
			while ($line = db_read($rlt))
				{
						$link = $sl[trim($line['m_status'])];
						if (strlen($link) > 0)
							{
								$link = '<A HREF="'.$link.'">';
								$link_a = '</A>';
							} else {
								$link = ''; $link_a = '';
							}
						$sx .= '<TD width="8%" align="center">';
						$sx .= $st[trim($line['m_status'])];
						$sx .= '('.$line['m_status'].')';
						$sx .= '<font style="font-size: 25px;">';
						$sx .= '<BR>'.$link.$line['total'].$link_a;
						$sx .= '</font>';
				}
			$sx .= '</table>';
			return($sx);
		}
	function cited_problens()
		{
			$sql = "select * from ".$this->tabela."  
					where m_status = 'Z' and m_ref <> ''
					$wh
					order by m_ref limit 20 
					";
			$rlt = db_query($sql);
			$proc = 0;
			while ($line = db_read($rlt))
			{
				$link = '<A HREF="article_view.php?dd0='.$line['m_work'].'" target="_new">';
				$link .= '<font color="blue">';
				$proc++;
				$refc = $line['id_m'];
				$mano = trim($line['m_ano']);
				$mref = $line['m_ref'];
				
				echo '<font class="lt2"><BR>'.$mref;
				echo ' ['.$link.'artigo</font></A>]';
				echo '<BR>';
			}
			return($proc);	
		}

	function cited_Iy_recover_year($id=0)
		{
			global $dd;
			
			$wh = '';
			
			if ($id > 0) 
				{
					if ($dd[1]=='ERRO')
						{
							$this->ref_save_year($id,'0','Z');
						} else {
							if ($dd[1]=='LINK')
								{
									$this->ref_save_year($id,'','U');
									$this->ref_save_type($id,'LINK');
									echo '<font color="blue"> (Internet Link)</font>';
								} else {
									$this->ref_save_year($id,$dd[1],'A');
								}
						}
					return(1);
				}
			$sql = "select * from ".$this->tabela."  
					where m_status = 'Y' 
					$wh
					order by m_ref limit 1 
					";
			$rlt = db_query($sql);
			$proc = 0;
			while ($line = db_read($rlt))
			{
				$refc = $line['id_m'];
				$mano = trim($line['m_ano']);
				$mref = $line['m_ref'];
				echo '<font class="lt2"><BR>'.$mref;
				$anos = $this->busca_ano($mref);
				if (count($anos)<=1)
					{
						$this->ref_save_year($refc,$anos[0],'@');
						echo '<font color="blue"> ('.$anos[0].' saved)</font>';
						$proc++;
					}

				if (count($anos) > 1)
					{
						//$this->ref_save_year($refc,'','Y');
						echo '<ul>';
						array_push($anos,'ERRO');
						array_push($anos,'LINK');
						for ($r=0;$r < count($anos);$r++)
							{
								$link = '<A HREF="'.page().'?dd0='.$refc.'&dd1='.$anos[$r].'">';
								echo '<LI>';
								echo $link;
								echo trim($anos[$r]);
								echo '</A>';
								echo '</LI>';
							}
					}									
			}
			return($proc);
		}
	function cited_alterar_status($de,$para)
		{
			$sql = "update ".$this->tabela." set m_status = '".$para."'
					where m_status = '".$de."' ";
			$rlt = db_query($sql);
		}
	function cited_I_recover_year($id=0)
		{
			$sql = "select * from ".$this->tabela."  
					where m_status = '@' 
					order by m_ref limit 20 
					";
			$rlt = db_query($sql);
			$proc = 0;
			while ($line = db_read($rlt))
			{
				$refc = $line['id_m'];
				$mano = trim($line['m_ano']);
				if ($mano > 0)
					{
						$this->ref_save_year($refc,$mano,'A');
						echo '<font color="blue"> ('.$anos[0].' saved)</font>';
						$proc++;	
					} else {
						$mref = $line['m_ref'];
						echo '<font class="lt2"><BR>'.$mref;
						$anos = $this->busca_ano($mref);
						if (count($anos)==1)
							{
								$this->ref_save_year($refc,$anos[0],'A');
								echo '<font color="blue"> ('.$anos[0].' saved)</font>';
								$proc++;
							}
						if (count($anos)==0)
							{
								if ($this->online==1)
									{
										$this->ref_save_year($refc,'','U');
										$this->ref_save_type($refc,'LINK');
										echo '<font color="blue"> (Internet Link)</font>';
										$proc++;
									} else {
										$this->ref_save_year($refc,'','Z');
										echo '<font color="red"> (without year)</font>';
										$proc++;								
									}
							}
						if (count($anos) > 1)
							{
								$this->ref_save_year($refc,'','Y');
								echo '<font color="green"> (more one year)</font>';
								$proc++;
							}									
						echo '</font>';
					}
			}
			return($proc);
		}
	function ref_save_year($refc,$year,$status)
		{
			$sql = "update mar_works set m_status = '".$status."', 
						m_ano = ".round(sonumero($year))." 
						where id_m = ".sonumero($refc);
			$rlt = db_query($sql);
			return(1);
		}
	function ref_save_type($refc,$type)
		{
			$sql = "update mar_works set m_tipo = '".$type."' 
						where id_m = ".sonumero($refc);
			$rlt = db_query($sql);
			return(1);
		}		
	function busca_ano($ref)
		{
		$anos = array();
		$refs = array();
		
		$this->online = 0;
		$opx = array('Available from','Acesso em','Acesso em:',
					'Acceso en:', 'Acceso:', 'Disponible en',
					'Disponível em', 'Acessado em','Consultado en',
					'Available:', 'Available from:','Acesso:',
					'Acessado em');
		for ($rc=0;$rc < count($opx);$rc++)
			{
			if (strpos($ref,$opx[$rc]) > 0)
				{
				$ref = substr($ref,0,strpos($ref,$opx[$rc]));
				$this->online = 1;
				}
			}
			
		/* Busca Anos */
		for ($ra=date("Y");$ra > 1850;$ra--)
			{
			$max = 0;
			$ano = $ra;
			for ($rt = 0;$rt < strlen($ref);$rt++)
				{
				if (substr($ref,$rt,4) == $ano) { $max = $rt; }
				}
			if ($max > 0)
				{
				array_push($anos,$ano);
				array_push($refs,$max);
				}
			}	
		return($anos);				
		}
		
	function article_without_ref($journal=0,$issue=0,$year=0)
		{
			// 
			if ($year == 0) { $year = (date("Y")-1); }
			$sql = 'SELECT ar_codigo, total, jnl_nome FROM brapci_article
					inner join brapci_edition on ar_edition = ed_codigo
					inner join brapci_journal on ar_journal_id = jnl_codigo	 
					left join (
						select m_work, count(*) as total from mar_works
						group by m_work 
					) as citacoes
					on ar_codigo = m_work
					where ed_ano = '.$year.'
			';
			
			$rlt = db_query($sql);
			
			$tot = 0;
			$wtot = 0;
			$xjournal = '';
			$sx = '<table class="tabela01" border=1>';
			$sx .= '<TR><TD>';
			$col = 99;
			while ($line = db_read($rlt))
				{
					$journal = trim($line['jnl_nome']);
					if ($journal != $xjournal)
						{
							$sx .= '<TR><TD colspan=10>';
							$sx .= '<HR><font class="lt3">'.$journal.'</font><HR>';
							$xjournal = $journal;
							$col=99;
						}
					if ($col > 6) 
						{
							$col = 0;
							$sx .= '<TR>';
						}
					$tot++;
					$ref = round($line['total']);
					if ($ref==0)
						{
							$link = '<A HREF="article_view.php?dd0='.trim($line['ar_codigo']).'" target="editar">';
							$wtot++;
							$sx .= '<TD>';
							$sx .= $link;
							$sx .= trim($line['ar_codigo']);
							$sx .= '</A>';
							$sx .= '&nbsp;';
						}
					$col++;
				}
			$sx .= '<TR><TD colspan=10>';
			$sx .= 'total de '.$tot.' trabalhos, destes '.$wtot.' sem referências.';
			$sx .='</table>';
			//echo $sx;
			return($sx);			
		}
	function save_cited($s)
	{
		global $dd;
		$refs=array();
		$article = strzero($dd[0],10);
		/* Excluir anteriores */
		$sql = "delete from mar_works where m_work = '".$article."' ";
		if (strlen($article) > 0) { $rlt = db_query($sql); }
		$s = troca($s,chr(10),chr(13));
		$ln = splitx(chr(13),$s);
		$s .= ' '.chr(13);
		while (strpos($s,chr(13)) > 0)
			{
			$sa = trim(substr($s,0,strpos($s,chr(13))));
			$s = ' '.substr($s,strpos($s,chr(13))+1,strlen($s));
			if (strlen($sa) > 0)
				{
				$sa = troca($sa,"'",'´');
				if (substr($sa,0,2) == '__')
					{
						if (strlen($__autor) == 0) 
							{
							echo 'ops, erro';
							exit;
							}
						$sa = $__autor.substr($sa,7,strlen($sa));
					} else {
						$__autor = substr($sa,0,strpos($sa,'.')+1);
					}
					$sql = "INSERT INTO `mar_works` ( ";
					$sql .= "`m_status` , `m_ref` , `m_title` , ";
					$sql .= "`m_codigo` , `m_journal` , ";
					$sql .= " m_tipo, m_work";
					$sql .= ") VALUES (";
					$sql .= " '@','".$sa."','".$titulo."',";
					$sql .= "'','',";
					$sql .= "'','".$article."'";
					$sql .= ")";
					$rlt = db_query($sql);
				}
			}
			return(1);		
	}
		
	function import()
		{
		global $dd;
		$refs=array();
		$article = $dd[0];
		$sql = "delete from mar_works where m_work = '".$article."' ";
			if (strlen($article) > 0) { $rlt = db_query($sql); }
			$s .= ' '.chr(13);
		while (strpos($s,chr(13)) > 0)
		{
			$sa = trim(substr($s,0,strpos($s,chr(13))));
			$s = ' '.substr($s,strpos($s,chr(13))+1,strlen($s));
			if (strlen($sa) > 0)
			{
			$sa = troca($sa,"'",'´');
				$sx .= '<TR><TD><TT>'.$sa;
			if (substr($sa,0,2) == '__')
				{
					if (strlen($__autor) == 0) 
						{
						echo 'ops, erro';
						exit;
						}
					$sa = $__autor.substr($sa,7,strlen($sa));
				} else {
					$__autor = substr($sa,0,strpos($sa,'.')+1);
				}
			if ($dd[11] == '1')
				{
				$sql = "INSERT INTO `mar_works` ( ";
				$sql .= "`m_status` , `m_ref` , `m_title` , ";
				$sql .= "`m_codigo` , `m_journal` , ";
				$sql .= " m_tipo, m_work";
				$sql .= ") VALUES (";
				$sql .= " '@','".$sa."','".$titulo."',";
				$sql .= "'','',";
				$sql .= "'','".$article."'";
				$sql .= ")";
				//echo '<HR>'.$sql.'<HR>';
				$rlt = db_query($sql);
				}
			}
			return($sx);				
		}
		}
	function cited_form()
		{
			global $dd,$acao;
			$sx = '
				<center>
					<textarea cols="70" rows="18" name="dd10" id="title1" style="width: 100%;">'.$dd[10].'</textarea>
					
					<select id="title2" name="ddx">
					<option value="">::Confirmar::</option>
					<option value="1">Salvar</option>
					</select>
					
					<input type="submit" value="e n v i a r" class="lt2" id="botao_cited" >
				</form>
				</center>
				';
				return($sx);			
		}			
		
	}

function MAR_preparar($x)
	{
	$x = troca($x,chr(10),chr(13));
	$x = chr(13).chr(10).$x;
	for ($r = 1;$r < 150;$r++)
		{ $x = troca($x,chr(10).$r.'. ',''); }
	for ($r = 1;$r < 150;$r++)
		{ $x = troca($x,chr(10).'['.$r.'] ',''); }
	for ($r = 1;$r < 150;$r++)
		{ $x = troca($x,chr(10).$r.' ',''); }		
	$x = troca($x,'[ Links ]','');
	$x = troca($x,chr(92),'');
	$x = troca($x,chr(13),'§');
	$x = troca($x,chr(10),'');
	$x = troca($x,'§§','§');
	$x = troca($x,'§§','§');

	$x = troca($x,'Mc','MC');
	$x = troca($x,'Ä','A');
	$x = troca($x,'Á','A');
	$x = troca($x,'Â','A');
	$x = troca($x,'Ü','U');
	$x = troca($x,'É','E');
	$x = troca($x,'Ê','E');
	$x = troca($x,'È','E');
	$x = troca($x,'Ë','E');
	$x = troca($x,'Ê','E');
	$x = troca($x,'Í','I');
	$x = troca($x,'Î','I');
	$x = troca($x,'Ó','O');
	$x = troca($x,'Ö','O');
	$x = troca($x,'Ô','O');
	$x = troca($x,'Ü','U');
	$x = troca($x,'Û','U');
	$x = troca($x,'Š','S');
	
	$x = troca($x,'______,','AAAAAA');
	$x = troca($x,'______;','AAAAAA');
	$x = troca($x,'______','AAAAAA');
	$x = troca($x,'______','AAAAAA');
	$x = troca($x,'_____','AAAAAA');
	

	$x = ' '.$x;
	while (strpos($x,'§') > 0) 
		{
		$pos = strpos($x,'§');
		$xs = substr($x,$pos,3);
		$x1 = ord(substr($xs,1,1));
		$x2 = ord(substr($xs,2,1));
		if (($x1 >= 65) and ($x1 <= 90) and ($x2 >= 65) and ($x2 <= 90))
			{
				$x = substr($x,0,$pos).chr(13).chr(13).substr($x,$pos+1,strlen($x));
			} else {
				$x = substr($x,0,$pos).' '.substr($x,$pos+1,strlen($x));
			}
		}
	$x = troca($x,'AAAAAA','______');
	return(trim($x));
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
				'por','sem','sob','sobre','trás','tras','da');
		if (in_array($pr, $prep))
			{ return(1); } else { return(0); }
	}
////////////////////////////////////////////////// AUTORES
function MAR_autor_grava($au)
	{
	echo '<HR>';
	print_r($au);
	echo '<HR>';	
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
				$isql .= "(a_nome,a_nome_asc,a_abrev,a_codigo,a_use) ";
				$isql .= " values ";
				$isql .= "('".$au[$r]."','".$aut."','".$au[$r]."','','')";
				$rlt = db_query($isql);
				
				$isql = "update mar_autor set a_use=lpad(id_a,'10','0') ,a_codigo=lpad(id_a,'10','0') where (length(a_codigo) < 10)";
				$rlt = db_query($isql);
			}
		}
	}
function MAR_autor($ss)
	{
	$au = array();
	$sx = '';
	$ss = trim($ss);
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
			if (strlen($sx) > 0) { array_push($au,$sx); }
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
