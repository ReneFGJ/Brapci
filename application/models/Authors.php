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
class authors extends CI_model {
	var $tabela = 'brapci_autor';

	function save_AUTHORS($id, $au) {

		$au = troca($au, chr(13), ';');
		$au = troca($au, chr(10), '') . ';';

		$au = troca($au, '0', '');
		$au = troca($au, '1', '');
		$au = troca($au, '2', '');
		$au = troca($au, '3', '');
		$au = troca($au, '4', '');
		$au = troca($au, '5', '');
		$au = troca($au, '6', '');
		$au = troca($au, '7', '');
		$au = troca($au, '8', '');
		$au = troca($au, '9', '');

		$aut = splitx(';', $au);
		$auts = array();
		$aut_asc = array();
		$autc = array();
		$hw = '';
		$idt = 0;
		for ($r = 0; $r < count($aut); $r++) {
			$autor = $aut[$r];
			$autor = troca($autor,"´",'');
			if (strpos($autor, ',') > 0) {
				$autor = substr($autor, strpos($autor, ','), strlen($autor)) . ' ' . substr($autor, 0, strpos($autor, ','));
			}
			$autor = trim(troca($autor, ',', ''));
			$autor_nbr = nbr_autor($autor, 1);
			$autor_asc = UpperCaseSql($autor_nbr);
			$auts[$r] = '';
			if (strlen($autor_nbr) > 0) {
				$auts[$idt] = $autor_nbr;
				$aut_asc[$idt] = $autor_asc;
				$autc[$idt] = '';
				$idt++;
			}
		}

		/* Recupera nomes */
		for ($r = 0; $r < count($aut_asc); $r++) {
			$autor = $aut_asc[$r];
			$code = trim(mb_detect_encoding($autor));
			if (1==2)
			{
			if (($code == 'UTF-8') or ($code == ''))
				{
					$autor = utf8_decode($autor);
					$code = trim(mb_detect_encoding($autor));
					if (($code == 'UTF-8') or ($code == ''))
						{
							$autor = utf8_decode($autor);
						}
				}
			}
			$sql = "select * from brapci_autor where autor_nome = '$autor'";
			$rrr = $this -> db -> query($sql);
			$rrr = $rrr -> result_array();

			if (count($rrr) > 0) {
				$line = $rrr[0];
				$cod = $line['autor_codigo'];
				$autc[$r] = $cod;
			} else {
				/* Não existe */
				$autc[$r] = $this -> inserir_novo_autor($autor);
			}
		}

		/* Recupera ID */
		$ida = strzero($id, 10);

		$sql = "delete from brapci_article_author where ae_article = '$ida' ";
		$rrr = $this -> db -> query($sql);

		$sql = "select * from brapci_article where ar_codigo = '$ida' ";
		$rrr = $this -> db -> query($sql);
		$rrr = $rrr -> result_array($rrr);
		$line = $rrr[0];
		$journal_id = $line['ar_journal_id'];

		/* salva na base */
		$sql = "insert into brapci_article_author 
				(ae_journal_id, ae_article, ae_position,
				ae_author, ae_instituicao, ae_aluno, 
				ae_professor, ae_ss, ae_pos,
				ae_contact, ae_mestrado, ae_doutorado,
				ae_profissional, ae_bio, ae_telefone,
				ae_endereco) value 
			";
		$pos = 1;
		for ($rx = 0; $rx < count($autc); $rx++) {
			$pos = ($rx + 1);
			if ($pos > 1) { $sql .= ', ';
			}
			$author = $autc[$rx];
			$sql .= "('$journal_id','$ida','$pos',
				'$author','','',
				'','','',
				'','','',
				'','','',
				''
				) ";
			$pos++;
		}
		$rlt = $this -> db -> query($sql);

	}

	function lista_obras_do_autor($author='')
		{
			$sql = "select * from brapci_article_author
					INNER JOIN brapci_article on ar_codigo = ae_article
					INNER JOIN brapci_journal on ae_journal_id = jnl_codigo
					INNER JOIN brapci_edition ON ed_codigo = ar_edition
					where ae_author = '$author' and ar_status <> 'X'
					order by ar_ano DESC, ar_titulo_1";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '<ul>';
			$xano = '';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					
					$ano = $line['ar_ano'];
					if ($ano != $xano)
						{
							if ($r > 0)
								{
									$sx .= '</ul><br>';
								}
							$sx .= '<h3>'.$ano.'</h3><br>';
							$xano = $ano;
							$sx .= '<ul>';
						}
					$sx .= '<li style="margin-left: 30px;">';
					$link = base_url('index.php/article/view/'.$line['ar_codigo'].'/'.checkpost_link($line['ar_codigo']));
					$sx .= '<a href="'.$link.'" class="link lt1">';
					$sx .= $line['ar_titulo_1'].'. ';
					$sx .= '<b>'.$line['jnl_nome'].'</b>';
					$sx .= ', v.'.$line['ed_vol'];
					$sx .= ', n.'.$line['ed_nr'];
					$sx .= ', '.$line['ed_ano'];
					$sx .= ', p.'.$line['ar_pg_inicial'];
					$sx .= '-'.$line['ar_pg_final'];
					$sx .= '</a>';
					$sx .= '</li>';
				}
			$sx .= '</ul>';
			//print_r($line);
			//echo '<hr>';
			return($sx);
							
		}

	function check_remissive()
		{
			$sql = "select * from ".$this->tabela."
						inner join brapci_article_author on autor_codigo = ae_author 
						where autor_codigo <> autor_alias 
						limit 10
						";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '<table width="100%" class="lt1">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$use = $line['autor_alias'];
					$remissiva = $line['autor_codigo'];
					$sql = "update brapci_article_author set ae_author = '$use' where ae_author = '$remissiva' ";
					$rrr = $this->db->query($sql);				
					$sx .= '<tr>';
					$sx .= '<td>'.$line['autor_nome'].'</td>';
				}
			$sx .= '</table>';
			return($sx);			
		}

	function inserir_novo_autor($nome) {
		/* Valida recuperacao */

		$nome = nbr_autor($nome, 7);
		$nome1 = nbr_autor($nome, 1);
		$nome2 = UpperCaseSql($nome1);
		$nome3 = nbr_autor($nome, 5);
		$nome4 = nbr_autor($nome, 7);

		$sql = "insert into brapci_autor 
									(
									autor_codigo, autor_nome, autor_nome_asc,
									autor_nome_abrev, autor_nome_citacao, autor_nasc,
									autor_lattes, autor_alias, autor_fale,
									autor_tipo, autor_genero
									) value (
									'','$nome1','$nome2',
									'$nome3','$nome4','',
									'','','',
									'','')	";
		$this -> db -> query($sql);
		$this -> updatex();

		$sql = "select * from brapci_autor where autor_nome_asc = '$nome2'";
		$rrr = $this -> db -> query($sql);
		$rrr = $rrr -> result_array();
		$line = $rrr[0];
		return($line['autor_codigo']);
	}

	function updatex() {
		$c = 'autor';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update brapci_autor set autor_codigo = lpad($c1,$c3,0), autor_alias = lpad($c1,$c3,0) where $c2='' ";
		$rlt = $this -> db -> query($sql);
	}

	function row($obj) {
		$obj -> fd = array('id_autor', 'autor_nome', 'autor_tipo', 'autor_nacionalidade', 'autor_codigo', 'autor_alias');
		$obj -> lb = array('ID', 'Nome do autor', 'Tipo', 'Nascionalidade', 'Codigo', 'Alias');
		$obj -> mk = array('', 'L', 'C', 'C', 'C', 'C');
		return ($obj);
	}

	/* Dados de edição*/
	function cp() {
		$cp = array();
		array_push($cp, array('$H', 'id_autor', '', True, True));
		array_push($cp, array('$S100', 'autor_nome', 'Nome de citação', False, True));
		array_push($cp, array('$S100', 'autor_lattes', 'Lattes do autor', False, True));
		array_push($cp, array('$O : &M:Masculino&F:Feminino', 'autor_genero', 'Genero', False, True));
		$sql = "select nasc_codigo, nasc_descricao from ajax_nacionalidade where nasc_ativo = 1 order by nasc_descricao ";
		array_push($cp, array('$Q nasc_codigo:nasc_descricao:' . $sql, 'autor_nacionalidade', 'Nascionalidade', False, True));
		array_push($cp, array('$S7', 'autor_alias', 'Remissiva', False, True));
		
		array_push($cp, array('$S8', 'autor_nasc', 'Nascimento', False, True));
		array_push($cp, array('$S8', '	autor_fale', 'Falecimento', False, True));
		
		/* Botao */
		array_push($cp, array('$B8', '', 'Gravar >>>', False, True));
		return ($cp);
	}

	/* Busca dados do autor */
	function le($id = 0) {
		$id = round($id);
		$sql = "select * from brapci_autor where id_autor = " . $id;
		$query = $this -> db -> query($sql);
		$row = $query -> result_array();
		$row = $row[0];
		$codigo = $row['autor_codigo'];
		$alias = $row['autor_alias'];
		$lattes = $row['autor_lattes'];

		if ($codigo != $alias) {
			$sql = "select * from brapci_autor where id_autor = " . round($alias);
			$query = $this -> db -> query($sql);
			$row = $query -> result_array();
			$row = $row[0];
			$codigo = $alias;
			$lattes = $row['autor_lattes'];
		}

		$row['autor_nomes'] = $this -> remissivas($codigo);
		$row['autor_foto'] = $this -> image($codigo);
		$row['autor_instituicoes'] = $this -> instituicoes($codigo);
		$row['autor_lattes'] = $this -> link_lattes($lattes);
		$row['autor_nacionalidade'] = $this -> nacionalidade($row['autor_nacionalidade'], $row['autor_genero']);
		//$row['autor_titulacao'] = 'Dr.';
		$row['acao_editar'] = $this -> link_editar($codigo);
		return ($row);
	}

	function link_editar($codigo) {
		$session_id = $this -> session -> userdata('nw_user');
		$sx = '';
		if (strlen($session_id) > 0) {
			$sx = '<A HREF="' . base_url('index.php/author/edit/' . $codigo) . '" class="lt1 link">editar</A>';
		}
		return ($sx);
	}

	function image($codigo = '') {
		$img = base_url('img/authors/noimage.jpg');
		$imgv = 'img/authors/' . $codigo . '.jpg';
		if (file_exists($imgv)) {
			$img = base_url('/img/authors/' . $codigo . '.jpg');
		}
		return ($img);
	}

	function link_lattes($link) {
		$lattes = '';
		if (strlen($link) > 0) {
			$lattes = '<img src="' . base_url('img/icone_lattes.png') . '" height="20" align="left">';
		}
		return ($lattes);
	}

	function instituicoes($codigo = '') {
		$inst = '';
		return ($inst);
	}

	function nacionalidade($nasc, $genero = 'N') {
		$genedo = trim($nasc);

		$nasc = '<img src="' . base_url('img/flags/flag_bra.png') . '" valign="center" align="left" height="20">&nbsp;-&nbsp;';
		if ($genero == 'F') { $nasc .= 'Brasileira';
		} else { $nasc .= 'Brasileiro';
		}
		return ($nasc);
	}

	function remissivas($codigo = '') {
		$sql = "select * from " . $this -> tabela . " where autor_alias = '" . $codigo . "' and autor_alias <> autor_codigo ";
		$query = $this -> db -> query($sql);
		$query = $query -> result();

		$nm = '';
		foreach ($query as $row) {
			$nm .= $row -> autor_nome;
			$nm .= '<BR>';
		}
		return ($nm);
	}

}
?>
