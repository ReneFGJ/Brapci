<?php
// This file is part of the Brapci Software. 
// 
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software. 
// 
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt 
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */
class export extends CI_model {
	var $sql;
	var $ini = 0;
	var $total = 0;
	
	function export_author()
		{
			$sql = "select autor_nome, autor_codigo, autor_alias from brapci_autor 
						WHERE autor_nome <> '' and autor_codigo = autor_alias
						ORDER BY autor_nome ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			/*************** FILE ***************/
			//$flt = fopen('_search/_autor.txt','w+');
			$flt = fopen('js/autor_complete.js','w+');
			/*************** FILE ***************/
			$scr = 'var availableTags = {'.cr();	
			fwrite($flt,$scr);
			$sx = '';
			$ix = array();
			
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$txt = $line['autor_alias'].'|'.$line['autor_codigo'].'|'.trim($line['autor_nome']);
					$txt = '"'.trim($line['autor_nome']).'",';
					$txt = uppercasesql($txt);
					$ttt = UpperCaseSql(troca(trim($line['autor_nome']),',',' '));
					$ttt = troca($ttt,' ',';');
					if (strlen($ttt) > 2)
					{
					$lnt = splitx(';',$ttt.';');
					for ($q=0;$q < count($lnt);$q++)
						{
							$fld = $lnt[$q];
							if (strlen($fld) > 1)
								{
								if (!(isset($ix[$fld])))
									{
									$ix[$fld] = 'ok';
									}
								}
						}
					fwrite($flt,'"'.$line['autor_codigo'].'" :'.$txt.cr());
					}
					$sx .= '<tt>'.'#'.strzero($r,5).' - '.$txt.'</tt><br>'.cr();
				}
			$scr = '    }'.cr();
			fwrite($flt,$scr);
			fclose($flt);
			
