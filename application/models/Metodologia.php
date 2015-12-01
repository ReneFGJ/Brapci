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
class metodologia extends CI_Model {
	function le($artigo) {
		$artigo = strzero($artigo,10);
		
		$sql = "select * from metodologia where met_article = '$artigo' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array($rlt);

		if (count($rlt) == 0)
			{
				$sql = "insert into metodologia
						(met_objetivo, met_fonte, met_article)
						values
						('','','$artigo') 
						";
				$rlt = $this -> db -> query($sql);
				
				$data = array();
				$data['met_objetivo'] = 'não classificado';
				$data['met_fonte'] = 'não classificado';
				$data['tipo'] = 'não classificado';
				$data['fins'] = 'não classificado';
				$data['instrumento'] = 'não classificado';
				return($data);
			}

		$data = $rlt[0];
		/* Metodos */
		$sql = "select * from metodologia_artigo_tipo 
							inner join metodologia_tipo on id_mett = mat_tipo
							where mat_artigo = '$artigo' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array($rlt);
		$tipo = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$tipo .= '<li>' . trim($line['mett_descricao']) . '</li>';
		}
		if (strlen($tipo) > 0) { $tipo = '<ul>' . $tipo . '</ul>';
		}
		$data['tipo'] = $tipo;

		/* Instrumentos */
		$isntr = '';
		$sql = "select * from metodologia_artigo_instrumento 
							inner join metodologia_instrumentos on id_mi = mai_instrumento
							where mai_article = '$artigo' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array($rlt);

		$instr = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$instr .= '<li>' . trim($line['mi_nome']) . '</li>';
		}
		if (strlen($instr) > 0) { $instr = '<ul>' . $instr . '</ul>';
		}
		$data['instrumento'] = $instr;
		
		/* Finalidade */
		$fins = '';
		$sql = "select * from metodologia_artigo_fins 
							inner join metodologia_fins on id_mf = maf_fim
							where maf_artigo = '$artigo' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array($rlt);

		$fins = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$fins .= '<li>' . trim($line['mf_nome']) . '</li>';
		}
		if (strlen($fins) > 0) { $fins = '<ul>' . $fins . '</ul>';
		}
		$data['fins'] = $fins;
		return ($data);		
	}
	
	function form_instrumentos($article)
		{
				/* tipos não selecionados */
				$sql = "select * from metodologia_instrumentos 
							left join metodologia_artigo_instrumento on id_mi = mai_instrumento and mai_article = '$article' 
						";
				echo $sql;
				$sx = '';
				$sx .= '<table width="400">';
				$sx .= '</table>';
				return($sx);		
		}	
	
	function mostra($obj,$edit=1) {
		$sx = '<table width="400" class="tabela00" cellspacing=2 border=0>';
		$sx .= '<tr><td class="lt3" colspan=2>Dados da metodologia</td></tr>';
		
		/* OBJETIVOS */

		$sx .= '<tr valign="top">';
		$sx .= '<th align="right" width="20%" id="obj" style="cursor: pointer;">objetivo:</th>';
		$sx .= '<td width="80%">';
		$sx .= $obj['met_objetivo'];
		$sx .= '</td>';
		
		$sx .= '<tr  id="objv" style="display: 1none;">';
		$sx .= '<th align="right" width="20%">objetivo:</th>';
		$sx .= '<td width="80%">';
		$sx .= '<textarea name="dd10" rows=5 cols=80 >'.$obj['met_objetivo'].'</textarea>';
		$sx .= '</td>';
		$sx .= '</tr>';
		
		/* FONTES */
		
		$sx .= '<tr valign="top" id="fonts">';
		$sx .= '<th align="right">fonte(s):</th>';
		$sx .= '<td>';
		$sx .= $obj['met_fonte'];
		$sx .= '</td>';		
		
		$sx .= '<tr id="fontsv" style="display: 1none;">';
		$sx .= '<th align="right" width="20%" id="fnt" style="cursor: pointer;">fonte(s):</th>';
		$sx .= '<td width="80%">';
		$sx .= '<textarea name="dd11" rows=5 cols=80 >'.$obj['met_fonte'].'</textarea>';
		$sx .= '</td>';
		$sx .= '</tr>';		
		
		/* FINALIDADE */
		
		$sx .= '<tr valign="top">';
		$sx .= '<th align="right" width="20%" id="fins">finalidade:</th>';
		$sx .= '<td>';
		$sx .= $obj['fins'];
		$sx .= '</td>';		

		$sx .= '<tr valign="top">';
		$sx .= '<th align="right" id="method">métodos:</th>';
		$sx .= '<td>';
		$sx .= $obj['tipo'];
		$sx .= '</td>';

		$sx .= '<tr valign="top">';
		$sx .= '<th align="right" id="instru">instrumentos:</th>';
		$sx .= '<td>';
		$sx .= $obj['instrumento'];
		$sx .= '</td>';


		$sx .= '</table>';
		
		if ($edit==1)
			{
				$sx .= '
				<script>
				$("#obj").click(function ()
					{
						alert("OLA");
						$("#objv").toggle()();
						$("#fontsv").toggle()();
					});
				</script>
				';
			}
		return ($sx);
	}

}
