<?php

class export	
	{
		var $sql;
		var $ini=0;
		var $total=0;
		
		function total_trabalhos()
			{
				global $db_public;
				$sql = "select count(*) as total from brapci_article 
							where ar_status <> 'X'
				";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$total = $line['total'];
				return($total);				
			}
		
		function zera_public()
			{
				global $db_public;
				$sql = "delete from ".$db_public."artigos ";
				$rlt = db_query($sql);				
			}
		function export_public($ini,$max)
			{
				global $dd,$db_public,$db_apoio;
				$sql = "alter table ".$db_public."artigos add ar_tipo char(1) ";
				//$rlt = db_query($sql);
				
				$sql = "alter table ".$db_public."artigos add ar_ref text ";
				//$rlt = db_query($sql);

				/* Cabeçalho Padrão */
				$sqli = "INSERT INTO ".$db_public."artigos ( ";
				$sqli .= "ar_asc_mini, ar_asc, Author_Analytic , Author_Role ";
				$sqli .= ", Author_Affiliation , Article_Title , Medium_Designator , ";
				$sqli .= "Author_2 , Journal_Title , Title_2 , ";
				$sqli .= "Reprint_Status , Date_Publication , Volume_ID ";
				$sqli .= ", Issue_ID , Pages , Idioma , ";
				$sqli .= "Availability , URL , ISSN ";
				$sqli .= ", Notes , Abstract , Call_Number ";
				$sqli .= ", Keywords , ar_codigo , ar_tipo , ";
				$sqli .= "ar_doi , ar_titulo_1 , ar_titulo_2 , ";
				$sqli .= "ar_resumo_1 , ar_resumo_2 , ar_section , ";
				$sqli .= "ar_journal_id , ar_keyword_1 , ar_keyword_2, ";
				$sqli .= "ar_ano, ar_vol, ar_nr, ";
				$sqli .= "ar_local, ar_ref ";
				$sqli .= " ) values ";
				
				$sqls = '';
				
				$sql = "select '' as autor,  ";
				$sql .= "'' as AuthorRole, ";
				$sql .= "'' as AuthorAffiliation ,";
				$sql .= "ar_titulo_1 as ArticleTitle,";
				$sql .= "ar_tipo as MediumDesignator,";

				$sql .= "'' as Author2,";
				$sql .= "jnl_nome as JournalTitle,";
				$sql .= "cidade_nome as ar_local,";
				$sql .= "'' as Title2,";

				$sql .= "'' as ReprintStatus,";
				$sql .= "'' as DatePublication,";
				$sql .= "'' as VolumeID,";

				$sql .= "'' as IssueID,";
				$sql .= "'' as Pages,";
				$sql .= "ar_idioma_1 as Idioma,";

				$sql .= "'' as Availability,";
				$sql .= "'' as URL,";
				$sql .= "jnl_issn_impresso as ISSN,";

				$sql .= "'' as Notes,";
				$sql .= "ar_resumo_1 as Abstract,";
				$sql .= "'' as Call_Number,";

				$sql .= "'' as Keywords,";
				$sql .= "ar_codigo as ar_codigo,";
				$sql .= "ar_tipo as ar_tipo,";

				$sql .= "ar_bdoi as ar_doi,";
				$sql .= "ar_titulo_1 as ar_titulo_1,";
				$sql .= "ar_titulo_2 as ar_titulo_2,";

				$sql .= "ar_resumo_1 as ar_resumo_1,";
				$sql .= "ar_resumo_2 as ar_resumo_2,";
				$sql .= "se_codigo as ar_section,";

				$sql .= "ar_journal_id as ar_journal_id,";
				$sql .= "'' as ar_keyword_1,";
				$sql .= "'' as ar_keyword_2,";

				$sql .= "ar_pg_inicial, ar_pg_final, ";

				$sql .= "se_tipo,";
				$sql .= "ed_vol as ed_vol,";
				$sql .= "ed_nr as ed_num,";
				$sql .= "ed_ano as ed_ano,";
				$sql .= "ed_periodo as ed_periodo, ";
				$sql .= "id_ar, se_cod, ";

				$sql .= 'ed_mes_inicial, ed_mes_final, ';
				$sql .= "ar_status as ar_status ";

				$sql .= " FROM brapci_article ";
				$sql .= " left join brapci_journal on ar_journal_id = id_jnl ";
				$sql .= " left join ".$db_apoio."ajax_cidade on jnl_cidade = cidade_codigo ";
				$sql .= " inner join brapci_edition on ed_codigo = ar_edition ";
				$sql .= " left join brapci_section on ar_section = se_codigo ";
				//$sql .= " where ar_status <> 'X' and (se_tipo <> '-' and se_tipo <> 'Z' and se_tipo <> 'E' and se_tipo <> 'H')";
				$sql .= " where ar_status <> 'X' 							
							";
				$sql .= " order by ar_codigo ";
				$sql .= " limit ".($ini+1).", ".$max." ";
				$rlt = db_query($sql);				
				
				/* Iniciar geracao do arquivo de exportacao */
				$sqlq = '';
				$ii = 0;
				while ($line = db_read($rlt))
					{
					$ii++;
					$ini++;
					/* Edião */
					$pag = '';
					$pag_i = $line['ar_pg_inicial'];
					$pag_f = $line['ar_pg_final'];
					if (strlen($pag_i) > 0)
						{ $pag = $pag_i; }
					if (strlen($pag_f) > 0)
						{ $pag = $pag.'-'.$pag_f; }
					if (strlen($pag) > 0) { $pag = 'p. '.$pag; }
					$v = trim($line['ed_vol']);
					$n = trim($line['ed_num']);
					$a = trim($line['ed_ano']);
					$p = trim($line['ed_periodo']);
					$m = '';	
					if (strlen($v) > 0) { $v = 'v. '.$v; }
					if (strlen($n) > 0) { $n = 'n. '.$n; }
					if (strlen($m1) > 0) { $m = nomemes_short($m1); }
					if (strlen($m2) > 0) { $m .= '/'.nomemes_short($m2); }
					if (strlen($m) > 0) { $m = ', '; }
	
					if (strlen($a) > 0) { $a = $m.$a; }
					if (strlen($p) > 0) { $a = $p; }
					/* AUTOR */
					$sqla = "select * from brapci_article_author ";
					$sqla .= " inner join brapci_autor on autor_codigo = ae_author ";
					$sqla .= " where ae_article = '".$line['ar_codigo']."'";
					$sqla .= ' order by ae_pos ';
					$rlta = db_query($sqla);
					$aut1 = '';
					$aut2 = '';
					while ($aline = db_read($rlta))
						{
						if (strlen($aut1) > 0) { $aut1 .= '; '; }
						$aut1 .= trim($aline['autor_nome_citacao']); 
						if (strlen($aut2) > 0) { $aut2 .= '; '; }
						$aut2 .= qualificacao($aline); 
						}
	
					/* KEYWORDS */
					$sqla = "select * from brapci_article_keyword ";
					$sqla .= " inner join brapci_keyword on kw_codigo = kw_keyword ";
					$sqla .= " where kw_article = '".$line['ar_codigo']."'";
					$sqla .= " order by kw_idioma,  kw_ord ";
					$rlta = db_query($sqla);
					$key1 = '';
					while ($aline = db_read($rlta))
						{
						if (strlen($key1) > 0) { $key1 .= ' / '; }
						$key1 .= trim($aline['kw_word']); 
						}
	
					/* LINKS */
					$sqla = "select * from brapci_article_suporte ";
					$sqla .= " where bs_article = '".$line['ar_codigo']."' ";
					$sqla .= " and bs_status <> 'X' order by bs_type ";
					$rlta = db_query($sqla);
					$links = '';
					$lnk = 0;
					$chx = '';
					if ($aline = db_read($rlta))
						{
						if ((trim($aline['bs_type']) == 'PDF') and ($lnk == 0))
							{ $lnk = 1; $links .= '<A HREF="download.php?dd0='.$aline['id_bs'].'" ><B><font color="blue">download PDF</font></B></A>&nbsp;'; }
						if ((trim($aline['bs_type']) == 'URL') and ($lnk == 0))
							{ $lnk = 2; $links .= '<A HREF="'.trim($aline['bs_adress']).'" target="_new'.date("mis").'" ><B><font color="blue">link externo</font></B></A>&nbsp;'; }
						}
					/* */
					if (strlen($sqlq) > 0) { $sqlq .= ', '; }
						$sqlq .= "(";
						$sqlq .= "'".UpperCaseSql($aut1.' '.trim($line['JournalTitle']).' '.trim($line['ar_titulo_1']).' '.trim($line['ar_titulo_2']));
						$sqlq .= UpperCaseSql(' '.trim($line['ed_ano']).' '.trim($line['ed_vol']).' ');
						$sqlq .= UpperCaseSql(trim($line['ed_nr']).' '.trim($line['ar_local']))."',";
			
						$sqlq .= "'".UpperCaseSql($aut1.' '.trim($line['JournalTitle']).' '.trim($line['ar_titulo_1']).' '.trim($line['ar_titulo_2']));
						$sqlq .= UpperCaseSql(trim($line['ar_resumo_1']).' '.trim($line['ar_resumo_2']).' '.trim($line['ed_ano']).' '.trim($line['ed_vol']).' ');
						$sqlq .= UpperCaseSql(trim($line['ed_nr']).' '.trim($line['ar_local']))."',";
		
						$sqlq .= "'".$aut1."',";
		
						$sqlq .= "'".$line['AuthorRole']."',";
						$sqlq .= "'".$line['AuthorAffiliation']."',";
						$sqlq .= "'".$line['ArticleTitle']."',";
						$sqlq .= "'".$line['MediumDesignator']."',";
		
						$sqlq .= "'".$line['Author2']."',";
						$sqlq .= "'".$line['JournalTitle']."',";
						$sqlq .= "'".$line['Title2']."',";
	
						$sqlq .= "'".$line['ReprintStatus']."',";
				//		$sqlq .= "'".$line['DatePublication']."',";
						$sqlq .= "'".$a."',";
						$sqlq .= "'".$v."',";
	
						$sqlq .= "'".$n."',";
						$sqlq .= "'".$pag."',";
						$sqlq .= "'".$line['Idioma']."',";
		
						$sqlq .= "'".$line['Availability']."',";
						$sqlq .= "'".$links."',";
						$sqlq .= "'".$line['ISSN']."',";
	
						$sqlq .= "'".$line['Notes']."',";
						$sqlq .= "'".$line['Abstract']."',";
						$sqlq .= "'".$line['Call_Number']."',";
	
						$sqlq .= "'".$key1."',";
						$sqlq .= "'".$line['ar_codigo']."',";
						$sqlq .= "'".$line['ar_tipo']."',";
	
						$sqlq .= "'".$line['ar_doi']."',";
						$sqlq .= "'".$line['ar_titulo_1']."',";
						$sqlq .= "'".$line['ar_titulo_2']."',";
					
						$res = trim($line['ar_resumo_1']);
						if (strlen($res) < 10)
							{
								$sqlq .= "'".$line['ar_resumo_2']."',";
								$sqlq .= "'".$line['ar_resumo_1']."',";
							} else {
								$sqlq .= "'".$line['ar_resumo_1']."',";
								$sqlq .= "'".$line['ar_resumo_2']."',";	
							}
						$sqlq .= "'".$line['se_cod']."',";
		
						$sqlq .= "'".$line['ar_journal_id']."',";
						$sqlq .= "'".$key1."',";
						$sqlq .= "'".$line['ar_keyword_1']."',";
	
						$sqlq .= "'".$line['ed_ano']."',";
						$sqlq .= "'".$line['ed_vol']."',";
						$sqlq .= "'".$line['ed_nr']."',";

						$sqlq .= "'".$line['ar_local']."',";
										
						$sqlq .= "'".$ref."'";
						$sqlq .= ")";

					if ($lnk == 8) {
						echo $sqlq;
						exit;
						}
				}
				if ($ii == 0) { $sqli = ''; $sqlq = ''; }	
				return($sqli.$sqlq);							
			}
	}
?>