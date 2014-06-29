<?php
class oai
	{
		var $http;
		var $verb;
		
		var $id;
		var $journal_id;
		var $title;
		var $title_alt; 
		var $abstract;
		var $author;
		var $source;
		var $edition;
		var $elink;
		var $tpye;
		
		var $tabela = "brapci_journal";
				
		/* read file */
	function utf8_detect($t)
		{
			$ok = 0;
			if (strpos($t,'Ã§') > 0) { $ok = 1; }
			if (strpos($t,'Ãµ') > 0) { $ok = 1; }
			if (strpos($t,'Ã£') > 0) { $ok = 1; }
			if (strpos($t,'Ã©') > 0) { $ok = 1; }
			
			return($ok);
		}
	function atualiza_harvesting($jid,$erro)
			{
				if ($erro == 'ok')
					{
						$sql = "update ".$this->tabela." set jnl_atual = '".date("Ymd")."' 
								where id_jnl = ".round('0'.$jid);
						$rlt = db_query($sql);
					} else {
						$sql = "update ".$this->tabela." set jnl_atual = '-1' 
								where id_jnl = ".round('0'.$jid);
						$rlt = db_query($sql);		
					}
			}		
	function show_last_harvesting()
		{
			$sql = "select * from ".$this->tabela." 
					where jnl_status <> 'X'
					order by jnl_nome
			";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR><TH width="90%">Nome da publicação</TH>
						<TH>Status
						<TH>Vigente
						<TH width="10%">última coleta';
			while ($line = db_read($rlt))
				{
					$st = $line['jnl_status'];
					$vg = $line['jnl_vinc_vigente'];
					
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['jnl_nome'];
					
					$sx .= '<TD class="tabela01">';
					$sx .= $st;
					
					$sx .= '<TD class="tabela01">';
					$sx .= $vg;

					$sx .= '<TD class="tabela01" align="center">';
					$cl = $line['jnl_atual'];
					if ($cl > 0)
						{
							$status = stodbr($cl);
						} else {
							$status = '<font color="red">Erro de coleta</font>';
							if ($st=='B')
								{
									$status = '<font color="orange">Descontinuada</font>';		
								}
						} 
							
					$sx .= $status;
				}
			$sx .= '</table>';
			return($sx);
		}
	function form_cancel()
		{
			global $dd,$acao,$line;
			if ((strlen($dd[40]) > 0) and ($dd[41]=='CANCEL'))
				{
					$sql = "update oai_cache set cache_status = 'B' where id_cache = ".round($dd[40]);
					$rlt = db_query($sql);
					redirecina(page());
				}
			$sx .= '<form method="post" action="'.page().'">';
			$sx .= '<input type="hidden" name="dd40" value="'.$line['id_cache'].'">';
			$sx .= '<input type="hidden" name="dd41" value="CANCEL">';
			$sx .= '<input type="submit" value="'.msg('cancel_this_register').'">';
			$sx .= '</form>';
			return($sx);
		}
	function record_cancel($id)
		{
			global $dd,$acao,$line;
			$sql = "update oai_cache set cache_status = 'B' where id_cache = ".round($id);
			$rlt = db_query($sql);
			echo '<h2>Cancelado</h2>';
			echo '<meta HTTP-EQUIV = "Refresh" CONTENT = "2; URL = '.page().'?dd1='.$dd[1].'"> ';
			return($sx);
		}		
	
	function resumo_process($jid=0)
		{
			$link = '<A HREF="oai_coleta.php">';
			$link2 = '<A HREF="oai_processar.php">';
			$wh = '';
			if ($jid > 0)
				{ $wh = " where cache_journal = '".strzero($jid,7)."' "; }
			$sql = "SELECT cache_status, count(*) as total 
						FROM oai_cache 
						$wh
						group by cache_status 
						order by cache_status
						";
			$rlt = db_query($sql);
			$ar = array(0,0,0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = trim($line['cache_status']);
					$total = $line['total'];
					if ($sta == '@') { $ar[0] = $ar[0] + $total; }
					if ($sta == 'A') { $ar[1] = $ar[1] + $total; }
					if ($sta == 'B') { $ar[2] = $ar[2] + $total; }				
				}
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR align="center">';
			$sx .= '<TD>&nbsp;';
			$sx .= '<TD class="tabela00" width="15%">Coletar';
			$sx .= '<TD class="tabela00" width="15%">Processar';
			$sx .= '<TD class="tabela00" width="15%">Processado';
			$sx .= '<TD class="tabela00" width="15%">Total';
			$sx .= '<TR align="center">';
			$sx .= '<TD class="tabela01"><font class="lt4">OAI Registers:';
			$sx .= '<TD class="tabela01" align="center"><font class="lt4">'.$link.number_format($ar[0],0,',','.').'</a>';;
			$sx .= '<TD class="tabela01" align="center"><font class="lt4">'.$link2.number_format($ar[1],0,',','.').'</a>';
			$sx .= '<TD class="tabela01" align="center"><font class="lt4">'.number_format($ar[2],0,',','.');
			$sx .= '<TD class="tabela01" align="center"><font class="lt4">'.number_format($ar[0]+$ar[1]+$ar[2],0,',','.');
			$sx .= '</table>';
			return($sx);
		}
	function updatex_article()
		{
				$c = 'ar';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 10;
				$sql = "update brapci_article set 
						$c2 = lpad($c1,$c3,0) 
						where $c2='' ";
				$rlt = db_query($sql);
				return(1);
		}
		
		function issue_show($line)
			{
				$v = 'v. '.$line['ed_vol'];
				$n = ', n. '.$line['ed_nr'];
				$y = ', '.$line['ed_ano'];
				return($v.$n.$y);
			}
		
		
		function issue_select($ses)
			{
				global $dd;
				$journal = $this->journal_id;
				if (strlen($ses) > 40)
					{
						$ses2 = substr($ses,0,strlen($ses)-12);
					} else {
						$ses2 = substr($ses,0,strlen($ses)-0);
					}
				/* Edi??o Selecionada */
				if (strlen($dd[12])==0)
					{
					$sql = "select count(*) as total, max(id_ed) as ed 
							from brapci_edition
							where ed_oai_issue like '%$ses2%' ";
					$rlt = db_query($sql);
					if ($line = db_read($rlt))
						{
							$total = $line['total'];
							$id = $line['ed'];
							if ($total == 1)
								{
								echo '
								<script>
									window.location.href = "'.page().'?dd12='.strzero($id,7).'&dd20=issue&dd13='.$this->id.'";
								</script>
								';
								}
						}						
					}
				
				if (strlen($dd[12]) > 0)
					{
						$sql = "select * from brapci_edition 
							where ed_codigo = '".$dd[12]."' 
							 ";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								$this->issue = $line['ed_codigo'];
								$this->issue_name = $this->issue_show($line);
								$this->issue_year = $line['ed_ano'];
								return($this->issue);
							}
						echo $dd[12];
					}
				
				$sql = "select * from brapci_edition 
						where ed_oai_issue = '".UpperCaseSql($ses)."' 
						and ed_journal_id = '$journal'
						";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						echo '
						<script>
							window.location.href = "'.page().'?dd12='.trim($line['ed_codigo']).'&dd20=issue&dd13='.$this->id.'";
						</script>
						';
						exit;
						}

				
				/* Volume e numero */
				$pos = strpos($ses,'v. ');
				if ($pos > 0) 
						{
						   $vol = trim(substr($ses,$pos + 2,10));
						   $vol = sonumero(substr($vol,0,strpos($vol,','))); 
						}
				$pos = strpos($ses,'n. ');
				if ($pos > 0) 
										{
						   $num = trim(substr($ses,$pos + 2,12));
						   $ano = sonumero(substr($num,strpos($num,'('),10));
						   $year = substr($ano,0,4);
						   $num = sonumero(substr($num,0,strpos($num,'('))); 
						}
				
				echo '<HR>'.$ses.'<BR>'.$vol.'-'.$num.'-'.$year.'<HR>';
				/* Buscape por volume, numero e ano */
				if ((strlen($vol) > 0) and (strlen($num) > 0) and (strlen($year) > 0))
					{
						$sql = "select * from brapci_edition 
								where ed_vol = '".$vol."'
								and ed_nr = '".$num."' 
								and ed_ano = '".$year."' 
								and ed_journal_id = '$journal'
								";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								echo '
								<script>
									window.location.href = "'.page().'?dd12='.trim($line['ed_codigo']).'&dd20=issue&dd13='.$this->id.'";
								</script>
								';
								exit;
								}
					}
				
						$ops = '<select id="dd12">';
						$sql = "select * from brapci_edition where ed_journal_id='$journal' order by ed_ano desc, ed_vol desc, ed_nr desc";
						$rlt = db_query($sql);
						while ($line = db_read($rlt))
							{
								$sh_issue = $this->issue_show($line);
								$ops .= chr(13);
								$ops .= '<option value="'.$line['ed_codigo'].'">'.msg('issue').': '.$sh_issue.'</option>';
							}
						$sx .= '</select>';
						$sx .= $ops;
						$sx .= '<input type="button" value="'.msg('save').'" id="save2">';
						$sx .= '<input type="button" value="'.msg('new_issue').'" id="save3">';
						$sx .= '<input type="hidden" value="'.$this->id.'" id="dd10">';
					
						$sx .= sget('dd3','$HV',$this->id,1,1);
						
						$sx .= '
							<script>
								$("#save2").click(function()
									{
										var sele = $("#dd12").val();
										var id = $("#dd10").val(); 
										window.location.href = "'.page().'?dd12="+sele+"&dd13="+id;
									});
								$("#save3").click(function()
									{
										var sele = $("#dd12").val();
										var id = $("#dd10").val(); 
										window.open("publications_details.php?dd0='.$journal.'","_black");
									});
								
								
							</script>
						';
												
				$tabela = "oai_listsets";
				return($sx);				
			}
		
		function session($ses)
			{
				global $dd;
				$journal = $this->journal_id;
	
				/* Salvar configuracoees */			
				if (strlen($dd[2]) > 0)
							{
								$data = date("Ymd");
								$dd2 = $dd[2];
								$sql = "insert into oai_listsets 
										(ls_setspec, ls_setname, ls_setdescription,
										ls_journal, ls_status, ls_data, 
										ls_equal, ls_tipo, ls_equal_ed)
										values
										('$ses','$ses','',
										'$journal','A',$data,
										'$dd2','S','')
								";
								$rlt = db_query($sql);
							}
				
				/* Recupera */
				$sql = "select * from oai_listsets 
						where ls_setname = '".$ses."' and ls_journal = '".$journal."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$tipo = trim($line['ls_equal']);
						return($tipo);
					} else {
						$ops = '<select id="dd2">';
						$sql = "select * from brapci_section where se_ativo=1 order by se_descricao";
						$rlt = db_query($sql);
						while ($line = db_read($rlt))
							{
								$ops .= chr(13);
								$ops .= '<option value="'.$line['se_codigo'].'">'.msg('section').': '.trim($line['se_descricao']).'</option>';
							}
						$sx .= '</select>';
						$sx = '<TR><TD align="right" class="tabela00">Informe a seção: ';
						$sx .= '<TD>';
						$sx .= $ops;
						$sx .= '<input type="button" value="'.msg('save').'" id="save">';
						$sx .= '<input type="hidden" value="'.$this->id.'" id="dd0">';
						$sx .= sget('dd3','$HV',$this->id,1,1);
						
						$sx .= '
							<script>
								$("#save").click(function()
									{
										var sele = $("#dd2").val();
										var id = $("#dd0").val(); 
										window.location.href = "'.page().'?dd2="+sele+"&dd3="+id;
									});
								
								
							</script>
						';
												
					}
				$tabela = "oai_listsets";
				return($sx);				
			}
		
		function words($s)
			{
				while (strpos($s,'<dc:subject>') > 0)
				{
					$s3 = substr($s,strpos($s,'<dc:subject>')+12,strlen($s));
					$s3 = substr($s3,0,strpos($s3,'<'));
					$assunto   .= $s3 .';';
					$ter = '<dc:subject>';
					$s = substr($s,strpos($s,$ter)+strlen($ter),strlen($s));
				}
				$assunto = trim($assunto);
				$assunto = troca($assunto,'.',';');	
				$assunto = troca($assunto,',',';').';';
				$words = splitx(';',$assunto);
				return($words);
			}	
					
		function cab()
			{
				$sx = '<table width="100%">
						<TR><TD width="80"><img src="../img/oai_logo.gif">
						<TD>
						<B>Coletor automático de publicações com protocolo OAI-PMH</B>
						<BR>Desenvolvido por Rene Faustino Gabriel Junior &copy; 2010-2013
						<BR>Versão v.0.14.14
						</table>
				';
				return($sx);
			}

		/* Salvar Artigo */
		function oai_recupera_cod($art)
			{
				$sql = "select * from brapci_article where ar_obs = '$art' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						return($line['ar_codigo']);
					} else {
						return(0);
					}			
			}
		function oai_save_99($id)
			{
				$sql = "update oai_cache set cache_status = 'B' where cache_oai_id = '$id' ";
				$rlt = db_query($sql);
			}
		function oai_save_03($id,$keys)
			{
				$kw = new keyword;
				//$au = new keys;
				$sql = "delete from brapci_article_keyword where kw_article = '$id'";
				$rlt = db_query($sql);
				
				for ($r=0;$r < count($keys);$r++)
					{
						$nome_ref = $keys[$r][0];
						$idioma = $keys[$r][1];
						$cod = $kw->keyword_find($nome_ref,$idioma);
						$sql = "insert into brapci_article_keyword
								(
								kw_article, kw_keyword, kw_ord
								) value (
								'$id','$cod',$r)
								";
						$rlt = db_query($sql);
					}
				return(1);
			}

		function oai_save_02($id,$author)
			{
				$au = new author;
				for ($r=0;$r < count($author);$r++)
					{
						$nome_ref = $author[$r][0];
						$comple = '';
						$pos = strpos($nome_ref,';');
						if ($pos > 0)
							{
								$comple = substr($nome_ref,$pos+1,strlen($nome_ref));
								$nome_ref = substr($nome_ref,0,$pos); 
							}
						$nome = nbr_autor($nome_ref,1);
						$au->bio = $comple;
						$authors = $au->author_find($nome_ref);
						$pos = $au->author_next_pos($id);
						$jid = $this->journal_id;
						
						//$au->autor_instituition = $comple;
						$au->author_article_save($id,$authors,$pos,$jid);
					}
				return(1);
			}
		function oai_save_04($id,$files)
			{
				for ($r=0;$r < count($files);$r++)
					{
						$nome_ref = $files[$r][0];
						$data = date("Ymd");
						$journal = $this->journal_id;
						$type = 'URL';
						
						if (substr($nome_ref,0,3)=='oai') { $type="OAI"; }
						
						$sql = "select * from brapci_article_suporte 
								where bs_adress = '$nome_ref' and
								bs_article = '$id'
						";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								$sql = "update brapci_article_suporte
											set bs_adress = '$nome_ref',
											bs_type = '$type'
										where id_bs = ".round($line['id_bs']);
								$rlt = db_query($sql);
								
							} else {
								$sql = "insert into brapci_article_suporte 
										(bs_article, bs_type, bs_adress,
										bs_status,bs_journal_id, bs_update )
										values
										('$id','$type','$nome_ref',
										'A','$journal',$data)
								";
								$rlt = db_query($sql);
							}				
						//$au->autor_instituition = $comple;
						//$au->author_article_save($id,$authors,$pos,$jid);
					}
				return(1);
			}
			
		function oai_save_00($titulo,$jid,$id_oai)
			{
				$titulo_asc = UpperCaseSql($titulo);
				$sql = "select * from brapci_article 
						where ar_titulo_1_asc = '$titulo_asc'
						and ar_journal_id = $jid
					";
				$rlt = db_query($sql);
				$id_oai = trim($id_oai);
				if ($line = db_read($rlt))
					{
						$sql = "update brapci_article 
								set 
								ar_oai_id = '$id_oai',
								at_obs = '$id_oai'
								where id_ar = ".$line['id_ar'];
						$rlt = db_query($sql);
					} else {
						return(0);
					}				
			}
			
			
		function oai_save_01($id)
			{
				$sql = "select * from brapci_article where 
					ar_obs = '$id' 
					or ar_oai_id = '$id'
					";
				$rlt = db_query($sql);
				
						$journal = $this->journal_id;
						$titulo = $this->title;
						$titulo2 = $this->title_alt;
						$titulo_asc = UpperCaseSql($titulo.$titulo2);
						$idioma1 = $this->idioma_1;
						$idioma2 = $this->idioma_2;
						$oai_id = $id;
						$data = date("Ymd");
						$datan = 19000101;
						$doi = '';
						
						$tipo = $this->tpye;
						$abstract1 = $this->abstract;
						$abstract2 = $this->abstract_alt;
						$year = $this->issue_year;
						$issue = $this->issue;
						$session = $this->session;
										
				if ($line = db_read($rlt))
					{
						echo '<BR>Atualiza...';
						$sql = "update brapci_article set
									ar_titulo_1 = '$titulo',
									ar_titulo_1_asc = '$titulo_asc',
									ar_titulo_2 = '$titulo2',
									ar_resumo_1 = '$abstract1',
									ar_resumo_2 = '$abstract2',
									ar_idioma_1 = '$idioma1',
									ar_idioma_2 = '$idioma2',
									ar_edition = '$issue',
									ar_tipo = '$session',
									ar_section = '$session'
								where ar_obs = '$id' ";
						$rlt = db_query($sql);
						$this->updatex_article();
					} else {
						echo '<BR>Novo...';						
						$sql = "insert into brapci_article 
								(
									ar_journal_id , ar_codigo, ar_titulo_1,
									ar_titulo_1_asc, ar_titulo_2, ar_titulo_3,
									ar_idioma_1, ar_idioma_2, ar_idioma_3,
									
									ar_obs, ar_status, ar_disponibilidade,
									ar_pg_inicial, ar_pg_final, ar_data_envio,
									ar_data_aceite, ar_data_aprovado, ar_doi,
									
									ar_oai_id, ar_tipo, ar_resumo_1,
									ar_resumo_2, ar_resumo_3, ar_section,
									at_mt, ar_ano, ar_bdoi, ar_edition
								) values (
									'$journal', '','$titulo',
									'$titulo_asc','$titulo2','',
									'$idioma1','$idioma2','',
									
									'$oai_id','A',$data,
									'','',$datan,
									$datan,$datan,'$doi',
									
									'$oai_id','$session','$abstract1',
									'$abstract2','$abstract3','$session',
									'A','$year','','$issue'
								)
						";
						$rlt = db_query($sql);
						$this->updatex_article();
					}
			}
		function proximo_processamento($jid=0)
			{
				if ($jid > 0)
					{ $wh = " and cache_journal = '".strzero($jid,7)."'"; }
				$sql = "select * from oai_cache 
							where cache_status = 'A'
							$wh 
					  order by id_cache desc limit 1";
				$zrlt = db_query($sql);
				if ($line = db_read($zrlt))
					{
						return($line);
					}
				return(array());
				
			}
			
		function read_link($url)
			{
				$ch = curl_init();
				echo '<BR>Link:'.$url.'<BR>';
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024);
				$contents = curl_exec($ch);
				
				if (curl_errno($ch)) {
  					echo curl_error($ch);
	  				echo "\n<br />";
  					$contents = '';
				} else {
	  				curl_close($ch);
				}
				if (!is_string($contents) || !strlen($contents)) {
					echo "Failed to get contents.";
					$contents = '';
				}
				return($contents);				
			}
		function read_link_fopen($link)
			{
				$rlt = fopen($link,'r');
				$s = '';
				while (!(feof($rlt)))
					{ $s .= fread($rlt,1024); }
				$s = troca($s,"'","?");

				fclose($rlt);
				return($s);
			}
		function cache_para_coletar($jid=0)
			{
				if ($jid > 0)
					{
					$wh = " and cache_jounal = '".strzero($jid,13)."' ";
					}
				$sql = "select count(*) as total
						from oai_cache
						where cache_status = '@' 
						$wh
						";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$total = $line['total'];
				return($total);
			}
			
		function cache_next_harvesting_journal($id,$jid=0)
			{
				if ($jid > 0) { $wh = " and jnl_codigo = '".strzero($jid,7)."'"; }
				$sql = "select * from brapci_journal 
							where id_jnl > $id and jnl_url_oai <> ''
							$wh
							";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						return($line['id_jnl']);
					} else {
						return(0);
					}
			}
		
		function cache_next_harvesting($jid=0)
			{
				if ($jid > 0)
					{
						$wh = " and cache_journal = '".strzero($jid,7)." ' ";
					}
				$sql = "select * from oai_cache 
							where cache_status = '@' 
							$wh
							";
				$sql .= " order by cache_tentativas ";
				$sql .= " limit 1 ";
				$zrlt = db_query($sql);
				$id = '';
				if ($line = db_read($zrlt))				
					{ $id = $line['cache_oai_id']; }
				return($id);
			}	
		function incrementa_tentativa($id)
			{
				$sql = "update oai_cache set cache_tentativas = (cache_tentativas + 1) where cache_oai_id = '$id' ";
				$irlt = db_query($sql);
				return(1);
			}

		function oai_listidentifiers($id)
			{
				$link_from = '';
				$jid = strzero(trim($id),7);
				$sql = "select * from brapci_journal 
							where id_jnl = $jid ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$token = oai_content($s,'resumptionToken');
						
						/* Busca seletiva */
						$seletivo = $line['jnl_oai_from'];
						$link_from = '';
						$harvesting_last = round($line['jnl_last_harvesting']);
						if ((strlen($harvesting_last) > 0) and ($harvesting_last > 19600101))
						{
							$hl = $harvesting_last;
							$hl = substr($hl,0,4).'-'.substr($hl,4,2).'-'.substr($hl,6,2);
							if ($seletivo == 1) { $link_from = '&from='.$hl; }
						} else {
							//$link .= '&from=1910-01-01';
						}						
						
						$link = trim($line['jnl_url_oai']);
						$link .= "?verb=ListIdentifiers&metadataPrefix=oai_dc".$link_from;
						
						$s = $this->read_link($link);
						$s = troca($s,'<header status="deleted">','<header>');
						if (oai_integridade($s)==1)
						{
							$record = oai_string($s,'<header>','</header>');
							for ($r=0;$r < count($record);$r++)
								{
								$identifier = oai_content($record[$r],'identifier');
								$stamp = oai_content($record[$r],'datestamp');
								
								$setSpec = oai_content($record[$r],'setSpec');
								
								if (strlen($identifier[0][0]) > 0)
									{
									$sql = "select * from oai_cache where cache_oai_id = '".$identifier[0][0]."' ";
									$rlt = db_query($sql);
									if (!($line = db_read($rlt)))
										{
											echo '<BR><TT>>>>'.$identifier[0][0].'</TT>';
											$sql = "insert into oai_cache ";
											$sql .= "( cache_oai_id, cache_status, cache_data, ";
											$sql .= "cache_journal, cache_prioridade, ";
											$sql .= "cache_datastamp, cache_setSpec )";
											$sql .= " values ";
											$sql .= "('".$identifier[0][0]."','@','".date("Ymd")."',";
											$sql .= "'".$jid."','5',";
											$sql .= "'".$stamp[0][0]."','".$setSpec[0][0]."'";
											$sql .= ")";
											$zrlt = db_query($sql);
											$novo++;
										}
									}
								}
							return('ok');
						} else {
							return('Error cheksum');
						}
					}
				return('Link error: '.$link);
			}						
			
		function cache_coleta_registro($id)
			{
				$id = trim($id);
				$this->incrementa_tentativa($id);
				$sql = "select * from oai_cache
							inner join brapci_journal on jnl_codigo = cache_journal 
							where cache_oai_id like '%$id%' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$cod = $line['id_cache'];
						$link = trim($line['jnl_url_oai']);
						$link .= "?verb=GetRecord&metadataPrefix=oai_dc&identifier=".$id;
						
						$s = $this->read_link($link);
						
						$utf8 = 0;
						$utf8 = 1;
						$s = troca($s,"'",'?');
						$s = troca($s,"'",'?');
						$s = troca($s,"&gt;",']');
						$s = troca($s,"&lt;",'[');
						if (oai_integridade($s)==1)
						{
							$file = strzero($cod,7).'.xml';
							if (!(is_dir('oai')))
								{ mkdir('oai'); }
							$rlt = fopen('oai/'.$file,'w+');
							echo '<BR>'.$file;
							fwrite($rlt,$s);
							fclose($rlt);
							
							$sql = "update oai_cache set
										cache_status = 'A'
										where cache_oai_id = '$id' 
							";
							$rlt = db_query($sql);
							
							/* salva arquivo */
							
							
							return('ok');
						} else {
							return('Error cheksum');
						}
					}
				return('Link error: '.$link);
			}		
		function getrecord()
			{
				
			}
		
	}
	
