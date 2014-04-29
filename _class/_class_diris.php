<?php
class diris
	{
	var $tabela = "diris";
	var $codigo;
	var $codigo_equivalente;
	var $line;
	
	function mostra_producao_docente($codigo)
		{
			global $db_base, $db_public;
			$search = new search;
			$eq = $this->line['dis_source'];
			$sql = "select * from (
						select ae_article as article 
						from ".$db_base."brapci_article_author 
						where ae_author = '$eq'
					) as tabela01
					inner join ".$db_public."artigos on ar_codigo = article
					order by ar_ano desc ";
					
			$rlt = db_query($sql);
			$sx .= '<table>';
			while ($line = db_read($rlt))
				{
					
					$sx .= $search->show_article_mini($line);
				}
			$sx .= '</table>';
			echo utf8_decode($sx.$search->js);
		}
	
	function busca_relacao_autor($nome,$codigo)
		{
			global $db_base;
			$nome = uppercasesql($nome);
			$nome = troca($nome,',',' ');
			$nome = troca($nome,' ',';').';';
			$nome = splitx(';',$nome);
			$wh = '';
			for ($r=0;$r < count($nome);$r++)
				{
					if (strlen($wh) > 0) { $wh .= ' and '; }
					$wh .= "(autor_nome_asc like '%".$nome[$r]."%') ";
				}
			
			$sqlq = "select * from ".$db_base."brapci_autor 
					where $wh ";
			$rlt = db_query($sqlq);
			$id = 0;
			$xcod = '';
			while ($line = db_read($rlt))
				{
					
					$cod = $line['autor_alias'];
					if ($cod != $xcod) { $id++; }
					$xcod = $cod;
					$sx .= '<BR>'.$line['autor_nome'].' ('.$cod.')';
				}
			if ($id==1)
				{
					$sql = "update diris set dis_source = '".$cod."' where dis_codigo = '".$codigo."' ";
					$sx .= '<BR>'.$sql;
					$rlt = db_query($sql);				
				} else {
					$sx .= '<BR>'.$sqlq;
				}
			return($sx);
		}
	
	function mostra()
		{
			$line = $this->line;
			$sx .= '<table class="tabela00">';
			$sx .= '<TR>';
			$sx .= '<TD class="lt3"><B>'.$line['dis_nome'].'</B>';
			
			/* Genero */
			$genero = $line['dis_genero'];
			
			$lattes = trim($line['dis_lattes']);
			if (strlen($lattes) > 0)
				{
					$lattes = '<a href="'.$lattes.'" class="_new">lattes</A>';
				}
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= $lattes;
			
			$sx .= '<TR><TD class="tabela01">'.$line['dis_biografia'];
			$sx .= '</table>';
			
			return($sx);
		}
	
	function le($id)
		{
			$sql = "select * from diris where dis_codigo = '".$id."' or id_dis  = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
					return(1);
				} else { return(0); }
					
		}
		
	function lista_professores_pos($est)
		{
			global $db_apoio;
			$sqld = "SELECT pdce_docente FROM programa_pos_docentes WHERE pdce_ativo = 1 group by pdce_docente";
			
			$sql = "select * from diris 
			inner join ( ".$sqld.") as tabela00 on dis_codigo = pdce_docente
					left join ".$db_apoio."ajax_pais on dir_nascionalidade = pais_sigla3
					where dis_ativo = 1
					order by dis_nome
			";
			$rlt = db_query($sql);
			
			$id = 0;
			$xlt = '';
			while ($line = db_read($rlt))
				{
					$lt = uppercase(substr($line['dis_nome'],0,1));
					if ($xlt != $lt)
						{
							$sx .= '<div style="padding: 4px; text-align: right; font-size: 20px; width: 25px; height: 25px; background-color: #C0C0FF; ">';
							$sx .= $lt;
							$sx .= '</div>';
							$xlt = $lt;
						}
					$link = '<A HREF="author.php?dd0='.$line['dis_codigo'].'">';
					$img = trim($line['pais_sigla3']);
					$sx .= '<div class="lt1 link">';
					$sx .= '<img src="../img/flags/flag_'.$img.'.png" height="15">';
					$sx .= '&nbsp;';
					$sx .= $link;
					$sx .= trim($line['dis_nome']);
					$sx .= '</A>';
					$sx .= '</div>';
					if (strlen(trim($line['dis_source']))==0)
						{
						$sx .= $this->busca_relacao_autor($line['dis_nome'],$line['dis_codigo']);
						}
					$id++;
				}
			$sx .= 'Total de '.$id;
			return($sx);
		}

	function lista_professores($est)
		{
			global $db_apoio;
			$sql = "select * from diris 
					left join ".$db_apoio."ajax_pais on dir_nascionalidade = pais_sigla3
					where dis_ativo = 1
					order by dis_nome
			";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="author.php?dd0='.$line['dis_codigo'].'">';
					$img = trim($line['pais_sigla3']);
					$sx .= '<div class="tabela01">';
					$sx .= '<img src="../img/flags/flag_'.$img.'" height="15">';
					$sx .= '&nbsp;';
					$sx .= $link;
					$sx .= trim($line['dis_nome']);
					$sx .= '</A>';
					$sx .= '</div>';
				}
			return($sx);
		}

	function cp()
		{
		global $db_apoio, $db_diris;
		$cp = array();
		array_push($cp,array('$H8','id_dis','id_dis',False,True,''));
		array_push($cp,array('$S100','dis_nome','Nome',False,True,''));
		array_push($cp,array('$H8','dis_nome_as','Nome Asc',False,True,''));
		array_push($cp,array('$H7','dis_codigo','Codigo',False,True,''));
		array_push($cp,array('$S7','dis_source','Codigo Eq. Autor',False,True,''));
		array_push($cp,array('$O : &M:Masculino&F:Feminino','dis_genero','Genero',False,True,''));

		array_push($cp,array('$S80','dis_instituicao','Instituição',False,True,''));
		array_push($cp,array('$S80','dis_lattes','Link para Lattes',False,True,''));

		array_push($cp,array('$Q pais_nome:pais_sigla3:select * from '.$db_apoio.'ajax_pais order by pais_preferencial desc, pais_nome','dir_nascionalidade','Nascionalidade',False,True,''));

		$qs = "a_descricao:a_cnpq:select CONCAT_WS(' ',a_cnpq,a_descricao) as a_descricao,a_cnpq from ".$db_apoio."ajax_areadoconhecimento order by a_cnpq ";
		array_push($cp,array('$S4','dis_nasc','Ano nascimento',False,True,''));
		array_push($cp,array('$S4','dis_fale','Ano falecimento',False,True,''));
		array_push($cp,array('$S40','dis_foto','Cod.Foto',False,True,''));
		array_push($cp,array('$S80','dis_email','e-mail',False,True,''));
		array_push($cp,array('$S80','dis_email_alt','e-mail (alternativo)',False,True,''));
		array_push($cp,array('$S80','dis_url','Página pessoal (URL)',False,True,''));
	
		array_push($cp,array('$T60:7','dis_biografia','Biografia',False,True,''));

		array_push($cp,array('$Q '.$qs,'dis_forma_1','Formação (Graduação)',False,True,''));
		array_push($cp,array('$S4','dis_forma_1_ano','Ano de formação (Graduação)',False,True,''));

		array_push($cp,array('$Q '.$qs,'dis_forma_2','Formação (Mestrado)',False,True,''));
		array_push($cp,array('$S4','dis_forma_2_ano','Ano de formação (Mestrado)',False,True,''));

		array_push($cp,array('$Q '.$qs,'dis_forma_3','Formação (Doutorado)',False,True,''));
		array_push($cp,array('$S4','dis_forma_3_ano','Ano de formação (Doutorado)',False,True,''));

		array_push($cp,array('$Q '.$qs,'dis_forma_4','Formação (Pós-Doutorado)',False,True,''));
		array_push($cp,array('$S4','dis_forma_4_ano','Ano de formação (Pós-Doutorado)',False,True,''));

		array_push($cp,array('$O 1:SIM&0:Não','dis_ativo','Ativo',False,True,''));
		array_push($cp,array('$U8','dis_update','',False,True,''));


		return($cp);	
		}
		
	
	function row()
		{
			global $cdf,$cdm,$masc;
			$cdf = array('id_dis','dis_nome','dis_instituicao','dis_ativo','dis_codigo');
			$cdm = array('cod',msg('nome'),msg('instituicao'),msg('ativo'),msg('codigo'));
			$masc = array('','','','','','SN','');
			return(1);				
		}
		function updatex()
			{
				global $base;
				$c = 'dis';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}			
	}
?>
