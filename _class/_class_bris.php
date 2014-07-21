<?php
class bris
	{
	var $iffa2 = 0;
	var $iffa3 = 0;
	var $iffa5 = 0;
	var $idh = 0;
	
	function artigos_ano_citacao($ano,$tipo)
		{
			$sql = "select m_ano, count(*) as total from mar_works
					inner join brapci_article on m_work = ar_codigo 
						where ar_ano = '$ano' and m_tipo = '$tipo' and m_status <> 'X'
						group by m_tipo, m_ano
						order by m_ano desc, m_tipo";
			$rlt = db_query($sql);
			echo $sql;
			$ar = array();
			$an = array();
			
			$max = 10;
			while ($line = db_read($rlt))
				{
					if ($max < $line['total']) { $max = $line['total']; }
					
					array_push($an,$line['m_ano']);
					array_push($ar,$line['total']);
				}
			$sx .= '<table width="98%">';
			$tot = 0;
			for ($r=0;$r < count($an);$r++)
				{
					$tot = $tot + $ar[$r];
					$sx .= '<TR><TD>'.$an[$r];
					$sx .= '<TD align="center">'.$ar[$r];
					$sx .= '<TD>';
				}
			$sx .= '<TR><TD colspan=2><I>Total '.$tot.'</I></TD></TR>';
			$sx .= '</table>';
			return($sx);
			print_r($an);
			print_r($ar);
			
		}
	
	function tipologia_fontes($anoi,$anof)
		{
			/* LINKS */
			$sql = "update mar_works set m_tipo = 'LINK' where m_tipo = 'LINK0' ";
			$rlt = db_query($sql);

			/* LEI */
			$sql = "update mar_works set m_tipo = 'LEI' where m_tipo = 'LEI00' ";
			$rlt = db_query($sql);
			
			/* NC */
			$sql = "update mar_works set m_tipo = 'NC' where m_tipo = 'NC000' ";
			$rlt = db_query($sql);

			$sql = "select m_tipo , ar_ano, count(*) as total from mar_works
					inner join brapci_article on m_work = ar_codigo and m_status <> 'X' 
						where ar_ano >= $anoi and ar_ano <= $anof  
						group by m_tipo
						order by ar_ano, m_tipo";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00" width="98%" align="center">';
			while ($line = db_read($rlt))
				{
					$tot = $tot + $line['total'];
					$tipo = $line['m_tipo'];
					$ano = $line['ar_ano'];
					$total = $line['total'];
					$link = '<A HREF="indicador_tipologia_ano.php?dd0='.$ano.'&dd1='.$tipo.'">';
					$sa .= '<TD width="6%">'.$link.$tipo.'</A>';
					$sb .= '<TD align="center" class="tabela01">'.$total;
				}
			$sx .= '<TR align="center">'.$sa;
			$sx .= '<TD><B>Total</B>';
			$sx .= '<TR>'.$sb;
			$sx .= '<TD align="center" class="tabela01"><B>'.$tot.'</B>';
			$sx .= '</table>';
			return($sx);
		}
	
	function mostra_iffa($autor, $ano='')
		{
			$sql = "select * from bris_autor 
					where au_codigo = '$autor' or au_ano = '$ano' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$fi2 = $line['au_cr_2'];
					$this->iffa2 = number_format($fi2,4,',','.');
					$fi2 = $line['au_cr_3'];
					$this->iffa3 = number_format($fi3,4,',','.');
					$fi2 = $line['au_cr_5'];
					$this->iffa5 = number_format($fi5,4,',','.');										
					print_r($line);
				}
		}
	function indicador_autor($autor)
		{
			$ano = '2013';
			
			/* iCPA */
			$icpa = $this->mostra_icpa($autor);
			
			/* IFFA */
			$this->mostra_iffa($autor,$ano);
			
			$sx = '<table width="400">';
			$sx .= '<TR><TH colspan=2 align="center">Indicador</TH></TR>';
			
			$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
			$sx .= '<TR><TD align="right">iCPA'.$icpa;
			
			$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
			$sx .= '<TR><TD align="right">FI (2 anos)<TD align="center">'.$this->iffa2;

			$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
			$sx .= '<TR><TD align="right">FI (3 anos)<TD align="center">'.$this->iffa3;

			$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
			$sx .= '<TR><TD align="right">FI (5 anos)<TD align="center">'.$this->iffa5;

			$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
			$sx .= '<TR><TD align="right">Índice h<TD align="center">'.$this->idh;

			$sx .= '</table>';
			return($sx);
		}
	function citacoes_por_ano()
		{
			$sql = "select count(*) as total, m_bdoi, ar_codigo from mar_works
					inner join brapci_article on m_bdoi = ar_bdoi 
						where m_bdoi <> '' group by m_bdoi, ar_codigo ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$total = $line['total'];
					$artigo = $line['ar_codigo'];
					$this->atualiza_citacoes($artigo,$total,'0000','');
				}
		}
	function citacoes_por_ano_base()
		{
			$sql = "delete from bris_cited where 1=1 ";
			$rlt = db_query($sql);
			
			$sql = "select ed_ano, substr(m_bdoi,1,4) as cited,
						m_work, t2.ar_codigo as origem
						from mar_works
					inner join brapci_article as t1 on m_work = t1.ar_codigo 
    				inner join brapci_edition on t1.ar_edition = ed_codigo
    				inner join brapci_article as t2 on m_bdoi = t2.ar_bdoi
					where m_bdoi <> '' 
					group by ed_ano, m_bdoi, t1.ar_codigo, t2.ar_codigo
					order by ed_ano desc
				";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$ano = $line['ed_ano'];
					$ano2 = $line['cited'];

					$citante = $line['m_work'];
					$citado = $line['origem'];
					$this->isere_citacao_relacao($ano,$citante,$ano2,$citado);
				}
		}		
	function isere_citacao_relacao($a1,$c1,$a3,$c2)
		{
			$data = date("Ymd");
			$sql = "insert into bris_cited (cit_ano_pub, cit_ano_art, cit_publico, cit_citado, cit_update) 
						values
						('$a1','$a3','$c1','$c2',$data);
			";
			$rlt = db_query($sql);
		}
	function atualiza_citacoes($artigo,$total,$ano,$autor='')
		{
			$data = date("Ymd");
			
			$sql = "select * from bris_article 
						where ca_artigo = '".$artigo."' and ca_autor = '$autor'
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sql = "update bris_article set  
						ca_citacoes = $total,
						ca_update = $data
						where id_ca = ".$line['id_ca'];
				} else {
					$sql = "insert into bris_article
							(
								ca_artigo, ca_ano, ca_citacoes,
								ca_update, ca_autor 
							) values (
								'$artigo','$ano','$total',
								$data, '$autor'
							)					
					";
					$rlt = db_query($sql);					
				}
		}
	
	function ranking_author($ano='')
		{
			$sql = "select * from bris_autor
						inner join brapci_autor on au_codigo = autor_codigo 
						where au_ano = '$ano' ";
			$rlt = db_query($sql);

			$sx .= '<table width="100%" align="center" border=0 class="tabela00" cellpadding=0 cellspacing=0 > ';
			$sx .= '<TR>
						<TH>Autor
						<TH>Trabalhos
						<TH>índice h
						<TH>II
						<TH>FI<BR>2 anos
						<TH>FI<BR>3 anos
						<TH>FI<BR>4 anos
			';
			while ($line = db_read($rlt))
			{
				$link = '<A HREF="author_view.php?dd0='.$line['autor_codigo'].'" target="_new">';
				$sx .= '<TR>';
				$sx .= '<TD align="left" class="tabela01">';
				$sx .= $link.$line['autor_nome'].'</A>';
				$sx .= '<TD width="5%" align="center" class="tabela01">';
				$sx .= $line['au_artigos'];
				$sx .= '<TD width="5%" align="center" class="tabela01">'.$h;
				$sx .= '<TD width="5%" align="center" class="tabela01">'.$ii;
				$sx .= '<TD width="5%" align="center" class="tabela01">'.$fi2;
				$sx .= '<TD width="5%" align="center" class="tabela01">'.$fi3;
				$sx .= '<TD width="5%" align="center" class="tabela01">'.$fi5;
			}	
			$sx .= '</table>';
			return($sx);			
		}
	
	function ranking_author_create($ano='')
		{
			$sql = "
					select count(*) as total, ae_author  from brapci_article 
					inner join brapci_edition on ar_edition = ed_codigo
					inner join brapci_article_author on ar_codigo = ae_article
					inner join brapci_journal on ae_journal_id = jnl_codigo
		
					where ar_status <> 'X' and ed_ano = '$ano' and jnl_tipo = 'J'
					
					group by ae_author
					order by total desc, ae_author
					";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$autor = trim($line['ae_author']);
					$total = trim($line['total']);
					$this->atualiza_producao($autor,$ano,$total);
				}

		}
	function atualiza_producao($autor,$ano,$total)
		{
			$sql = "select * from bris_autor
						where au_codigo = '$autor'
						and au_ano = '$ano'
					";
			$rlt = db_query($sql);
			$data = date("Ymd");
			if (strlen($autor) > 0)
			{
				if ($line = db_read($rlt))
					{
						$sql = "update bris_autor set 
									au_artigos = '$total',
									au_update = $data
								where id_au = ".$line['id_au'];
					} else {
						$sql = "insert into bris_autor 
								(
								au_codigo, au_ano, au_artigos,
								au_cr_i, au_cr_2, au_cr_3,
								au_cr_5, au_tcr, au_update
								) values (
								'$autor','$ano',$total,
								0,0,0,
								0,0,$data)
						";
					}
				$rlt = db_query($sql);
			}
			return(1);
			
		}
		
	function journal_cited_by_years($ano=0,$tipo='S',$anos)
		{
			if ($tipo == 'A') 
				{ $wh2 = ''; }
			else { $wh2 = " and ((m_tipo = 'ARTIC') or (m_tipo = 'PERIO')) "; }
			
			$wh2 = " and ((m_tipo = 'LIVRO') or (m_tipo = 'CAPIT')) ";
			
			if ($tipo == 'S')
				{ $wh3 = " where (m_processar = '$tipo' ) "; }
			if ($tipo == 'O')
				{ $wh3 = " where (m_processar <> 'S' ) "; }				
			
			//$wh2 = " and ((m_tipo = 'LIVRO') or (m_tipo = 'CAPIT')) ";
			$sql = "select sum(total) as total, m_ano from (
					SELECT * from (
						SELECT m_ano, m_journal, count(*) as total FROM mar_works 
						INNER JOIN brapci_article on `m_work` = ar_codigo
						INNER JOIN brapci_edition on ed_codigo = ar_edition
					WHERE ed_ano = '$ano' and m_status <> 'X'
						$wh2
						GROUP BY m_journal, m_ano
    				) as tabela
					LEFT JOIN mar_journal on m_journal = mj_codigo
					 $wh3 ) as tabela
					 group by m_ano
					 order by m_ano 
					  ";
			$rlt = db_query($sql);
			echo $sql;
			$sx = '<h3>Ano Base: '.$ano.' ('.$anos.' anos)</h3>';
			
			$sx .= '<table width="50%" align="center" border=0 class="tabela00" cellpadding=0 cellspacing=0 > ';
			$tot = 0;
			$id = 0;
			$max = 100;
			while ($line = db_read($rlt))
				{
					$id++;
					$tot = $tot + $line['total'];
					$vano = $line['total'];
					if ($vano > 0)
						{
							$wdt = (int)(($vano / $max) * 200);
						}
					if ($line['m_ano'] > (date("Y")-50))
						{
						$rr .= '<TR><TD>'.$line['m_ano'].'<TD>'.$line['total'];
						$r1 .= '<TD ><font class="lt0">'.substr($line['m_ano'],2,2).'</font>';
						$r2 .= '<TD class="tabela01" align="center"><font style="font-size:8px;">'.$line['total'].'</font>';
						$r3 .= '<TD class="tabela01 lt0" width="3%">'.
							'<img src="img/nada_verde.png" width="18" height="'.$wdt.'">';
						}							
				}
			$sx .= '<TR valign="bottom"><TD>'.$r3;
			$sx .= '<TR><TD class="lt0"><NOBR>Qtda. citações</NOBR>'.$r2;
			$sx .= '<TR><TD class="lt0"><NOBR>ano da ref. citada</NOBR>'.$r1;
			$sx .= '<TR><TD colspan=10><I>Total '.$tot.'</I>';
			$sx .= '</table>';
			$sx .= '<BR><BR>';
			$sx .= '<table border=1 class="lt0">'.$rr.'</table>';
			return($sx);			
		}
		
	function journal_cited_by_year($ano=0,$tipo='S',$anos)
		{
			if ($tipo == 'A') 
				{ $wh2 = ''; }
			else { $wh2 = " and ((m_tipo = 'ARTIC') or (m_tipo = 'PERIO')) "; }

			if ($anos > 0)
				{
					$wh2 .= ' and (';
					$id = 0;
					for ($r=($ano-1);$r > ($ano-1-$anos);$r--)
						{
							if ($id > 0) { $wh2 .= ' or '; }
							$wh2 .= '(m_ano = '.$r.')';
							$id++;
						}
					$wh2 .= ')';
				}
			if ($tipo == 'S')
				{ $wh3 = " where (m_processar = '$tipo' ) "; }
			if ($tipo == 'O')
				{ $wh3 = " where (m_processar <> 'S' ) "; }					
			
			//$wh2 = " and ((m_tipo = 'LIVRO') or (m_tipo = 'CAPIT')) ";
			$sql = "SELECT * from (
						SELECT ed_ano,m_journal, count(*) as total FROM mar_works 
						INNER JOIN brapci_article on `m_work` = ar_codigo
						INNER JOIN brapci_edition on ed_codigo = ar_edition
					WHERE ed_ano = '$ano' and m_status <> 'X'
						$wh2
						GROUP BY ed_ano , m_journal
    				) as tabela
					LEFT JOIN mar_journal on m_journal = mj_codigo
					 $wh3
					ORDER BY total desc, ed_ano desc, mj_nome ";
			$rlt = db_query($sql);
			
			$sx = '<h3>Ano Base: '.$ano.' ('.$anos.' anos)</h3>';
			
			$sx .= '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH>Pos<TH>Citações<TH>Publicação<TH>Tipo';
			$tot = 0;
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$tot = $tot + $line['total'];
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $id.'.';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $line['total'];
					$sx .= '<TD class="tabela01">';
					$nome = trim($line['mj_nome']);
					$nome = troca($nome,',',' ');
					$nome = troca($nome,'.',' ');
					$nome = trim($nome);
					$sx .= $nome;
					$sx .= '<TD class="tabela01">';
					$sx .= $line['mj_tipo'];
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $line['m_processar'];
					
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $line['m_journal'];
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $line['mj_use'];
				}
			$sx .= '<TR><TD colspan=10><I>Total '.$tot.'</I>';
			$sx .= '</table>';
			return($sx);			
		}
	function journal_fasciculos($jid,$ano=0)
		{	
			$sql = "
					SELECT * FROM bris_data 
					inner join brapci_journal on db_journal = jnl_codigo
					inner join brapci_edition on db_fasciculo = ed_codigo
					where db_journal = '".strzero($jid,7)."' and db_ano = '".$ano."'
					order by db_ano desc, ed_vol desc, ed_nr desc
			";
			$rlt = db_query($sql);
			$sx = '<table width="100%">';
			$sx .= '<TR class="lt0"><TH width="10%">ISSN
						<TH width="65%">Journal Name
						<TH width="5%">Ano
						<TH width="5%">Vol.
						<TH width="5%">Nr.
						<TH width="5%">Artigos
						<TH width="5%">Citações concedidas
						<TH width="5%">Citações recebidas
						<TH width="5%">média art./fasc.
						<TH width="5%">média ref./artigos';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="index_edition_journal.php?dd0='.$line['ed_codigo'].'&dd1='.$jid.'" target="_new_issue">';
					$n1 = $line['db_fasciculo'];
					$n2 = $line['db_artigos'];
					$m2 = $line['db_cited_concedidas'];
					$sx .= '<TR>';
					$sx .= '<TD align="center" class="tabela01">'.$line['jnl_issn_impresso'];
					$sx .= '<TD align="left" class="tabela01">'.$line['jnl_nome'];
					$sx .= '<TD align="center" class="tabela01">'.$link.$line['db_ano'].'</A>';
					$sx .= '<TD align="center" class="tabela01">'.$link.$line['ed_vol'].'</A>';
					$sx .= '<TD align="center" class="tabela01">'.$link.$line['ed_nr'].'</A>';
					
					$sx .= '<TD align="center" class="tabela01">'.$line['db_artigos'];
					$sx .= '<TD align="center" class="tabela01">'.$line['db_cited_concedidas'];
					$sx .= '<TD align="center" class="tabela01">'.$line[''];
					$media = '-';
					if ($n1 > 0) 
						{
							 $media = $n2 / $n1;
							 $media = number_format($media,1,',','.');
						}
					$sx .= '<TD align="center" class="tabela01">'.$media;

					$media = '-';
					if ($n1 > 0) {
						$media = $m2 / $n2;
						$media = number_format($media,1,',','.'); 
						}
					$sx .= '<TD align="center" class="tabela01">'.$media;
				}
			$sx .= '</table>';
			return($sx);
		}

	function journal_anos($jid)
		{
			$sql = "select * from 
					(
						SELECT count(*) as fasciculo, db_ano, sum(db_artigos) as db_artigos,
							sum(db_cited_concedidas) as db_cited_concedidas, 
							db_journal
					    	FROM bris_data 
					    	group by db_journal, db_ano) as tabela
					inner join brapci_journal on db_journal = jnl_codigo
					where db_journal = '".strzero($jid,7)."'
					order by db_ano desc
			";
			$rlt = db_query($sql);
			$sx = '<table width="100%">';
			$sx .= '<TR class="lt0"><TH width="10%">ISSN
						<TH width="65%">Journal Name
						<TH width="5%">Ano
						<TH width="5%">Fasciculos
						<TH width="5%">Artigos
						<TH width="5%">Citações concedidas
						<TH width="5%">Citações recebidas
						<TH width="5%">média art./fasc.
						<TH width="5%">média ref./artigos';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="index_issue_journal.php?dd0='.$line['db_ano'].'&dd1='.$jid.'" target="_new_issue">';
					$n1 = $line['db_fasciculo'];
					$n2 = $line['db_artigos'];
					$m2 = $line['db_cited_concedidas'];
					$sx .= '<TR>';
					$sx .= '<TD align="center" class="tabela01">'.$line['jnl_issn_impresso'];
					$sx .= '<TD align="left" class="tabela01">'.$line['jnl_nome'];
					$sx .= '<TD align="center" class="tabela01">'.$link.$line['db_ano'].'</A>';
					
					$sx .= '<TD align="center" class="tabela01">'.$line['fasciculo'];
					$sx .= '<TD align="center" class="tabela01">'.$line['db_artigos'];
					$sx .= '<TD align="center" class="tabela01">'.$line['db_cited_concedidas'];
					$sx .= '<TD align="center" class="tabela01">'.$line[''];
					$media = '-';
					if ($n1 > 0) 
						{
							 $media = $n2 / $n1;
							 $media = number_format($media,1,',','.');
						}
					$sx .= '<TD align="center" class="tabela01">'.$media;

					$media = '-';
					if ($n1 > 0) {
						$media = $m2 / $n2;
						$media = number_format($media,1,',','.'); 
						}
					$sx .= '<TD align="center" class="tabela01">'.$media;
				}
			$sx .= '</table>';
			return($sx);
		}
	
	function journal_fasciculos_insert($jid)
		{
			$sql = "select ar_edition as fasciculos, sum(total) as artigos, ed_ano
					from (
						select count(*) as total, ar_edition, ed_ano
							from brapci_article
						inner join brapci_edition on ar_edition = ed_codigo
						where ar_journal_id = '".strzero($jid,7)."' and
							(ar_status <> 'X') and
							((ar_tipo is null) or ar_tipo = '' or ar_tipo = 'ARTIG' or ar_tipo = 'REVIS' or ar_tipo = 'COMUN' or ar_tipo = 'RELAT')
						group by ar_edition, ed_ano
					) as tabela
					group by ed_ano, fasciculos
					order by ed_ano desc
					"; 
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$ano = $line['ed_ano'];
					$fas = $line['fasciculos'];
					$art = $line['artigos'];
					$jou = strzero($jid,7);
					$this->journal_data($ano,$fas,$art,$jou);
				}
				
			$sql = "
				SELECT count(*) as refs, ed_ano, ar_edition
				FROM brapci_article
					left join mar_works on m_work = ar_codigo
					inner join brapci_edition on ar_edition = ed_codigo
					WHERE ar_journal_id = '".strzero($jid,7)."' and m_status <> 'X'
				group by ed_ano, ar_edition		
			";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$ano = $line['ed_ano'];
					$art = $line['refs'];
					$jou = strzero($jid,7);
					$edition = $line['ar_edition'];
					$this->journal_citacoes($ano,$jou,$art,$edition);
				}
					}
		
	function journal_citacoes($ano, $jid, $refs = 0, $edition)
		{
			$sql = "select * from bris_data 
					where db_journal = '$jid' and db_ano = '$ano' and db_fasciculo = '$edition'";
			$rlt = db_query($sql);
			$data = date("Ymd");
			if ($line = db_read($rlt))
				{
					$sql = "update bris_data set 
								db_cited_concedidas = ".$refs."
							where id_db = ".$line['id_db'];
							$rlt = db_query($sql);
				}
			return(1);
		}
		
	function journal_data($ano, $fasciculo, $artigos, $jid, $refs = 0)
		{
			$sql = "select * from bris_data 
					where db_journal = '$jid' and db_ano = '$ano' and db_fasciculo = '$fasciculo' ";
			$rlt = db_query($sql);
			$data = date("Ymd");
			if ($line = db_read($rlt))
				{
					$sql = "update bris_data set 
								db_artigos = '".$artigos."',
								db_update = ".$data."
							where id_db = ".$line['id_db'];
				} else {
					$sql = "insert into bris_data
							(
								db_journal, db_ano, db_fasciculo,
								db_artigos, db_update, db_cited_concedidas
							) values (
								'$jid','$ano','$fasciculo',
								'$artigos','$data','0'
							)
					";
				}
				$rlt = db_query($sql);
				//echo '<HR>'.$sql;
		}
	function journal_ranking($ano=2014)
		{
			$sx .= '<table>';
			$sx .= '<TR>';
			$sx .= '<TH>ISSN';
			$sx .= '<TH>Journal name';
			$sx .= '<TH class="lt0">Cidade/Região';
			$sx .= '<TH width="5%" class="lt0">Citações<BR>Recebidas';
			$sx .= '<TH width="5%" class="lt0">Fator de Impacto<BR>2 anos';
			$sx .= '<TH width="5%" class="lt0">Fator de Impacto<BR>3 anos';
			$sx .= '<TH width="5%" class="lt0">Fator de Impacto<BR>5 anos';
			$sx .= '<TH width="5%" class="lt0">Impacto Imediato';
			$sx .= '<TH width="5%" class="lt0">Índice h';
			$sx .= '<TH width="5%" class="lt0">Auto citação';
			$sx .= '<TH width="5%" class="lt0">Vida Média';
			
			$sql = "select * from brapci_journal
					left join bris_rank on rk_issn = jnl_issn_impresso 
					left join ajax_cidade on jnl_cidade = cidade_codigo
					where jnl_tipo = 'J'
					order by jnl_nome 
				";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="index_about_journal.php?dd0='.$line['id_jnl'].'" target="_new">';
					
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $line['jnl_issn_impresso'];
					$sx .= '<TD class="tabela01">';
					$sx .= $link.$line['jnl_nome'].'</A>';
					
					$sx .= '<TD class="tabela01">';
					$sx .= $line['cidade_nome'];

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_artigos'],0,',','.');

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_cited'],0,',','.');

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_fi2'],4,',','.');

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_fi3'],4,',','.');

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_fi5'],4,',','.');

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_h'],0,',','.');

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= number_format($line['rk_aa'],0,',','.');
										
				}
			
			
			$sx .= '</table>';
			return($sx);
		}
		
	function grupos_icpa_mostra($ano,$tipo)
		{
			$sql = "select * from bris_icpa 
						inner join brapci_autor on icpa_autor = autor_codigo
						where icpa_ano = '$ano'
						and icpa_indice = '$tipo' 
					order by autor_nome ";
			$tot = 0;
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$tot++;
					$link = '<A HREF="author_view.php?dd0='.$line['autor_codigo'].'" target="_new">';
					$sx .= '<BR>'.$link.$line['autor_nome'].'</A>';
				}
			$sx .= '<BR><i>Total '.$id.'</i>';
			return($sx);
		}
	function mostra_icpa($autor='')
		{
			$sql = "select * from bris_icpa where icpa_autor = '$autor' order by icpa_ano limit 1";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$id = $line['icpa_indice'];
			$this->autor_icpa = $id;
			switch ($id)
				{
				case '0': $sx .= '<TD bgcolor="white" align="center">NC</TD>'; break;
				case '1': $sx .= '<TD bgcolor="#C0FFC0" align="center">BAIXO</TD>'; break;
				case '2': $sx .= '<TD bgcolor="#C0C0FF" align="center">MÉDIO</TD>'; break;
				case '3': $sx .= '<TD bgcolor="#FFC0C0" align="center">ALTO</TD>'; break;
				}
			return($sx);
		}	
	
	function grupos_icpa($ano='')
		{
			if (strlen($ano) == 0)
				{
					$sql = "select max(icpa_ano) as ano from bris_icpa ";
					$rlt = db_query($sql);
					$line = db_read($rlt);
					$ano = $line['ano'];
				}
			$sql = "select count(*) as total, sum(icpa_artigos) as artigos, icpa_indice 
							from bris_icpa 
							group by icpa_indice 
							order by icpa_indice ";
			$rlt = db_query($sql);
			$icpa = array(0,0,0,0);
			$icpap = array(0,0,0,0);
			$tot1 = 0;
			$tot2 = 0;
			while ($line = db_read($rlt))
				{
					$tp = round($line['icpa_indice']);
					$icpa[$tp] = $icpa[$tp] + $line['artigos'];
					$icpap[$tp] = $icpap[$tp] + $line['total'];
					$tot1 = $tot1 + $line['artigos'];
					$tot2 = $tot2 + $line['total'];
				}
			/* totais */
			$sx = '<table width="100%">';
			$sx .= '<TR>
					<th WIDTH="20%">iCPA
					<Th width="15%" colspan=2>NC</th>
					<Th width="15%" colspan=2>BAIXO</th>
					<Th width="15%" colspan=2>MÉDIO</th>
					<Th width="15%" colspan=2>ALTO</th>
					<Th width="20%">TOTAL</th>';

			$sx .= '<TR>
						<TD><B>Autores</B></TD>
						<td align="center" class="tabela01">'.number_format($icpap[0]).'</TD>
						<td align="center" class="tabela01">'.number_format(100*($icpap[0]/$tot2),1,',','.').'%</TD>
						
						<td align="center" class="tabela01">'.number_format($icpap[1]).'</TD>
						<td align="center" class="tabela01">'.number_format(100*($icpap[1]/$tot2),1,',','.').'%</TD>
						
						<td align="center" class="tabela01">'.number_format($icpap[2]).'</TD>
						<td align="center" class="tabela01">'.number_format(100*($icpap[2]/$tot2),1,',','.').'%</TD>
						
						<td align="center" class="tabela01">'.number_format($icpap[3]).'</TD>
						<td align="center" class="tabela01">'.number_format(100*($icpap[3]/$tot2),1,',','.').'%</TD>
						
						<td align="center" class="tabela01">'.number_format($tot2).'</TD>
			';

			$linka = '<A HREF="indicador_icpa_detalhe.php?dd0='.$ano.'&dd1=0" class="link lt0">detalhes</A>';
			$linkb = '<A HREF="indicador_icpa_detalhe.php?dd0='.$ano.'&dd1=1" class="link lt0">detalhes</A>';
			$linkc = '<A HREF="indicador_icpa_detalhe.php?dd0='.$ano.'&dd1=2" class="link lt0">detalhes</A>';
			$linkd = '<A HREF="indicador_icpa_detalhe.php?dd0='.$ano.'&dd1=3" class="link lt0">detalhes</A>';
			$sx .= '<TR>
						<TD>&nbsp;</TD>
						<td align="center" colspan=2></TD>						
						<td align="center" colspan=2>'.$linkb.'</TD>						
						<td align="center" colspan=2>'.$linkc.'</TD>						
						<td align="center" colspan=2>'.$linkd.'</TD>
			';
			$sx .= '</table>';
			return($sx);
		}
	
	function verificar_artigo_sem_ano()
		{
			$total = 0;
			$sql = "select count(*) as total from brapci_article 
						where (ar_ano = 0 or ar_ano is null)
							and ar_status <> 'X'
						";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$total = $line['total'];
				}
			return($total);
		}
	function calcula_icpa($ano=1900)
		{
			$total = $this->verificar_artigo_sem_ano();
			if ($total > 0)
				{
					echo '<font color="err">Existe Artigos sem ano</font>'; exit;
				}

			/* excluir dados do ano base */
			$sql = "delete from bris_icpa where icpa_ano = ".$ano;
			$rrr = db_query($sql);

			/* Calcula dispersão dos autores */
			$sql = "select max(art) as maximo, sum(art) as artigos, ae_author from (
						select count(*) as art, ae_journal_id, ae_author from brapci_article_author 
						inner join brapci_article on ae_article = ar_codigo
						where ar_ano <= $ano and ar_status <> 'X' and 
							(ar_section <> 'EDITO' and ar_section <> 'RESEN')
						group by ae_journal_id, ae_author
						) as tabela 
						group by ae_author
						order by maximo desc, ae_author, artigos desc
			";
			$rlt = db_query($sql);
			$sx = '';
			$idt = 0;
			$idz = 0;
			$min = 10;
			while ($line = db_read($rlt))
				{
				$idt++;
				$autor = trim($line['ae_author']);
				$max = trim($line['maximo']);
				$art = trim($line['artigos']);

				$idc = (int)(100 * ($max / $art));
				$id = 0;
				if ($idc <= 35)  { $id = 1; }
				if ($idc > 35) { $id = 2; }
				if ($idc > 50) { $id = 3; }
				if ($art < $min) { $id = 0; $idz++; }
				
				$sql = "insert into bris_icpa 
						(icpa_autor, icpa_ano, icpa_revistas, icpa_artigos, icpa_indice )
						values 
						('$autor','$ano','$rev','$art','$id')			
					";
					$xxx = db_query($sql);
				}	
				$sx .= '<BR>Gerado '.$idt.' registros, '.$idz.' com menos de 3 artigos';
				$sx .= '<BR><BR>';
				return($sx);			
		}	
	}
?>
