<?php
class author
	{
	var $codigo;
	var $nome;
	
	var $tabela = "brapci_autor";
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_autor','',False,True));
			array_push($cp,array('$S100','autor_nome','Nome do autor',False,True));
			array_push($cp,array('$S10','autor_codigo','Codigo',False,True));
			array_push($cp,array('$S100','autor_nome_abrev','Nome abreviado',False,True));
			array_push($cp,array('$S100','autor_alias','Alias',False,True));
			return($cp);
		}
	
	function seek_google()
		{
			$link = "http://scholar.google.com.br/scholar?q=";
			$sx .= '<A HREF="'.$link.'" target="_new">'.msg('seek_google_scholar').'</a>';
			return($sx);
		}
		
	
	function row()
		{
			global $cdf, $cdm,$masc;
			$idcp = 'autor';
			$cdf = array('id_'.$idcp,$idcp.'_nome',$idcp.'_nome_abrev',$idcp.'_codigo',$idcp.'_alias');
			$cdm = array('Código','Nome','Citação','Codigo','Alias');
			$masc = array('','','','','','','','','','','');			
		}
	

	function publicoes_dos_autores($artigo)
		{
			global $db_public;
			$art = new article;
			$sql = "select * from (
					SELECT ae_author FROM `brapci_article_author` 
					where ae_article = '$artigo'
					) as tabela";
					
			$sql = "select * from (
						select ae_article from (
						SELECT ae_author as author FROM brapci_article_author
						where ae_article = '$artigo'
						) as tabela01
					inner join brapci_article_author on ae_author = author 
					group by ae_article
					) as tabela02
					inner join ".$db_public."artigos on ar_codigo = ae_article
				order by ar_ano desc, ar_vol desc, ar_nr desc	
				";
							
			$rlt = db_query($sql);
			$wh = '';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="article.php?dd0='.$line['ar_codigo'].'" >VIEW</A>';
					$sx .= $art->referencia_abnt($line);
					$sx .= '&nbsp&nbsp[ '.$link.' ]';
					$sx .= '<BR><BR>';
				}
			return($sx);			
		}
	function troca_remissivas()
		{
			echo '<HR>1<HR>';
			$sql = "select * from brapci_article_author 
					inner join brapci_autor on autor_codigo = ae_author
					where autor_codigo <> autor_alias
			";
			$rlt = db_query($sql);
			$id=0;
			while ($line = db_read($rlt))
				{
					$id++;
					$sql = "update brapci_article_author 
							set ae_author = '".$line['autor_alias']."'
							where id_ae = ".$line['id_ae'];
					echo '<BR>'.$line['autor_nome'].' '.$line['autor_alias'];
					$rrr = db_query($sql);
				}
			echo '<BR>Total de '.$id.' trocas';
		}
	
	function mostra_producao()
		{
			
		}
				
	function updatex_author()
		{
				$c = 'autor';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c5 = $c.'_alias';
				$c3 = 7;
				$sql = "update  brapci_autor set 
						$c2 = lpad($c1,$c3,0) , 
						$c5 = lpad($c1,$c3,0)
						where $c2='' ";
				$rlt = db_query($sql);
				return(1);
		}
		
	function author_new($author)
		{
				$author1 = trim($author);
				
				$author1a = nbr_autor(($author1),1);
				$author1b = uppercasesql($author1a);
				$author1e = nbr_autor(($author1),5);
				$sql = "insert into brapci_autor
						(autor_nome, autor_nome_asc, autor_nome_abrev ,
						autor_nome_citacao, autor_tipo, autor_codigo) 
						values
						('$author1a','$author1b','$author1e',
						'$author1a','A','')";
				$rlt = db_query($sql);
				$this->updatex_author();
				$sql = "select * from brapci_autor where
						autor_nome_asc = '$author1a' 
						";
				$rrr = db_query($sql);
				$line = db_read($rrr);
				$codigo = $line['autor_codigo'];
				return($codigo);
		}
		
	function author_article_save($art,$author,$pos,$jid)
		{
			if ((strlen($art) > 0) and (strlen($author) > 0))
				{
				$inst = $this->autor_instituition;
				$bio = $this->bio;
				$sql = "select * from brapci_article_author
						where ae_article = '$art' and ae_author = '$author'	";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						//echo '<BR>'.$author;
						//print_r($line);
					} else {
						$sql = "insert into  brapci_article_author 
						(ae_journal_id, ae_article, ae_position,
						ae_author,ae_instituicao, ae_aluno,
						ae_professor, ae_ss, ae_pos,
						ae_contact, ae_mestrado, ae_doutorado,
						ae_profissional, ae_bio, ae_telefone,
						ae_endereco
						) values (
						'$jid','$art',$pos,
						'$author','$inst',0,
						0,0,0,
						'',0,0,
						'0','$bio','',
						''
						)";
						$rrr = db_query($sql);
					}
				}
		}
		
	function author_next_pos($art)
		{
			$sql = "select count(*) as total from brapci_article_author
					where ae_article = '$art' ";
			$rrr = db_query($sql);
			$line = db_read($rrr);
			$total = round($line['total']);
			return($total);
		}
		
	function author_find($author)
		{
			$author1 = $author;
							
			$author1a = nbr_autor(UpperCaseSql($author1),1);
			$sql = "select * from brapci_autor where
					autor_nome_asc = '$author1a' 
					";
			$rrr = db_query($sql);
			if ($line = db_read($rrr))
				{
					$codigo = $line['autor_codigo'];
				} else {
					$codigo = $this->author_new($author);
				}
				
			$sql = "select * from  brapci_article_author
					inner join brapci_article on ae_article = ar_codigo
					inner join brapci_edition on ar_edition = ed_ano   
					where ae_author = '$codigo'
					order by ed_ano desc limit 1
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->autor_instituition = $line['ae_instituicao'];
					if (($line['ae_doutorado']) == 1) { $dr = 1; }
					if (($line['ae_mestrado']) == 1) { $dr = 1; }
					if (($line['ae_profissional']) == 1) { $dr = 1; }
					if (($line['ae_professor']) == 1) { $dr = 1; }
					if (($line['ae_ss']) == 1) { $dr = 1; }
					if (($line['ae_professor']) == 1) { $dr = 1; }
				} else {
					$this->autor_institution = '';
				}
			return($codigo);		
		}
	function show_author_row($key)
		{
			$sql = "select * from brapci_autor  
					where autor_nome like '".$key."%' 
					order by autor_nome_asc
					";
			$rlt = db_query($sql);	
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="busca_autor.php?dd1='.$line['autor_codigo'].'" class="link lt1">';
					$sx .= '<BR>';
					$sx .= $link;
					$sx .= utf8_encode($line['autor_nome']);
					$sx .= '</A>';
				}
			return($sx);
		}
	function show_author($article,$tp=1)
		{
			global $editar;
			$sx = '';
			/* Show Author */
			$sql = "select * from  brapci_article_author
					inner join brapci_autor on autor_codigo = ae_author 
					where ae_article = '".$article."' 
					order by ae_position
					";
			$rrr = db_query($sql);
			$sa = '';
			while ($rline = db_read($rrr))
				{
					if (strlen($sa) > 0) { $sa .= '; '; }
					$sa .= '<I>'.trim($rline['autor_nome']).'</I>';
				}
			$sx .= $sa;		
			if ($editar==1)
				{
				$sx .= '<BR><font class="link"><a href="#" class="link" id="autor">editar autor</A></font>';
				}
			return($sx);	
		}
	
	}
?>