			/* indice de autores */
			$fff = fopen('_search/_autor_index.txt','w+');
			foreach ($ix as $key => $value) {
				fwrite($fff,$key.cr());
			}
			fclose($fff);
			return($sx);
		}

	function export_keywords()
		{
			$sql = "select kw_word, kw_codigo, kw_use from brapci_keyword 
						WHERE kw_word <> '' and kw_codigo = kw_use AND kw_idioma = 'pt_BR'
						ORDER BY kw_word ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			/*************** FILE ***************/
			$flt = fopen('js/keywords_complete.js','w+');
			/*************** FILE ***************/
			$scr = 'var keysTags = {'.cr();	
			fwrite($flt,$scr);
			$sx = '';
			$ix = array();
			
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$txt = $line['kw_use'].'|'.$line['kw_codigo'].'|'.trim($line['kw_word']);
					$txt = '"'.trim(troca($line['kw_word'],'"','´')).'",';
					$txt = uppercasesql($txt);
					$ttt = UpperCaseSql(troca(trim($line['kw_word']),',',' '));
					$ttt = troca($ttt,' ',';');
					if (strlen($ttt) > 2)
					{
					$lnt = splitx(';',$ttt.';');
					for ($q=0;$q < count($lnt);$q++)
						{
							$fld = $lnt[$q];
							if (strlen($fld) > 1)
								{
								if (!(isset($ix[$fld])))
									{
									$ix[$fld] = 'ok';
									}
								}
						}
					$txt = troca($txt,chr(13),' ');
					$txt = troca($txt,chr(10),' ');
					$txt = troca($txt,'/','-');
					fwrite($flt,'			"'.$line['kw_codigo'].'" :'.$txt.cr());
					}
					$sx .= '<tt>'.'#'.strzero($r,5).' - '.$txt.'</tt><br>'.cr();
				}
			$scr = '    }'.cr();
			fwrite($flt,$scr);
			fclose($flt);
			
			/* indice de autores */
			$fff = fopen('_search/_keywords_index.txt','w+');
			foreach ($ix as $key => $value) {
				fwrite($fff,$key.cr());
			}
			fclose($fff);
			return($sx);
		}

	function exporta_texto($pg) {
		global $db_public;
        $i = 0;
        $limit = 10;
        $offset = $pg*$limit;
        
        /************************* JOURNAL */
        $sql = "select * from brapci_journal 
                    WHERE jnl_status = 'A' or jnl_status = 'B'
                    ORDER BY jnl_tipo, jnl_nome                     
                    ";
        $rlt = $this->db->query($sql);
        $rlt = $rlt->result_array();
        $sq = '';
        $ok = 0;
        for ($r=0;$r < count($rlt);$r++)
            {
                $line = $rlt[$r];
                $sq .= $line['jnl_codigo'].':'.trim($line['jnl_nome']).chr(13).chr(10);
            }
        $fla = fopen('_search/journals.htm','w');
        fwrite($fla,$sq);
        fclose($fla);           
                    
        /************************* JOURNAL */
        $sql = "select * from brapci_section 
                    WHERE (se_tipo = 'A' or se_tipo = 'B') and (se_ativo = 1)
                    ORDER BY se_ordem, se_descricao ";
        $rlt = $this->db->query($sql);
        $rlt = $rlt->result_array();
        $sq = '';
        for ($r=0;$r < count($rlt);$r++)
            {
                $line = $rlt[$r];
                $sq .= $line['se_codigo'].':'.trim($line['se_descricao']).chr(13).chr(10);
            }
        $fla = fopen('_search/sections.htm','w');
        fwrite($fla,$sq);
        fclose($fla);           
        
        
        echo "OK";
        exit;
        
		$cp = ' * ';
		$sql = "select $cp from " . $db_public . "artigos ";
		$sql .= " order by ar_ano desc, ID desc, ar_titulo_1, ar_vol, ar_nr 
		          LIMIT $limit OFFSET $offset";
		//$sql .= " limit 200";

		$query = $this -> db -> query($sql);
		$sf = '';
		$sa = '';
		$txt = '';

		/* Tipo 1 - titulos, Resumo, Palavra-chaves, Autores */

		$flq = fopen('search-'.strzero($pg,4).'.txt', 'w+');

		/* */
		$row = $query -> result_array();
		$m1 = '';
		$m2 = '';
		$tote = 0;
			
		/* Articles */
		for ($r = 0; $r < count($row); $r++) {
			$tote++;
            $ok = 1;
			//$row = $row[$r];
			$line = $row[$r];
            $cod = trim($line['ar_codigo']);

			$st = ' ';

			$sq =  '[['.(trim(($line['ar_codigo']))).']]';
			$sq .= '['.(trim(($line['ar_titulo_1']))).']';
			$sq .= '['.(trim(($line['ar_titulo_2']))).']';
			$sq .= '['.(trim(($line['ar_resumo_1']))).']';
			$sq .= '['.(trim(($line['ar_resumo_2']))).']';
			$sq .= '['.(trim(($line['Journal_Title']))).']';
			$sq .= '['.(trim(($line['Keywords']))).']';
			$sq .= '['.(trim(($line['ar_keyword_2']))).']';
			$sq .= '['.(trim(($line['Author_Analytic']))).']';
			$sq .= '[J'.(trim(($line['ar_journal_id']))).']';
            $sq .= '[S'.(trim(($line['Medium_Designator']))).']';
            $sq .= '[Y'.(trim(($line['ar_ano']))).']';
			$sq = troca($sq,chr(13),' ');
			$sq = troca($sq,chr(10),'');	
			
			//print_r($line);
            /**************************************************** JOURNAL *********/
            $jnl = trim(trim(($line['Journal_Title'])));
            $nro = trim($line['ar_nr']);
            $vol = trim($line['ar_vol']);
            $ano = trim($line['ar_ano']);
            if (strlen($nro.$vol.$ano) > 0) { $jnl .= ', '; }
            if (strlen($nro) > 0) { $jnl .= 'n. '.$nro; }
            if (strlen($vol) > 0) { $jnl .= ', v. '.$vol; }
            if (strlen($nro) > 0) { $jnl .= ', '.$ano; }
            
            $abs = trim($line['ar_resumo_1']);
            $abs = troca($abs,chr(13),' ');
            $abs = troca($abs,chr(10),' '); 
            
            $link = '<a href="$url/'.$cod.'" class="css_title">';
            $linka = '</a>';
            
            $fl = '<div id="ard'.$cod.'" class="css css_article">'.cr();
            $fl .= '    <div class="css_check">';
            $fl .= '        <input type="checkbox" id="ca'.$line['ar_codigo'].'"  onchange="basket(\''.$line['ar_codigo'].'\')">';
            $fl .= '    </div>'.cr();
            $fl .= '    <div class="css_title">'.$link.trim($line['ar_titulo_1']).$linka.'</div>'.cr();
            $fl .= '    <div class="css_author">'.trim(($line['Author_Analytic'])).'</div>'.cr(); 
            $fl .= '    <div class="css_journal">'.$jnl.'.</div>'.cr();                      
            $fl .= '    <div id="rr'.$line['ar_codigo'].'" class="css_resumo css">ver resumo</div>'.cr();                    
            $fl .= '    <div id="rs'.$line['ar_codigo'].'" class="text-justify css_abstract" style="display: none;">'.cr();
            $fl .= '        '.$abs.cr();
            if (strlen($line['ar_keyword_1'])>0)
                {            
                    $fl .= '        <br>Palavras-chaves: <b>'.trim(($line['ar_keyword_1'])).'</b>'.cr();
                }
            $fl .= '    </div>'.cr();                
            $fl .= '</div>'.cr();  
            $dir = substr($cod,0,7);
            if (!is_dir('_search/'.$dir))
                {
                    mkdir('_search/'.$dir);        
                }
            
            $fln = fopen('_search/'.$dir.'/'.$cod.'.htm','w');
            fwrite($fln,$fl);
            fclose($fln); 
            
            $tela = $this->load->view('article/article_view_export',$line,true);
            $fln = fopen('_search/'.$dir.'/'.$cod.'ar.htm','w');
            fwrite($fln,$tela);
            fclose($fln);             
            //echo $fl;
            
            //if (($i++) > 10) { return(''); }                 
			
			$sq = utf8_decode($sq);	
			$sq = iconv("ISO-8859-1", "ASCII//TRANSLIT", $sq);			
			$sq = troca($sq,"'",'');
			$sq = troca($sq,"`",'');
			$sq = troca($sq,"´",'');
			$sq = troca($sq,".",' ');
			$sq = troca($sq,";",' ');
			$sq = troca($sq,",",' ');
			$sq = troca($sq,'"',' ');
			$sq = troca($sq,'?',' ');
			$sq = troca($sq,'!',' ');			
			$sq = troca($sq,"~",'');
			$sq = troca($sq,"^",'');
			$sq = troca($sq,"/",' ');
			$sq = troca($sq,",",' ');
			$sq = troca($sq,")",' ');
			$sq = troca($sq,"(",' ');
			$sq = troca($sq,"  ",' ');
			$sq = troca($sq,"  ",' ');
			$sq = troca($sq,"  ",' ');
			$sq = troca($sq,"£",'');
			$sq = strtolower($sq);
			
			$st .= $sq.cr();
			
			//$txt = $st . chr(13) . chr(10);
			fwrite($flq, $st);
		}
		fclose($flq);
        return($ok);
	}


	function total_trabalhos() {
		global $db_public;
		$sql = "select count(*) as total from brapci_article 
							where ar_status <> 'X'
				";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$total = $line['total'];
		return ($total);
	}

	function zera_public() {
		global $db_public;
		$sql = "delete from " . $db_public . "artigos where 1=1 ";
		$query = $this -> db -> query($sql);
		
		$sql = "TRUNCATE " . $db_public . "artigos ";
		$query = $this -> db -> query($sql);
	}

	function export_public($ini, $max) {
		global $dd, $db_public, $db_apoio;
		$sql = "update brapci_article set ar_status = '@' where ar_status is null ";
		//$rlt = db_query($sql);

		/* Cabecalho Padrao */
		$sqli = "INSERT INTO " . $db_public . "artigos ( ";
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
		$sqli .= "ar_local, ar_ref, ";
        $sqli .= "ar_linguage_1, ar_linguage_2, ";
        $sqli .= 'ar_secao';
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
        $sql .= "ar_idioma_1 as idioma_1,";
        $sql .= "ar_idioma_2 as idioma_2,";

		$sql .= "'' as Availability,";
		$sql .= "'' as URL,";
		$sql .= "jnl_issn_impresso as ISSN,";

		$sql .= "'' as Notes,";
		$sql .= "ar_resumo_1 as Abstract,";
		$sql .= "jnl_tipo as Call_Number,";

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
		$sql .= 'se_descricao, ';
		$sql .= "ar_status as ar_status, ";
		$sql .= "se_descricao as ar_secao ";

		$sql .= " FROM brapci_article ";
		$sql .= " left join brapci_journal on ar_journal_id = id_jnl ";
		$sql .= " left join " . $db_apoio . "ajax_cidade on jnl_cidade = cidade_codigo ";
		$sql .= " inner join brapci_edition on ed_codigo = ar_edition ";
		$sql .= " left join brapci_section on ar_section = se_codigo ";
		$sql .= " where ar_status <> 'X' and (
							se_tipo <> '-' and 
							se_tipo <> 'Z' and 
							se_tipo <> 'H'
							)";
		//$sql .= " and ar_codigo = '0000000506' ";
		
		$sql .= " order by ar_codigo ";
		$sql .= " limit " . ($ini + 1) . ", " . $max . " ";
		$query = $this -> db -> query($sql);

		/* Iniciar geracao do arquivo de exportacao */
		$sqlq = '';
		$ii = 0;

		/* */
		$row = $query -> result_array();
		$m1 = '';
		$m2 = '';
		$tote = 0;
		for ($r = 0; $r < count($row); $r++) {
			$tote++;
			//$row = $row[$r];
			$line = $row[$r];

			$ii++;
			$ini++;
			/* Edicao */
			$ref = $line['ar_codigo'];
			$pag = '';
			$pag_i = $line['ar_pg_inicial'];
			$pag_f = $line['ar_pg_final'];
			if (strlen($pag_i) > 0) { $pag = $pag_i;
			}
			if (strlen($pag_f) > 0) { $pag = $pag . '-' . $pag_f;
			}
			if (strlen($pag) > 0) { $pag = 'p. ' . $pag;
			}
			$v = trim($line['ed_vol']);
			$n = trim($line['ed_num']);
			$a = trim($line['ed_ano']);
			$p = trim($line['ed_periodo']);
			$m = '';
			if (strlen($v) > 0) { $v = 'v. ' . $v;
			}
			if (strlen($n) > 0) { $n = 'n. ' . $n;
			}
			if (strlen($m1) > 0) { $m = nomemes_short($m1);
			}
			if (strlen($m2) > 0) { $m .= '/' . nomemes_short($m2);
			}
			if (strlen($m) > 0) { $m = ', ';
			}

			if (strlen($a) > 0) { $a = $m . $a;
			}
			if (strlen($p) > 0) { $a = $p;
			}
            if (strlen($line['se_descricao']) > 0)
                {
                    $a .= ' ('.$line['se_descricao'].')';
                }
                
			/* AUTOR */
			$sqla = "select * from brapci_article_author ";
			$sqla .= " inner join brapci_autor on autor_codigo = ae_author ";
			$sqla .= " where ae_article = '" . $line['ar_codigo'] . "'";
			$sqla .= ' order by ae_pos ';

			$rlta = $this -> db -> query($sqla);
			$rowa = $rlta -> result_array();
			$aut1 = '';
			$aut2 = '';
			for ($ra = 0; $ra < count($rowa); $ra++) {
				$aline = $rowa[$ra];
				//while ($aline = db_read($rlta)) {
				if (strlen($aut1) > 0) { $aut1 .= '; ';
				}
				$aut1 .= trim($aline['autor_nome_citacao']);
				if (strlen($aut2) > 0) { $aut2 .= '; ';
				}
				$aut2 .= qualificacao($aline);
			}

			/* KEYWORDS */
			$sqla = "select * from brapci_article_keyword ";
			$sqla .= " inner join brapci_keyword on kw_codigo = kw_keyword ";
			$sqla .= " where kw_article = '" . $line['ar_codigo'] . "' ";
			//$sqla .= " and kw_idioma = 'en' ";
			$sqla .= " order by kw_idioma desc ,  kw_ord, kw_word ";

			$rlta = $this -> db -> query($sqla);
			$rowa = $rlta -> result_array();

			$key1 = '';
			for ($ra = 0; $ra < count($rowa); $ra++) {
				$aline = $rowa[$ra];
				if (strlen($key1) > 0) { $key1 .= '. ';
				}
				$key1 .= trim($aline['kw_word']);
			}

			/* LINKS */
			$sqla = "select * from brapci_article_suporte ";
			$sqla .= " where bs_article = '" . $line['ar_codigo'] . "' ";
			$sqla .= " and bs_status <> 'X' order by bs_type ";
			$rlta = $this -> db -> query($sqla);
			$rowa = $rlta -> result_array();

			$links = '';
			$lnk = 0;
			$chx = '';

			if (isset($rowa[0])) {
				if ($aline = $rowa[0]) {
					if ((trim($aline['bs_type']) == 'PDF') and ($lnk == 0)) { $lnk = 1;
						$links .= '<A HREF="download.php?dd0=' . $aline['id_bs'] . '" ><B><font color="blue">download PDF</font></B></A>&nbsp;';
					}
					if ((trim($aline['bs_type']) == 'URL') and ($lnk == 0)) { $lnk = 2;
						$links .= '<A HREF="' . trim($aline['bs_adress']) . '" target="_new' . date("mis") . '" ><B><font color="blue">link externo</font></B></A>&nbsp;';
					}
				}
			}
			/* */
			if (strlen($sqlq) > 0) { $sqlq .= ', ';
			}

			$sqlq .= "(";
			$sqlq .= "'" . ($aut1 . ' ' . trim($line['JournalTitle']) . ' ' . trim($line['ar_titulo_1']) . ' ' . trim($line['ar_titulo_2']));
			$sqlq .= (' ' . trim($line['ed_ano']) . ' ' . trim($line['ed_vol']) . ' ');
			$sqlq .= (trim($line['ed_num']) . ' ' . trim($line['ar_local'])) . "',";

			$sqlq .= "'" . ($aut1 . ' ' . trim($line['JournalTitle']) . ' ' . trim($line['ar_titulo_1']) . ' ' . trim($line['ar_titulo_2']));
			$sqlq .= (trim($line['ar_resumo_1']) . ' ' . trim($line['ar_resumo_2']) . ' ' . trim($line['ed_ano']) . ' ' . trim($line['ed_vol']) . ' ');
			$sqlq .= (trim($line['ed_num']) . ' ' . trim($line['ar_local'])) . "',";

			$sqlq .= "'" . $aut1 . "',";

			$sqlq .= "'" . $line['AuthorRole'] . "',";
			$sqlq .= "'" . $line['AuthorAffiliation'] . "',";
			$sqlq .= "'" . $line['ArticleTitle'] . "',";
			$sqlq .= "'" . $line['MediumDesignator'] . "',";

			$sqlq .= "'" . $line['Author2'] . "',";
			$sqlq .= "'" . $line['JournalTitle'] . "',";
			$sqlq .= "'" . $line['Title2'] . "',";

			$sqlq .= "'" . $line['ReprintStatus'] . "',";
			//		$sqlq .= "'".$line['DatePublication']."',";
			$sqlq .= "'" . $a . "',";
			$sqlq .= "'" . $v . "',";

			$sqlq .= "'" . $n . "',";
			$sqlq .= "'" . $pag . "',";
			$sqlq .= "'" . $line['Idioma'] . "',";

			$sqlq .= "'" . $line['Availability'] . "',";
			$sqlq .= "'" . $links . "',";
			$sqlq .= "'" . $line['ISSN'] . "',";

			$sqlq .= "'" . $line['Notes'] . "',";
			$sqlq .= "'" . $line['Abstract'] . "',";
			/* Tipo */
			$tipo = trim($line['Call_Number']);

			switch ($tipo) {
				case 'J' :
					$tipo = '1';
					break;
				case 'E' :
					$tipo = '2';
					break;
				case 'L' :
					$tipo = '3';
					break;
				case 'C' :
					$tipo = '4';
					break;
				case 'D' :
					$tipo = '5';
					break;
				case 'B' :
					$tipo = '6';
					break;
				case 'T' :
					$tipo = '7';
					break;
				case 'A' :
					$tipo = '8';
					break;
				default :
					$tipo = '99';
					break;
			}
			$sqlq .= "'" . $tipo . "',";

			$sqlq .= "'" . $key1 . "',";
			$sqlq .= "'" . $line['ar_codigo'] . "',";
			$sqlq .= "'" . $line['ar_tipo'] . "',";

			$sqlq .= "'" . $line['ar_doi'] . "',";
			$sqlq .= "'" . $line['ar_titulo_1'] . "',";
			$sqlq .= "'" . $line['ar_titulo_2'] . "',";

			$sqlq .= "'" . $line['ar_resumo_1'] . "',";
			$sqlq .= "'" . $line['ar_resumo_2'] . "',";
			$sqlq .= "'" . $line['se_cod'] . "',";

			$sqlq .= "'" . $line['ar_journal_id'] . "',";
			$sqlq .= "'" . $key1 . "',";
			$sqlq .= "'" . $line['ar_keyword_2'] . "',";

			$sqlq .= "'" . $line['ed_ano'] . "',";
			$sqlq .= "'" . $line['ed_vol'] . "',";
			$sqlq .= "'" . $line['ed_num'] . "',";

			$sqlq .= "'" . $line['ar_local'] . "',";

			$sqlq .= "'" . $ref . "',";
            $sqlq .= "'" . $line['idioma_1'] . "',";
            $sqlq .= "'" . $line['idioma_2'] . "',";
            
            $sqlq .= "'" . $line['ar_secao'] . "'";
            
			$sqlq .= ")";

			if ($lnk == 8) {
				echo $sqlq;
				exit ;
			}
		}
		if ($ii == 0) { $sqli = '';
			$sqlq = '';
		}
		$sql = $sqli . $sqlq;
		if ($tote > 0) {
			$query = $this -> db -> query($sql);
		}
		return ($tote);
	}

