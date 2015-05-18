<?php
class authors extends CI_model {
	var $tabela = 'brapci_autor';
	
	function save_AUTHORS($id, $au)
		{
			$au = troca($au,chr(13),';');
			$au = troca($au,chr(10),'').';';
			
			$au = troca($au,'0','');
			$au = troca($au,'1','');
			$au = troca($au,'2','');
			$au = troca($au,'3','');
			$au = troca($au,'4','');
			$au = troca($au,'5','');
			$au = troca($au,'6','');
			$au = troca($au,'7','');
			$au = troca($au,'8','');
			$au = troca($au,'9','');
			
			
			$aut = splitx(';',$au);
			$auts = array();
			$aut_asc = array();
			$hw = '';
			for ($r=0;$r < count($aut);$r++)
				{
					$autor = $aut[$r];
					if (strpos($autor,',') > 0)
						{
							$autor = substr($autor,strpos($autor,','),strlen($autor)).' '.substr($autor,0,strpos($autor,','));
						}
					$autor = trim(troca($autor,',',''));
					$autor_nbr = nbr_autor($autor,1);
					$autor_asc = UpperCaseSql($autor_nbr);
					echo $autor.'='.$autor_nbr.'='.$autor_asc.'<BR>';
					$auts[$autor_nbr] = '';
					if (strlen($autor_nbr) > 0)
						{
						if (strlen($hw) > 0) { $hw .= ','; }
						$hw .= "'$autor_nbr'";
						$aut_asc[$autor_asc] = '';
						}
				}
			/* Recupera nomes */
			$sql = "select * from brapci_autor where autor_nome IN ($hw)";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$aut1 = trim($line['autor_nome_asc']);
					$aut_asc[$aut1] = $line['autor_alias'];
				}

			/* Valida recuperacao */
			foreach ($aut_asc as $key => $value) {
				echo '<BR>'.$key.'--'.$value;
				if (strlen($value) == '')
					{
						/* Novo autor */
					}
			}
			
			/* salva na base */
			$sql = "insert into brapci_article_author 
				(ae_journal_id, ae_article, ae_position,
				ae_author, ae_instituicao, ae_aluno, 
				ae_professor, ae_ss, ae_pos,
				ae_contact, ae_mestrado, ae_doutorado,
				ae_profissional, ae_bio, ae_telefone,
				ae_endereco)
			";
			
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
			$sx = '<A HREF="' . base_url('author/edit/' . $codigo) . '" class="lt1 link">editar</A>';
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
