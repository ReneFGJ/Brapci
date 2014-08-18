<?
class cited
	{
	var $article;
	
	var $cited;
	var $edited=1;
	
	var $tabela = "mar_works";
	var $tabela_journal = "mar_journal";
	var $tabela_journal_cited = "cited_journals";
	
	function row_journals()
		{
			global $cdf,$cdm,$masc;
			$cdf = array('id_cj','cj_codigo','cj_issn','cj_nome','cj_abrev');
			$cdm = array('cod',msg('codigo'),msg('issn'),msg('journal'),msg('abrev'));
			$masc = array('','','','SN','','','');
			return(1);				
		}
	
	function cp_mar()
		{
			$cp = array();
			array_push($cp,array('$H8','id_mj','',False,True));
			array_push($cp,array('$H8','mj_codigo','',False,True));
			array_push($cp,array('$S100','mj_nome','Nome',False,True));
			array_push($cp,array('$H8','mj_nome_asc',UpperCaseSql($dd[2]),False,True));
			array_push($cp,array('$S40','mj_abrev','Abreviatura',False,True));
			array_push($cp,array('$S10','mj_issn','ISSN',False,True));
			array_push($cp,array('$O 1:SIM&0:NÃO','mj_ativo','Ativo',False,True));
			
			array_push($cp,array('$H8','mj_cidade','',False,True));
			array_push($cp,array('$Q mt_descricao:mt_codigo:select * from mar_tipo where mt_ativo=1 order by mt_descricao','mj_tipo','Tipo',False,True));
			array_push($cp,array('$S7','mj_use','Use',False,True));
			array_push($cp,array('$H8','m_use_base','',False,True));
			array_push($cp,array('$O N:NÃO&S:SIM.','m_processar','Processar',False,True));
			return($cp);		
		}
	function rows_mar()
		{
				global $cdf,$cdm,$masc;
				$cdf = array('id_mj','mj_nome','mj_abrev','mj_issn','mj_codigo','mj_use');
				$cdm = array('cod',msg('nome'),msg('abbrev'),msg('ISSN'),msg('codigo'),msg('use'));
				$masc = array('','','','','','','');
				return(1);			
		}
	function MAR_save($s)
		{
		global $dd;
		$refs=array();
		$article = $this->article;
		$norma = $dd[4];
		if (strlen($norma)==0) { $norma = 'ABNT'; }
		$this->structure();
		
		$sql = "delete from mar_works where m_work = '".$article."' ";
		$rlt = db_query($sql);

		$s .= ' '.chr(13);
		while (strpos($s,chr(13)) > 0)
			{
			$sa = trim(substr($s,0,strpos($s,chr(13))));
			$s = ' '.substr($s,strpos($s,chr(13))+1,strlen($s));

			if (strlen($sa) > 0)
				{
				$sa = troca($sa,"'",'´');

				echo '<TR><TD><TT>'.$sa;

				if ($dd[11] == '1')
					{
					$sql = "INSERT INTO mar_works ( ";
					$sql .= "m_status , m_ref , m_title , ";
					$sql .= "m_author , ";
					$sql .= "m_codigo , m_journal , ";
					$sql .= " m_tipo, m_work, m_norma";
					$sql .= ") VALUES (";
					$sql .= " '@','".$sa."','".$titulo."',";
					$sql .= " '', ";
					$sql .= " '', '', ";
					$sql .= " '', '".$article."','".$norma."'";
					$sql .= ")";
					echo $sql;
					echo '<HR>';
					$rlt = db_query($sql);
					}
				}
			}	
		}

	/* Lista referencias dos arquivos */
	function MAR_lista()
		{
			//$sql = "update ".$this->tabela." set m_norma = 'APA' ";
			//$rlt = db_query($sql);
			
			$xcor = array('@'=>'#808080','A'=>'#404040','B'=>'#202020','C'=>'#000000','Z'=>'#FF0000','R'=>'#0000FF','F'=>'#00FF00');

			$sql = "select * from ".$this->tabela." ";
			$sql .= " left join mar_journal on m_journal = mj_codigo ";
			$sql .= " where m_work = '".$this->artigo."' ";
			$sql .= " order by id_m " ;
			
			$rlt = db_query($sql);
			$cited = array();
			while ($line = db_read($rlt))
				{
				$link2 = '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_editar.php?dd0='.$line['id_m'].chr(39).',680,250);">[EDITAR]</A>';
					
				$sx .= '<font color="'.$xcor[$line['m_status']].'">';
				$sx .= $line['m_ref'];
				$sx .= '['.$line['m_status'].','.$line['m_tipo'].']';
				if ($this->edited == 1)
					{ $sx .= $link2; }
				$sx .= '<BR><BR>';
				array_push($cited,$line);
				}
			$this->cited = $cited;
			return($sx);
		}
	function MAR_phase_i()
		{
			global $dd;
			
			if (strlen($dd[2]) > 0)
				{
					$status = $dd[2];
					$ano = $dd[1];
					$id = $dd[0];
					$sql = "update mar_works set m_status = '$status', m_ano = $ano where id_m = ".$id;
					$rlt = db_query($sql);
					redirecina('mar_phase_i.php');
					//$rlt = db_query($sql);
				}
			$anos = array();
			$refs = array();
			$loop = 0;
			$online = 0;
			$sql = "select * from ".$this->tabela." where m_status = '@' order by m_ref limit 1  ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					/* Mostra dados da referencia */
					$sx .= $this->MAR_dados($line);
					/* Recupeda ano */
					$m_ano = trim($line['m_ano']);
					$lx = ' '.trim($line['m_ref']);
					
					/* Identifica se é fonte online */
					$opx = $this->MAR_online();
					for ($rc=0;$rc < count($opx);$rc++)
					{
						if (strpos($lx,$opx[$rc]) > 0)
						{
						$lx = substr($lx,0,strpos($lx,$opx[$rc]));
						$online = 1;
						}
					}
					
					/* Recupera os anos */
					for ($ra=date("Y");$ra >= 1850;$ra--)
						{
						$max = 0;
						$ano = $ra;
						for ($rt = 0;$rt < strlen($lx);$rt++)
							{
							if (substr($lx,$rt,4) == $ano) { $max = $rt; }
							}
						if ($max > 0)
							{
							array_push($anos,$ano);
							array_push($refs,$max);
							}
						}
					
					$lx = troca($lx,'<','&lt;');
					$lx = troca($lx,'>','&gt;');
					$sx .= '<BR><B>'.$lx.'</B>';
					
					/**
					 * Analisa anos recuperados 
					 */
										
					/* O Ano foi registrado anteriormente */
					if (strlen($m_ano) == 4) { $anos = array($m_ano); $msg = 'Utilizado anterior'; }
					
					/* Sem ano registrado e Online */
					if ((count($anos) == 0) and ($online == 1))
					{
						$lx = ' '.trim($line['m_ref']);
						for ($ra=date("Y");$ra > 1990;$ra--)
							{
								$max = 0;
								$ano = $ra;
								for ($rt = 0;$rt < strlen($lx);$rt++)
									{ if (substr($lx,$rt,4) == $ano) { $max = $rt; } } 
							if ($max > 0)
								{	
								array_push($anos,$ano);
								array_push($refs,$max);
								}
							}						
					}	
					/* Salva se foi localizando apenas um ano */
					
					if (count($anos) == 1)
					{
						$sql = "update mar_works set m_status = 'A', m_ano = ".round($anos[0])." where id_m = ".$line['id_m'];
						$rlt = db_query($sql);
						$loop = 1;
						$msg = 'Ano salvo com sucesso !'.chr(13);
						$msg .= '<META HTTP-EQUIV=Refresh CONTENT="1; URL='.page().'">';
					} 
					
					/* Problemas nos anos, mais de um ou nenhum */
					if (count($anos) > 1)
					{
						for ($ra = 0;$ra < count($anos);$ra++)
						{
							$sx .= '<a href="'.page().'?dd0='.$line['id_m'].'&dd1='.$anos[$ra].'&dd2=A">'.$anos[$ra].'</a>';
							$sx .= ' ';
						}
						$sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1=0&dd2=Z">(Erro na referência)</a>';
						$work = $line['m_work'];
						$link = '<A href="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd1='.$work.'" target="new">';
						$sx .= '<BR><BR><BR>';
						$sx .= $link.'Editar lista de referências</a>';
						$msg = 'Multiplos anos, selecione o ano da referência';
					} else {
						/* Sem ano localizado */
						if (count($anos) == 0)
							{
							$sx .= '<a href="mar_marcacao_i.php?dd0='.$line['id_m'].'&dd1=0&dd2=Z">(Erro na referência)</a>';
							$sx .= ' ';
							$sx .= '<BR><font color=green>'.$msg.'</font>';

							$sql = "update mar_works set m_status = 'Z', m_ano = 0 where id_m = ".$line['id_m'];
							$rlt = db_query($sql);
							$loop = 1;
							$msg = 'Erro na localização do ano da referência';
							}
					}						
				} else {
					$sx .= 'FIM';
				}
				$sx .= $msg;
			return($sx);					 				
		}

	function MAR_phase_ii()
		{
			global $dd;
			
			if (strlen($dd[2]) > 0)
				{
					$status = $dd[2];
					$ano = $dd[1];
					$tipo = $dd[3];
					$id = $dd[0];
					$sql = "update mar_works set m_status = '$status', m_tipo = '$tipo' where id_m = ".$id;
					$rlt = db_query($sql);
					
					redirecina('mar_phase_ii.php');
					//$rlt = db_query($sql);
				}			
					
			$anos = array();
			$refs = array();
			$loop = 0;
			$online = 0;
			$this->structure();
			$sql = "select * from ".$this->tabela." where m_status = 'A' order by m_ref limit 1  ";
			$rlt = db_query($sql);
			if ($xline = db_read($rlt))
				{
					/* Mostra dados da referencia */
					$sx .= $this->MAR_dados($xline);
					/* Recupeda ano */
					$m_ano = trim($xline['m_ano']);
					$lx = ' '.trim($xline['m_ref']);
					
					$tipo = $this->MAR_tipo($lx);
					echo '<HR>===>'.$tipo;
					
					if (strlen($tipo) > 0)
						{
							$sql = "update ".$this->tabela." set m_status = 'B', ";
							$sql .= "m_tipo = '".substr($tipo,0,5)."' ,";
							$sql .= "m_journal = '".substr($tipo,5,7)."' ";
							$sql .= " where id_m = ".$xline['id_m'];
							$rlt = db_query($sql);
							
							$msg .= '<META HTTP-EQUIV=Refresh CONTENT="1; URL='.page().'">';
							echo $msg;
						} else {
							$sql = "update ".$this->tabela." set m_status = 'R', ";
							$sql .= "m_tipo = '--NC-' ,";
							$sql .= "m_journal = '0000000' ";
							$sql .= " where id_m = ".$xline['id_m'];
							
							if ($dd[2]=='SIM')
								{
									$rlt = db_query($sql);
									redirecina(page());
								} else {
									$sx .= '<a href="'.page().'?dd0='.$xline['id_m'].'&dd3=--NC-&dd2=B">--NC--</a>';
									$sx .= '&nbsp;|&nbsp;';
									$sx .= '<a href="'.page().'?dd0='.$xline['id_m'].'&dd3=LINK&dd2=B">LINK</a>';
									$sx .= '&nbsp;|&nbsp;';
									$sx .= '<a href="'.page().'?dd0='.$xline['id_m'].'&dd3=RELAT&dd2=B">RELATÓRIO</a>';								}
									$sx .= '&nbsp;|&nbsp;';
									$sx .= '<a href="'.page().'?dd0='.$xline['id_m'].'&dd3=&dd2=Z">ERRO</a>';										
						}
				}
				
			return($sx);
		}		
	function MAR_dados($line)
		{
			$work = $line['m_work'];
			$link = '<A href="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd1='.$work.'" target="new">';
			$link2 = '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_editar.php?dd0='.$line['id_m'].chr(39).',680,250);">[EDITAR]</A>';

		$sx = '
			<BR><BR>
			<table width="90%" border="1" cellpadding="6" cellspacing="0" align="center">
			<TR valign="top">
			<TD rowspan="11" width="225"><img src="img/logo_cited.png" width="215" height="138" alt="" border="0">
			<center>';

			$sx .= '<BR>';
			$sx .= $link.'Editar lista de referências</a>';
				
			$sx .= '
			<br><br><br>
			<?=$complement;?>
			</center></TD>
			<TD colspan="2" height="80"><TT>';
			$sx .- '<font size=4><B>';
			$sx .= $line['m_ref'];
			$sx .= '</font>';
			$sx .= '<BR>'.$link2;
			$sx .= '</TD></TR>
			<TR><TD width="5%" align="right"><TT>Ano:<TD><TT><B>';
			$sx .= $line['m_ano'];
			$sx .= '</TD>';
			$sx .= '<TR valign="top"><TD align="right"><TT>Tipo:</TD>
				 			<TD><TT>&nbsp;<B>';
			$sx .= trim($line['m_tipo']);
			$sx .= '</TD>';

			/* DOI */
			$sx .= '<TR valign="top"><TD align="right"><TT>DOI:</TD>
				 			<TD><TT>&nbsp;<B>';
			$sx .= trim($line['m_doi']);
			$sx .= '</TD>';

			$sx .= '<TR valign="top"><TD align="right"><TT>Editor(a):</TD>
				 		<TD><TT>&nbsp;<B>';
			$sx .= $line['m_journal'];
			$sx .= '</TD></table>';
			
			return($sx);			
		}

	function MAR_preparar($x)
		{
		global $dd;
		$this->article = strzero($dd[0],7);
		$x = troca($x,chr(13),'¢');
		$x = troca($x,chr(10),'');
		$x = troca($x,'¢¢','¢');
		$x = troca($x,'¢¢','¢');
		$x = ' '.$x;
		/** ABNT */
		if ($dd[4]=='ABNT')
			{
			while (strpos($x,'¢') > 0) 
				{
				$pos = strpos($x,'¢');
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
			}
		/** APA */
		if ($dd[4]=='APA')
			{
				$x = troca($x,'¢',chr(13).chr(13));
			}
		return(trim($x));
		}		
	
	function marcacao_import_form()
		{
			global $dd,$acao;
			
			/** Trata entrada */
			$dd[10] .= chr(13);
			$s = $dd[10];
			$s = $this->MAR_preparar($s);
			$s = troca($s,chr(9),' ');
			$s = troca($s,'  ',' ');
			$s = troca($s,'\\','');
			$dd[10] = $s;
			$page = page();
			$dd0 = $dd[0]; $dd1= $dd[1]; $dd2 = $dd[2]; $dd3 = $dd[3]; $dd10=$dd[10];
			
			if ($dd[4]=='ABNT') { $sdd4a = 'selected'; }
			if ($dd[4]=='APA') { $sdd4b = 'selected'; }
			$sx .= '
				<center>
				<form action="'.$page.'" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
				<textarea cols="70" rows="18" name="dd10">'.$dd10.'</textarea>
				<BR>
				<input type="checkbox" name="dd11" value="1">Salvar&nbsp;&nbsp;&nbsp;
				&nbsp;<input type="submit" value="e n v i a r" class="lt2" $estilo >
				<input type="hidden" name="dd0" value="'.$dd0.'">
				<input type="hidden" name="dd1" value="'.$dd1.'">
				<input type="hidden" name="dd2" value="'.$dd2.'">
				<input type="hidden" name="dd3" value="'.$dd3.'">
				<select name="dd4">
					<option value="ABNT" '.$sdd4a.'>ABNT</option>
					<option value="APA"" '.$sdd4b.'>APA</option>					
				</select>
				</form>
				</center>
				';
				
			if ($dd[11] == '1')
				{
					$this->MAR_save($dd10);
				}
			return($sx);
			}	
	////////////////////////////////////////////////// Proposicao e Conjuntoo
	function MAR_cuts($pr,$ar)
		{
		$tit = trim(substr($pr,strpos($pr,$ar)+1+strlen($ar),strlen($pr)));
		$tit = substr($tit,0,strpos($tit,'.'));
		
		$result = $tit;
		return($result);
		}
		
	
	////////////////////////////////////////////////// Busca Titulo
	function MAR_titulo($pr,$ar)
		{
		$aut = $ar[count($ar)-1];
		$tit = trim(substr($pr,strpos($pr,$aut)+1+strlen($aut),strlen($pr)));
		$tit = substr($tit,0,strpos($tit,'.'));
		return($tit);
		}
	////////////////////////////////////////////////// Preposicao	
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

/**
 * Lista de palavras encontradas em referencias online 
 */		
function MAR_online()
	{
		$ar = array('Available from',
					'Acesso em',
					'Recuperado em',
					'Disponivel em',
					'Disponível em',
					'Avaliable from',
					'Avaliable at',
					'Avaliable in'
				);
		return($ar);
	}	
/**
 * Lista de palavras encontradas em referencias de teses
 */	
function MAR_tese()
	{
		$ar = array('TESE (DOUTORADO',
					'TESE DE DOUTORADO',
					'TESE(',
					'TESE ('
			);
		return($ar);
	}	
/**
 * Lista de palavras encontradas em referencias de teses
 */	
function MAR_dissertacao()
	{
		$ar = array('(DISSERTACAO DE MESTRADO)',
					'DISSERTACAO (MESTRADO',
					'DISSERTATION (',
					'TESE DE MESTRADO',
					'DISSERTACAO DE MESTRADO',
					'DISSERTACAO MESTRADO DO PROGRAMA'
			);
		return($ar);
	}	
 
/**
 * Lista de palavras encontradas em referencias de anais de eventos
 */	
function MAR_anais()
	{
		$ar = array('ANAIS ..',
					'ANAIS..',
					'PROCEDINGS..',
					'PROCEEDINGS',
					'APAPERS...',
					'PROCEEDINGS..'
			);
		return($ar);
	}
 
function MAR_tipo($ref)
	{
	$tp = '???';	/* Tipo desconhecido */
	$ok = '';
	/* Tratamento da fonte para processamento */
	
	$ref = UpperCaseSql($ref);
	$ref = troca($ref,'[','');		
	$ref = troca($ref,']','');		
	$ref = troca($ref,' : ',': ');
	$ref = troca($ref,'º','.');		
	$ref = troca($ref,'?','');
		
	$ref = troca($ref,'VOL ','V. ');		
	$ref = troca($ref,'VOL. ','V. ');		
	$ref = troca($ref,'NUM. ','N. ');		
	$ref = troca($ref,'Nº ','N. ');		
	$ref = troca($ref,'NO. ','N. ');		
	$ref = troca($ref,' V0 ','V. ');		
	$ref = troca($ref,',VO ','V. ');		
	
	$sx .= '<BR><table width="600" bgcolor="#808080" border="1" align="center"><TR><TD><TT>'.$ref.'</TD></TR></table>';
	
	/* Tipo Tese de Doutorado */
	$comp = $this->MAR_tese();
	for ($r=0;$r < count($comp);$r++)
		{ if (strpos($ref,$comp[$r]) > 0) { $ok = 'TESE 0000000'; } }
	
	/* Tipo Dissertação */
	$comp = $this->MAR_dissertacao();
	for ($r=0;$r < count($comp);$r++)
		{ if (strpos($ref,$comp[$r]) > 0) { $ok = 'DISSE0000000'; }	}
		
	/* Anais de Eventos */
	$comp = $this->MAR_anais();
	for ($r=0;$r < count($comp);$r++)
		{ if (strpos($ref,$comp[$r]) > 0) { $ok = 'ANAIS0000000'; } }


	if (strpos($ref,'#') > 0)
		{ $ok = 'NC0000000'; }

/* Processa e analisa em comparação a estrutura das revistas */
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
	
	/* Existe volume ou número, considerando como revista */
	if (($v_vol > 0) or ($v_num > 0))
		{
		$sx .= '<UL>';
		$sx .= '<LI>Phase IIIa - Busca por revistas cadastradas';
		$sx .= '<UL><LI>'.'['.$v_vol.'],['.$v_num.']<LI></UL>';
		$sx .= '</LI>';
		
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
	$ref = $xref;
	
	/* Compara com o banco de livros */
	
	if (strlen($ok) == 0)
		{
		$sx .= '<UL>';
		$sx .= '<LI>Phase IIIb - Busca por livros cadastradas';
		$sx .= '</LI>';
					
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
		
	/* Compara com o banco de eventos cadastrados */
	
	if (strlen($ok) == 0)
		{
		$sx .= '<UL>';
		$sx .= '<LI>Phase IIIc - Busca por eventos cadastradas';
		$sx .= '</LI>';

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

	/* Compara com o banco de relatorios cadastrados */
	if (strlen($ok) == 0)
		{
		$sx .= '<UL>';
		$sx .= '<LI>Phase IIId - Busca por relatorio, leis e normas cadastradas';
		$sx .= '</LI>';

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
		/* Anais de Eventos */
		if (strlen($ok) == 0)
			{
			$comp = $this->MAR_online();
			for ($r=0;$r < count($comp);$r++)
				{ if (strpos($ref,$comp[$r]) > 0) { $ok = 'LINK 0000000'; } }
			}
		echo $sx;
		
		return($ok);
		}

	function structure()
		{
			$sql = "
				CREATE TABLE mar_works (
  				id_m serial NOT NULL,
  				m_status char(1),
  				m_ref text,
  				m_title text,
  				m_author char(100),
  				m_codigo char(10),
  				m_journal char(7),
  				m_tipo char(5),
  				m_work char(10),
  				m_ano int4,
  				m_doi varchar(40),
  				m_bdoi char(40),
  				m_first_page char(5),
  				m_norma char(5) NOT NULL default 'ABNT'
  				)";
			//$rlt = db_query($sql);
			
			$sql = "
				CREATE TABLE mar_journal (
  				id_mj serial NOT NULL,
  				mj_codigo char(7),
  				mj_nome char(100),
  				mj_abrev char(40),
  				mj_issn char(10),
  				mj_ativo int2,
  				mj_nome_asc char(50),
  				mj_cidade char(7),
  				mj_tipo char(5),
  				mj_use char(7),
  				m_use_base char(7),
  				m_processar char(1) NOT NULL default 'N'
  				)
				";
			//$rlt = db_query($sql);

			$sql = "			
					CREATE TABLE mar_tipo (
  						id_mt serial NOT NULL,
  						mt_codigo char(5),
  						mt_descricao char(40),
  						mt_ativo int2
					)";
			//$rlt = db_query($sql);
					
			$sql = "			
					INSERT INTO mar_tipo (mt_codigo, mt_descricao, mt_ativo) VALUES
						('PERIO', 'Periódico Científica & Profissional', 1),
						('LINK', 'Página da Internet', 1),
						('LIVRO', 'Livro', 1),
						('CAPIT', 'Capítulo de Livro', 1),
						('ANAIS', 'Anais de eventos', 1),
						('DISSE', 'Dissertação', 1),
						('TESE', 'Tese', 1),
						('TCC', 'TCC de graduação', 1),
						('NORMA', 'Norma técnica', 1),
						('LEI', 'Lei', 1),
						('JORNA', 'Jornal Diário', 1),
						('RELAT', 'Relatório Técnico', 1),
						('NC', '--NC-', 1) ";
			//$rlt = db_query($sql);						
			}
		function updatex_journal()
			{
				global $base;
				$c = 'mj';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela_journal." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela_journal." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
				
				$c2 = $c.'_use';
				$sql = "update ".$this->tabela_journal." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela_journal." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}					
	}
?>