function resume()
	{
		$sql = "SELECT jnl_tipo, count(*) as total FROM `brapci_journal` 
					where (jnl_status = 'A' or jnl_status = 'B')  
					group by jnl_tipo ";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		$sx = '';
		for ($r=0;$r < count($rlt);$r++)
			{
				$line = $rlt[$r];
				$type = $line['jnl_tipo'];
				$total = $line['total'];
				switch($type)
					{
					case 'J':
						$link = '<a href="'.base_url('index.php/journal').'">';
						$sx .= '<b><span class="big">'.$link.$line['total'].''.'</b> '.'Revistas Científicas</a>'.'</span></br>';
						break;
					}
			}
		$sx .= '<br/>';
		/*************************************** TOTAL DE ARTIGOS *******************/
		$sql = "SELECT count(*) as total, jnl_tipo, jtp_descricao
				FROM `brapci_article` 
				INNER JOIN brapci_journal on ar_journal_id = jnl_codigo
				INNER JOIN brapci_journal_tipo ON jnl_tipo = jtp_codigo
				WHERE (ar_status <> 'X' and ar_status <> '') 
				group by jnl_tipo, jtp_descricao 
				ORDER BY total desc ";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		
		for ($r=0;$r < count($rlt);$r++)
			{
				$line = $rlt[$r];
				$type = $line['jnl_tipo'];
				$total = $line['total'];
				switch($type)
					{
					case 'J':
						$sx .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Trabalhos em Revistas Científicas'.'</br>';
						break;
					case 'E':
						$sx .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Trabalhos em Eventos'.'</br>';
						break;
					case 'A':
						$sx .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Teses'.'</br>';
						break;
					case 'L':
						$sx .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Livros'.'</br>';
						break;						
					case 'D':
						$sx .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Disertações'.'</br>';
						break;						
					case 'C':
						$sx .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Capítulos de livros'.'</br>';
						break;						
											}
			}	
			
	/****************** Autores ****************************************************************************************/
	$sql = "SELECT 'A' as type, count(*) as total FROM `brapci_autor` where autor_alias = autor_codigo UNION SELECT 'U' as type, count(*) as total FROM `brapci_autor` where autor_alias <> autor_codigo";
	$rlt = $this->db->query($sql);
	$rlt = $rlt->result_array();
	$sb = '';
	for ($r=0;$r < count($rlt);$r++)
		{
			$line = $rlt[$r];
			$type = $line['type'];
			switch ($type)
				{
				case 'A':
					$sb .= '<span class="big"><b>'.number_format($line['total'],0,',','.').'</b> '.'Autores'.'</span></br>';
					break;
				case 'U':
					$sb .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Remissivas de Autores'.'</br>';
					break;
				}
		}
	$sb .= '<br>';
		
	/***************** Keywords ****************************************************************************************/
	$sql = "SELECT count(*) as total, 'A' as type, kw_idioma FROM `brapci_keyword` where kw_codigo = kw_use group by kw_idioma
			UNION
			SELECT count(*) as total, 'U' as type, kw_idioma FROM `brapci_keyword` where kw_codigo <> kw_use group by kw_idioma
			ORDER BY kw_idioma, type
			";
	$rlt = $this->db->query($sql);
	$rlt = $rlt->result_array();
	$sc = '';
	for ($r=0;$r < count($rlt);$r++)
		{
			$line = $rlt[$r];
			$type = $line['type'];
			switch ($type)
				{
				case 'A':
					$sc .= '<span class="big"><b>'.number_format($line['total'],0,',','.').'</b> '.' Palavras-chave em '.msg($line['kw_idioma']).'</span></br>';
					break;
				case 'U':
					$sc .= '<b>'.number_format($line['total'],0,',','.').'</b> '.'Remissivas em '.msg($line['kw_idioma']).'</br>';
					$sc .= '<br>';
					break;
				}
		}	
			
	$sa = '
			<div class="container">
				<div class="jumbors col-md-3" style="border-radius: 20px;">
					<span class="big"><b>PUBLICAÇÕES</b></span>
					<br>
					<br>
					<span class="middle">'.$sx.'
					</span>
				</div>
				<div class="jumbors col-md-3 col-md-offset-1" style="border-radius: 20px;">
					<span class="big"><b>AUTORIDADES</b></span>
					<br>
					<br>
					<span class="middle">			
						'.$sb.'
						'.$sc.'
						1 Tesauro<br>
					</span>
				</div>
			</div>	
		';
		
		$dir = $_SERVER['SCRIPT_FILENAME'];
		$dir = troca($dir,'index.php','');
		$filename = $dir.'application/views/brapci/jumbo.php';

		$flt = fopen($filename,'w');
		fwrite($flt,$sa);
		fclose($flt);			
		return($sa);		
	}

}

function qualificacao($x) {
	$prof = $x['ae_professor'];
	$estu = $x['ae_aluno'];
	$mest = $x['ae_mestrado'];
	$dout = $x['ae_doutorado'];
	$pros = $x['ae_profissional'];
	$r = '';
	if ($estu == 1) {
		if ($mest == 1) { $r = 'Mestrando ';
		} else {
			if ($dout == 1) { $r = 'Doutorando ';
			} else { $r = 'Estudante';
			}
		}
	} else {
		if ($dout == 1) { $r = 'Doutor ';
		} else {
			if ($mest == 1) { $r = 'Mestre ';
			}
		}
		if ($prof == 1) { $r = 'Prof.(a) ' . $r;
		}
	}
	return ($r);
}
?>