/* funcoes de apoio OAI */
function oai_integridade($oai)
	{
	$oai = ' '.$oai;
	$ok = 0;
	if (strpos($oai,'<OAI-PMH') > 0) { $ok++; }
	if (strpos($oai,'</OAI-PMH') > 0) { $ok++; }
	if ($ok==2) { return(1); }
	return(0);
	}
	
function oai_autor($nrz)
	{
	$nra = array();
	for ($nr = 0;$nr < count($nrz);$nr++)
		{
		$sa = $nrz[$nr];
		$sb = '';
		if (strpos($sa,';') > 0)
			{
			$sb = substr($sa,strpos($sa,';')+1,strlen($sa));
			$sa = substr($sa,0,strpos($sa,';'));
			}
		if (strlen($sa) > 0)
			{
			array_push($nra,array('',$sa,nbr_autor($sa,5),nbr_autor($sa,1),'','',$sb));
			$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
			}
		}
	//// Processa na base

	for ($nr = 0;$nr < count($nra);$nr++)
		{
		$xsql = "select * from brapci_autor ";
		$xsql .= " where autor_nome_abrev = '".UpperCaseSql($nra[$nr][2])."'";
		$xrlt = db_query($xsql);
		if (!($xline = db_read($xrlt)))
			{
			$xsql = "insert into brapci_autor ";
			$xsql .= "(autor_codigo,autor_nome,autor_nome_asc,";
			$xsql .= "autor_nome_abrev,autor_nome_citacao,autor_nasc,";
			$xsql .= "autor_lattes,autor_alias";
			$xsql .= ") values (";
			$xsql .= "'','".$nra[$nr][3]."','".UpperCaseSql($nra[$nr][3])."',";
			$xsql .= "'".UpperCaseSql($nra[$nr][2])."','".$nra[$nr][3]."','',";
			$xsql .= "'',''";
			$xsql .= ")";
			$xrlt = db_query($xsql);	
			echo '<BR>Cadastrado autor '.$nra[$nr][1].' '.$nra[$nr][2].' '.$nra[$nr][3];
			$xsql = "update brapci_autor set autor_codigo=lpad(id_autor,7,'0') where autor_codigo=''";			
			$xrlt = db_query($xsql);

			$xsql = "select * from brapci_autor ";
			$xsql .= " where autor_nome_abrev = '".UpperCaseSql($nra[$nr][2])."'";
			$xrlt = db_query($xsql);
			$xline = db_read($xrlt);
			}
		$xcod = $xline['autor_codigo'];
		$nra[$nr][0] = $xcod;
		}
	return($nra);
	}

