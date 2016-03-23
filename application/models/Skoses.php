<?php
class skoses extends CI_model {
	var $concept = 'wese_concept';
	
	function le_c($concept)
		{
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					inner join  wese_scheme on id_sh = c_scheme
 					left join wese_note on wn_id_c = id_c
				where l_type='PREF' 
 					and id_c = '$concept'	
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0)
			{
				$line = $rlt[0];
				$id = $line['id_c'];
				$line['uri'] = base_url('index.php/skos/t/'.$id);	
				$line['narrow'] = $this->narrow_le($id);	
				$line['boader'] = $this->broader_le($id);
				$line['used'] = $this->used_le($id);
				$line['pref'] = $this->pref_le($id);
				$line['hidden'] = $this->hidden_le($id);
			} else {
				$line = array();
			}
		return($line);	
		}
		
		
	Function prepara_termo($t='')
		{
			$t = LowerCase($t);
			$t = UpperCase(substr($t,0,1)).substr($t,1,strlen($t));
			return($t);
		}		
		
	function insere_termo($t='',$lang='')
		{
			$t = $this->prepara_termo($t);
			$sql = "select * from wese_term where t_name = '$t' and t_lang = '$lang' ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			if (count($rlt) == 0)
				{
					$sqli = "insert into wese_term 
							(
							t_name, t_lang
							) values (
							'$t','$lang')
							";
					$xrlt = $this->db->query($sqli);
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
				} 
			$line = $rlt[0];
			$idr = $line['id_t'];
			return($idr);
		}
			
	function le($scheme,$concept)
		{
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					inner join  wese_scheme on id_sh = c_scheme
				where l_type='PREF' 
 					and sh_initials = '$scheme'	
 					and c_id = '$concept'	
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0)
			{
				$line = $rlt[0];		
			} else {
				$line = array();
			}
		return($line);	
		}

	function alphabetic_list($letter='')
		{
			$sx = $this->show_letters($letter);
			return($sx);
		}
	function show_letters()
		{
			$letter=get("dd0");
			$sql = "SELECT letters, count(*) as total 
						FROM ( 
							SELECT substr(t_name,1,1) as letters, 1 FROM wese_concept 
							INNER JOIN wese_label on l_concept_id = id_c 
							INNER JOIN wese_term on l_term = id_t
							) as tabela 
						GROUP BY letters ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			
			
			$sx = '<center><table align="center">';
			$sx .= '<tr align="center">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$bg = '';
					if ($line['letters']==$letter)
						{
							$bg = ' bg_lblue ';
							$sx .= '';
							
						}
					$link = '<a href="'.base_url('index.php/skos/?dd0='.$line['letters']).'" class="link lt3">';
					$sx .= '<td class="border1 pad5 radius5 '.$bg.'" style="width: 20px;" >'.$link.$line['letters'].'</a>'.'</font>';
				}
			$sx .= '</table></center>';
			return($sx);
		}
		
	function show_terms_by_letter($letter='')
		{
			$sql = "SELECT * 
						FROM wese_concept 
							INNER JOIN wese_label on l_concept_id = id_c 
							INNER JOIN wese_term on l_term = id_t 
						WHERE t_name like '$letter%' 
						ORDER BY t_name ";
						echo $sql;
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			
			$sx = '<div class="column3">';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$link = '<a href="'.base_url('index.php/skos/t/'.strzero($line['id_c'],7)).'" class="link lt2">';
					$sx .= $link.$line['t_name'].'</a>';
					$sx .= '<br>';
				}
			$sx .= '</div>';
			return($sx);
		}

	function search_term($term, $page = 1) {
		$sql = "select * from wese_term 
					left join  wese_label on id_t = l_term
					left join wese_concept on id_c = l_concept_id
					left join  wese_scheme on id_sh = c_scheme
				where t_name like '%$term%'
					order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<Div class="column3"><tt>';
		$ft = '<span style="line-height: 150%">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			
			$type = $line['l_type'];
			
			switch ($type)
				{
				case 'PREF':
					
					$link = '<a href="'.base_url('index.php/skos/t/'.strzero($line['l_concept_id'],7)).'" class="link lt2">';
					$sx .= $link.$ft.$line['t_name'].'<span></a>';
					$type_term = '<font color="#A0A0A0">(prefTerm)</font>';
					$sx .= ' '.$type_term;
					$sx .= '<br>';
					
					break;
				default:
					$link = '<a href="'.base_url('index.php/skos/t/'.strzero($line['l_concept_id'],7)).'" class="link lt2">';
					$sx .= $link.$ft.$line['t_name'].'<span></a>';
					$type_term = '<font color="#A0A0A0">(altTerm)</font>';
					$sx .= ' '.$type_term;
					$sx .= '<br>';
					break;
				}

		}
		$sx .= '</tt></div>';
		return ($sx);
	}

	/* Conceito */
	function narrows_link($c1, $c2) {
		$scheme = $this -> session -> userdata('scheme_id');
		$sql = "insert into wese_concept_tg
					(tg_conceito_2, tg_conceito_1, tg_scheme)
					values
					($c1,$c2,$scheme)					
					";
		//echo $sql;
		//exit;
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function recupera_id_concept($tm = '') {
		$sql = "select * from wese_concept where c_id = '$tm' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_c']);
		} else {
			return (0);
		}
	}

	/* Terms */
	function concept($id) {
		$data = array();

		$sx = '<table width="100%" class="lt1">';
		$sx .= $this -> prefTerm($id);
		$sx .= $this -> terms_association($id, 'ALT');
		$sx .= $this -> terms_association($id, 'HIDDEN');

		$sx .= $this -> broader($id);
		$sx .= $this -> narrower($id);
		$sx .= '</table>';

		return ($sx);
	}

	function concepts_no($id = '', $concept = '') {
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					left join (
 							select tg_conceito_1 as c1 from wese_concept_tg
 							union
 							select tg_conceito_2 as c1 from wese_concept_tg 
						) as tabela on l_concept_id = c1
 					where l_type='PREF' and c1 is null
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<h2>' . msg('Alphabetical') . '</h2>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<a href="' . base_url('index.php/skos/thema/' . $id . '/' . $line['c_id']) . '/' . $concept . '/' . '" class="link lt3">';
			$sx .= $line['t_name'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}

	function concepts($id = '', $concept = '') {
		$scheme = $this -> session -> userdata('scheme_id');

		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					left join (
 							select tg_conceito_1 as c1 from wese_concept_tg
 							union
 							select tg_conceito_2 as c1 from wese_concept_tg 
						) as tabela on l_concept_id = c1
 					where l_type='PREF' and c1 is not null
 					and c_scheme = $scheme			
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<h2>' . msg('Alphabetical') . '</h2>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<a href="' . base_url('index.php/skos/thema/' . $id . '/' . $line['c_id']) . '/' . $concept . '/' . '" class="link lt3">';
			$sx .= $line['t_name'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}
	function pref_le($term)
		{
			$sql = "select * from  wese_label
						inner join wese_term on l_term = id_t
						where l_concept_id = $term and l_type = 'PREF' 
					order by t_name";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			return($rlt);	
		}

	function used_le($term)
		{
			$sql = "select * from  wese_label
						inner join wese_term on l_term = id_t
						where l_concept_id = $term and l_type = 'ALT' 
					order by t_name";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			return($rlt);	
		}
	function hidden_le($term)
		{
			$sql = "select * from  wese_label
						inner join wese_term on l_term = id_t
						where l_concept_id = $term and l_type = 'HIDDEN' 
					order by t_name";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			return($rlt);
		}
	
	function broader_le($term)
		{
			$sql = "select distinct t_name,sh_initials, c_id, tg_conceito_2, tg_conceito_1
					FROM wese_concept_tg
						inner join  wese_scheme on id_sh = tg_scheme
						left join wese_concept on tg_conceito_1 = id_c 
						left join wese_label on l_concept_id = id_c
						left join wese_term on l_term = id_t
						where tg_conceito_2 = $term and l_type = 'PREF' 
					order by t_name";					
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			return($rlt);
		}
	function narrow_le($term)
		{
			$sql = "select distinct t_name,sh_initials, c_id, tg_conceito_2, tg_conceito_1 
					FROM wese_concept_tg
						inner join  wese_scheme on id_sh = tg_scheme
						left join wese_concept on tg_conceito_2 = id_c 
						left join wese_label on l_concept_id = id_c
						left join wese_term on l_term = id_t
						where tg_conceito_1 = $term and l_type = 'PREF' 
					order by t_name";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			return($rlt);
		}		

	function broader($id) {
		$id = $this -> recupera_id_concept($id);
		$sql = "select * from wese_concept_tg
					inner join  wese_label on l_concept_id = tg_conceito_1 and l_type = 'PREF'
					inner join wese_term on l_term = id_t
					inner join wese_concept on id_c = l_concept_id
					inner join  wese_scheme on id_sh = c_scheme 
					where tg_conceito_2 = $id 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$tm = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$concept = $line['sh_initials'];

			$link = '<a href="' . base_url('index.php/skos/thema/' . $concept . '/' . $line['c_id']) . '" class="link lt3">';
			if ($r > 0) { $tm .= '<br>';
			}
			$tm .= $link . $line['t_name'] . '</a>';
		}
		$sx = '<tr valign="top">';
		$sx .= '<td>' . msg('BROADER CONCEPT') . '</td>';
		$sx .= '<td class="lt3">';
		$sx .= $tm;
		$sx .= '<div class="drop" id="broader" ondrop="drop(event)" ondragover="allowDrop(event)">Drop Here the broader term</div>';
		$sx .= '<hr size=1>';
		$sx .= '</td>';
		$sx .= '<tr>';

		$sx .= '
		<style>
		.drop
			{
				width: 90%; height: 30px; border: 1px solid #111111;
				border-radius: 5px;
				padding: 5px;
			}
		</style>
		';
		return ($sx);
	}

	function narrower($id) {
		$id = $this -> recupera_id_concept($id);
		$sql = "select * from wese_concept_tg
					inner join  wese_label on l_concept_id = tg_conceito_2 and l_type = 'PREF'
					inner join wese_term on l_term = id_t
					inner join wese_concept on id_c = l_concept_id
					inner join  wese_scheme on id_sh = c_scheme
					where tg_conceito_1 = $id 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$tm = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$concept = $line['sh_initials'];

			$link = '<a href="' . base_url('index.php/skos/thema/' . $concept . '/' . $line['c_id']) . '" class="link lt3">';
			if ($r > 0) { $tm .= '<br>';
			}
			$tm .= $link . $line['t_name'] . '</a>';
		}
		$sx = '<tr valign="top">';
		$sx .= '<td>' . msg('NARROWER CONCEPTS') . '</td>';
		$sx .= '<td class="lt3">';
		$sx .= $tm;
		$sx .= '<div class="drop" id="narrower" ondrop="drop(event)" ondragover="allowDrop(event)">Drop Here the narrower term</div>';
		$sx .= '<hr size=1>';
		$sx .= '</td>';
		$sx .= '<tr>';

		$sx .= '
		<style>
		.drop
			{
				width: 90%; height: 30px; border: 1px solid #111111;
				border-radius: 5px;
				padding: 5px;
			}
		</style>
		';
		return ($sx);
	}

	function prefTerm($id) {
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					where l_type='PREF' and c_id = '$id' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<tr valign="top">';
			$sx .= '<td width="25%">' . msg('PREFERRED TERM') . '</td>';
			$sx .= '<td class="lt4"><b>' . $line['t_name'] . '</b></td>';

			$sx .= '<tr><td colspan=2><hr size=1></td></tr>';
		}
		return ($sx);
	}

	function create_concept($id, $scheme) {
		$data = date("Y-m-d");

		$sql = "select * from wese_term where id_t = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$term = trim($line['t_name']);
			$term = lowercasesql($term);
			$term = troca($term, ' ', '_');

			/* Verifica se já não existe */
			$sql = "select * from wese_concept where c_id = '$term' ";
			$rlta = $this -> db -> query($sql);
			$rlta = $rlta -> result_array();
			if (count($rlta) == 0) {
				$sql = "insert into wese_concept (c_id, c_scheme) value ('$term', $scheme) ";
				$rrb = $this -> db -> query($sql);

				$sql = "select * from wese_concept where c_id = '$term' and c_scheme = '$scheme' ";
				$rrb = $this -> db -> query($sql);
				$rrb = $rrb -> result_array();

				$line = $rrb[0];
				$idx = $line['id_c'];

				$sql = "insert into  wese_label 
							(l_concept_id, l_term, l_type, l_Update) 
									values 
							('$idx','$id','PREF','$data') ";
				$rrb = $this -> db -> query($sql);
				$sx = '1';
			} else {
				$sx = msg('term already found');
			}
			echo $sx;
		}

	}

	function is_concept($id) {
		$sql = "select * from  wese_label where l_type = 'PREF' and l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['l_concept_id']);
		} else {
			return (0);
		}
	}

	function is_hidden($id) {
		$sql = "select * from  wese_label where l_type = 'HIDEEN' and  l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['l_concept_id']);
		} else {
			return (0);
		}
	}

	function is_altterm($id) {
		$sql = "select * from  wese_label where l_type = 'ALT' and  l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['l_concept_id']);
		} else {
			return (0);
		}
	}

	function is_prefterm($id) {
		$sql = "select * from  wese_label where l_type = 'HIDDEN' and  l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['concept_pH_id_c']);
		} else {
			return (0);
		}
	}

	function terms_link($id) {
		$sql = "select * from wese_term
				
					where id_t = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$sx = '<h2>' . $line['t_name'] . '</h2>';
		return ($sx);
	}

	function terms_association($id, $type) {
		$sql = "select * from  wese_label 
			inner join wese_concept on id_c = l_concept_id
			inner join wese_term on id_t = l_term
					where l_type = '$type' and c_id = '$id'
			order by t_name
			";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<tr valign="top">';
		if ($type == 'ALT') { $sx .= '<td>' . msg('ALTERNATIVE LABEL') . '</td>';
		}
		if ($type == 'HIDDEN') { $sx .= '<td>' . msg('HIDDEN LABEL') . '</td>';
		}
		$sx .= '<td class="lt2">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if ($r > 0) { $sx .= '<br>';
			}
			$sx .= trim($line['t_name']) . ' (' . $line['t_lang'] . ')';
		}
		$sx .= '<hr size=1>';
		$sx .= '</td></tr>';
		return ($sx);
	}

	function association_term($term, $concept, $type) {
		
		$sql = "select * from wese_label 
					where l_term = $term ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt->result_array();
		
		if (count($rlt) == 0)
		{
			$data = date("Y-m-d");
			$sql = "insert into  wese_label 
						(l_concept_id, l_term, l_type, l_update) 
								values 
							('$concept','$term','$type','$data')";
			$rlt = $this -> db -> query($sql);
		} else {
			echo 'Termo já associado '.$type;
		}
		return (1);
	}

	function terms() {
		$sql = "select * from wese_term 
					left join  wese_label on l_term = id_t
					where id_l is null 		
						order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<h2>' . msg('Alphabetical') . '</h2>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<a href="' . base_url('index.php/skos/terms/' . $line['id_t']) . '" class="link lt3">';
			$sx .= $line['t_name'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}

	/* SCHEME*/
	function scheme_set($scheme) {
		$sql = "select * from  wese_scheme where sh_initials = '$scheme' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$se = array();
			$se['scheme_id'] = $line['id_sh'];
			$se['sh_name'] = $line['sh_name'];
			$se['sh_initials'] = $line['sh_initials'];
			$se['sh_icone'] = $line['sh_icone'];
			$se['sh_link'] = $line['sh_link'];
			$this -> session -> set_userdata($se);
			return (1);
		}
		return (0);

	}

	function schemes() {
		$sql = "select * from  wese_scheme where sh_active = 1 order by sh_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		$sx .= '<table width="1024" align="center">';
		$sx .= '<tr><td>';
		$sx .= '<h1>' . msg('scheme') . '</h1>';
		$sx .= '</td></tr>';

		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/scheme/' . $line['sh_initials']) . '" class="link">';
			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link . $line['sh_name'] . '</a>';
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		return ($sx);
	}

	function resumo() {
		$sql = "select count(*) as total from wese_concept ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$concepts = $line['total'];

		$sql = "select count(*) as total from wese_term ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$terms = $line['total'];

		$sx = '<table width="100%">';
		$sx .= '<tr align="center">';
		$sx .= '<td>Concepts</td>';
		$sx .= '<td>Terms</td>';

		$sx .= '<tr align="center">';
		$sx .= '<td>' . $concepts . '</td>';
		$sx .= '<td>' . $terms . '</td>';
		$sx .= '</table>';

		return ($sx);
	}

}
?>
