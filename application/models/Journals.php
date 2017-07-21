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
class journals extends CI_model {
	
	function coletions($cl = 0)
		{
			
		}
	
	function row($obj) {
		$obj -> fd = array('id_jnl', 'jnl_nome', 'jnl_tipo','jnl_last_harvesting', 'jnl_status','jnl_issn_impresso');
		$obj -> lb = array('ID', 'Nome do autor', 'Tipo', 'Harvesting','Situação', 'ISSN');
		$obj -> mk = array('', 'L', 'C', 'C');
		return ($obj);
	}
	
	
	function updatex() {
		$c = 'jnl';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update brapci_journal set $c2 = lpad($c1,$c3,0) where $c2='' ";
		$rlt = $this -> db -> query($sql);
	}	

	/* Dados CP*/
	function cp() {
		$cp = array();
		array_push($cp, array('$H', 'id_jnl', '', False, True));
		array_push($cp, array('$S100', 'jnl_nome', 'Nome de citação', False, True));
		array_push($cp, array('$S40', 'jnl_nome_abrev', 'Nome abreviado', False, True));
		array_push($cp, array('$S14', 'jnl_issn_impresso', 'ISSN', False, True));
		array_push($cp, array('$S14', 'jnl_issn_eletronico', 'ISSN Eletrônico', False, True));
		array_push($cp, array('$S100', 'jnl_url', 'URL da publicação', False, True));
		
		$sqltp = 'select * from ajax_cidade where cidade_ativo = 1 order by cidade_nome';
		array_push($cp, array('$Q cidade_codigo:cidade_nome:'.$sqltp, 'jnl_cidade', 'Cidade', True, True));
        
        array_push($cp, array('$Q peri_codigo:peri_nome:select * from brapci_periodicidade order by peri_nome', 'jnl_periodicidade', 'Periodicidade', True, True));

        array_push($cp, array('$O A:Vigente&B:Encerrado&X:Cancelado', 'jnl_status', 'Situação', True, True));              

		//array_push($cp, array('$O 1:Ativo&0:Inativo', 'jnl_oai_from', 'OAI Ativo', False, True));
		array_push($cp, array('$S20', 'jnl_patch', 'Atalho', False, True));
        

        /* COLEÇÃO */
        array_push($cp, array('${', '', 'Coleção', False, True));
        $sqltp = 'select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem';
        array_push($cp, array('$Q jtp_codigo:jtp_descricao:'.$sqltp, 'jnl_tipo', 'Tipo da publicação', True, True));
        
        array_push($cp, array('$Q id_cl:cl_name:select * from collections', 'jnl_collection', 'Coleção', True, True));
        array_push($cp, array('$}', '', 'Coleção', False, True));

		array_push($cp, array('${', '', 'OAI', False, True));
		array_push($cp, array('$S100', 'jnl_url_oai', 'Link do OAI-PMH', False, True));
		array_push($cp, array('$S100', 'jnl_token', 'Token', False, True));
		array_push($cp, array('$[1900-'.date("Y").']D', 'jnl_token_from', 'harvsting from', False, True));
		
		array_push($cp, array('$}', '', 'OAI', False, True));
		
		
		array_push($cp, array('${', '', 'Indexadores', False, True));
		array_push($cp, array('$O 0:Não&1:Sim', 'jnl_scielo', 'Indexado no Scielo', False, True));
		array_push($cp, array('$}', '', 'Indexadores', False, True));
		
		/* Botao */
		array_push($cp, array('$B8', '', 'Gravar >>>', False, True));
		return ($cp);
	}
	
	function le($id=0)
		{
			$sql = "select * from brapci_journal
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo 
					left join ajax_cidade on cidade_codigo = jnl_cidade
					left join brapci_periodicidade on jnl_periodicidade = peri_codigo
					where id_jnl = ".$id."
					order by jtp_descricao, jnl_nome
			 ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
					
			$line['na'] = 0;
			
			$line['desde'] = $line['jnl_ano_inicio'];
			$line['anos'] = date("Y")-$line['jnl_ano_inicio'];
			
			/* Calcula */
			$sql = "select count(*) as artigos from brapci_article where ar_journal_id = '".strzero($id,7)."' and ar_status <> 'X'  ";
			$query = $this->db->query($sql);
			$rlt = $query->result();
			$xline = db_read($rlt);
			$line['na'] = $xline['artigos'];

			$sql = "select count(*) as edicoes from brapci_edition where ed_journal_id = '".strzero($id,7)."' and ed_status <> 'X' ";
			$query = $this->db->query($sql);
			$rlt = $query->result();
			$xline = db_read($rlt);
			$line['nr'] = $xline['edicoes'];
			
			$line['logo'] = '';
			$logo = trim($line['jnl_codigo']).'.jpg';
			$file_logo = 'img/journals/'.$logo;
			if (file_exists($file_logo))
				{
					$line['logo'] = '<img src="'.base_url('img/journals/'.$logo).'" width="150">';
				} else {
					
				}							
						
			return($line);
			
		}
	
	function show_publish($tipo='')
		{
			if (strlen($tipo) > 0)
				{
					$wh = " and jnl_tipo = '$tipo' ";
				} else {
					$wh = '';
				}
			$sql = "select * from brapci_journal
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo 
					left join ajax_cidade on cidade_codigo = jnl_cidade
					left join brapci_periodicidade on jnl_periodicidade = peri_codigo
					where (jnl_status = 'A' or jnl_status = 'B')
					$wh
					order by jtp_descricao, jnl_nome
			 ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result();
			$xtp = '';
			
			$sx = '<table width="100%" class="tabela00">';
			$sh = '<tr> <th></th>
						<th>'.msg('pos').'</th>
						<th>'.msg('title').'</th>
						<th>'.msg('city').'</th>
						<th>'.msg('periodicity').'</th>
						<th>'.msg('ISSN').'*</th>
						<th>'.msg('eISSN').'**</th>
						</tr>';
			
			$id = 0;			
			while ($line = db_read($rlt))
				{
					$id++;
					$tp = $line['jtp_descricao'];
					
					
					if ($tp <> $xtp)
						{
							$sx .= '<TR><TD colspan=3 class="lt4">'.$tp;
							$xtp = $tp;
							$sx .= $sh;
							$id = 1;
						}
					$link = base_url('index.php/journal/view/'.$line['id_jnl']);
					$link = '<A HREF="'.$link.'" class="link">';
					$sx .= '<TR>';
					$sx .= '<TD width="20">&nbsp;</td>';
					$sx .= '<TD width="20" align="center">'.$id.'.</td>';
					$sx .= '<TD class="tabela01">';
					$sx .= $link.trim($line['jnl_nome']).'</a>';
					
					$sx .= '<TD class="tabela01">';
					$sx .= $link.trim($line['cidade_nome']).'</a>';
					
					$sx .= '<TD class="tabela01">';
					$sx .= $link.trim($line['peri_nome']).'</a>';

					$sx .= '<TD class="tabela01" align="center" width="80">';
					$sx .= trim($line['jnl_issn_impresso']);
					$sx .= '<TD class="tabela01" align="center" width="80">';
					$sx .= trim($line['jnl_issn_eletronico']);
									}
			$sx .= '</table>';
			return($sx);
		}
}
	