function oai_listsets_equal($oai,$j)
	{
	$oai = troca($oai,'&','e');
	$sql = "select * from oai_listsets ";
	$sql .= " left join brapci_section on se_codigo = ls_equal ";
	$sql .= " where ls_setspec = '".$oai."' and ls_journal='".strzero($j,7)."' ";
	$zrlt = db_query($sql);

	if ($zline = db_read($zrlt))
		{
			$tipo = $zline['ls_tipo'];
			if ($tipo == 'E')
				{	
					$result = array(1,$zline['se_descricao'],$zline['ls_equal_ed'],$zline['ls_tipo'],'');
				} else {
					$equal = trim($zline['ls_equal']);
					if (strlen($equal) > 0)
						{
							$result = array($zline['se_processar'],$zline['se_descricao'],$zline['ls_equal'],trim($zline['ls_tipo']),'');
						} else {
							$slink = '<input type="submit" name="acao" value="associar se??o" onclick="newxy('.chr(39).'oai/listsets_equal.php?dd0='.$zline['id_ls'].chr(39).',600,230);">';
							$result = array(-1,'','',0,$slink);
						}
				}
		} else {
			$result = array(-1,'','',0,'ListSets N?o existe nesta publica??o');
			$sql = "insert into oai_listsets ";
			$sql .= "( ls_setspec, ls_setname, ls_setdescription, ";
			$sql .= "ls_journal, ls_status, ls_data ";
			$sql .= " )";
			$sql .= " values ";
			$sql .= "('".$oai."','".$oai."','$setDescription',";
			$sql .= "'".strzero($j,7)."','A',".date("Ymd");
			$sql .= ")";
			$xrlt = db_query($sql);
		}
		return($result);
	}
