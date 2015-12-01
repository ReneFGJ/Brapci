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
class terms extends CI_model {
	var $tabela = 'brapci_keyword';


	function check_remissive()
		{
			$sx = '';
			$sql = "SELECT * FROM brapci_article_keyword 
						left join brapci_article on kw_article = ar_codigo 
					where ar_codigo is null";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$id = 0;
			for ($r=0;$r < count($rlt);$r++)
				{
					$id++;
					$line = $rlt[$r];
					$id_ak = $line['id_ak'];
					$sql = "delete from brapci_article_keyword where id_ak = $id_ak";
					//$rrr = $this->db->query($sql);
				}
			$sx .= 'Removed '.$id.' Itens<br>';
			/*   */
			$sql = "select * from ".$this->tabela."
						left join brapci_article_keyword on kw_keyword = kw_codigo 
						where kw_article is null 
						";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$id = $line['id_kw'];
					$sql = "delete from ".$this->tabela." where id_kw = $id";
				}
			$sx = '<table width="100%" class="lt1">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$use = $line['kw_use'];
					$remissiva = $line['kw_use'];
					$sql = "update brapci_article_keyword set ae_author = '$use' where ae_author = '$remissiva' ";
					//$rrr = $this->db->query($sql);	
					echo $sql.'<br>';			
					$sx .= '<tr>';
					$sx .= '<td>'.$line['kw_word'].'</td>';
				}
			$sx .= '</table>';
			return($sx);			
		}


	function updatex() {
		$c = 'kw';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c4 = $c . '_use';
		$c3 = 10;
		$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0), $c4 = lpad($c1,$c3,0) where $c2='' ";
		$rlt = $this -> db -> query($sql);
	}

	function row($obj) {
		$obj -> fd = array('id_kw', 'kw_word', 'kw_idioma', 'kw_codigo', 'kw_use');
		$obj -> lb = array('ID', 'Termo', 'Idioma', 'Codigo', 'Alias');
		$obj -> mk = array('', 'L', 'C', 'C', 'C', 'C');
		return ($obj);
	}

	/* Dados de edição*/
	function cp() {
		$cp = array();
		array_push($cp, array('$H', 'id_autor', '', True, True));
		array_push($cp, array('$S100', 'autor_nome', 'Nome de citação', False, True));
		array_push($cp, array('$S100', 'autor_lattes', 'Lattes do autor', False, True));
		array_push($cp, array('$S1', 'autor_genero', 'Genero', False, True));
		$sql = "select nasc_codigo, nasc_descricao from ajax_nacionalidade where nasc_ativo = 1 order by nasc_descricao ";
		array_push($cp, array('$Q nasc_codigo:nasc_descricao:' . $sql, 'autor_nacionalidade', 'Nascionalidade', False, True));
		array_push($cp, array('$S7', 'autor_alias', 'Remissiva', False, True));
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