function oai_edicao($oai)
	{
	$oai = ' '.LowerCase($oai);
	$edicao = array('','','','','');
	// Volume, Numero, Ano, Tem?tica
	if (strpos($oai,'vol.') > 0)
		{ 
			$vi = strpos($oai,'vol.'); 
			$vol = substr($oai,$vi+4,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$v = trim($vol);
		}
	if (strpos($oai,'v.') > 0)
		{ 
			$vi = strpos($oai,'v.'); 
			$vol = substr($oai,$vi+2,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			if (strpos($vol,' ') > 0) { $vol = substr($vol,0,strpos($vol,' ')); }
			$v = trim($vol);
		}		
	if ((strpos($oai,'vol ') > 0) and (strlen($v) == 0))
		{ 
			$vi = strpos($oai,'vol '); 
			$vol = substr($oai,$vi+4,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$v = trim($vol);
		}		
	if (strpos($oai,'no ') > 0)
		{ 
			$vi = strpos($oai,'no '); 
			$vol = substr($oai,$vi+2,strlen($oai));
			echo '['.$vol.']';
//			exit;
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$n = trim($vol);
		}		
	if (strpos($oai,'n.') > 0)
		{ 
			$vi = strpos($oai,'n.'); 
			$vol = substr($oai,$vi+2,strlen($oai));
			echo '['.$vol.']';
//			exit;
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			if (strpos($vol,' ') > 0) { $vol = substr($vol,0,strpos($vol,' ')); }
			$n = trim($vol);
		}
	///////////////////////////// ano
	if (strpos($oai,'no') > 0)
		{ 
			$vi = strpos($oai,'('); 
			$vol = substr($oai,$vi+1,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,')') > 0) { $vol = substr($vol,0,strpos($vol,')')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$a = trim($vol);
		}		
	if (strpos($oai,'n.') > 0)
		{ 
			$vi = strpos($oai,'n.'); 
			$vol = substr($oai,$vi+4,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,')') > 0) { $vol = substr($vol,0,strpos($vol,')')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$a = trim($vol);
		}		

	$edicao[0] = sonumero($v);
	$edicao[1] = sonumero($n);
	$edicao[2] = sonumero($a);
	return($edicao);
	}

function oai_string($oai,$si,$sf)
	{
	$result = array();
	$oai = ' '.$oai;
	
	while (strpos($oai,$si) > 0)
		{
		$sp = strpos($oai,$si)+strlen($si);
		$se = strpos($oai,$sf);
		$sa = substr($oai,$sp,$se-$sp);
		array_push($result,$sa);
		$oai = ' '.substr($oai,$se+strlen($sf),strlen($oai));
		}
	return($result);
	}
	
function oai_content($oai,$id)
	{
		$rst = array();
		
		$si = '<'.$id;
		$sf = '</'.$id;
		$oai = ' '.$oai;
		$sp = 1;
		$it = 0;
		while (strlen($oai) > 0)
			{
				$it++;
				if ($it > 5) { exit; }
				$sp = round(strpos($oai,$si));
				if ($sp == 0) { $oai = ''; }
				
				$se = strpos($oai,$sf);
				$sa = substr($oai,$sp,$se-$sp);
				$sa = trim(substr($sa,strpos($sa,'>')+1,strlen($sa)));
				
				$oai = substr($oai,$se,strlen($oai));
				//echo '<BR>'.$id.'='.$sp;
				//echo '<PRE>'.$oai.'</PRE>';
				/* Idioma */
				if (substr($sa,0,1)=='[')
					{
						$idioma = substr($sa,1,strpos($sa,']')-1);
						if ($idioma == 'pt-BR') { $idioma = 'pt_BR'; }
						$termo = substr($sa,strpos($sa,']')+1,strlen($sa));		
					} else {
						$idioma = 'pt_BR';
						$termo = trim($sa);
					}
				if (strlen($termo) > 0)
					{
						array_push($rst,array($termo,$idioma));
					}
				//exit;
			}
		/* Detecta Idioma */
		return($rst);
	}

function show($t)
	{
		for ($r=0;$r < count($t);$r++)
			{
				echo $t[$r][0].'('.$t[$r][1].')<BR>';
			}
		
	}
function oai_identifier($oais)
	{
		return(oai_content($oais,'identifier'));
	}	
?>
