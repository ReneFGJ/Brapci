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
class cited extends CI_Model {
	function inport_cited_text($s) {

	}

	function save_ref_pre($txt) {
		$c = troca($txt, chr(13), '');
		$c = troca($c,'[ Links ]','.');
		$c = troca($c, '#', '%');
		$c = troca($c, chr(10), '¢');
		$c = troca($c, '. ¢', '.¢');
		$c = troca($c, '<', '&lt;');
		$c = troca($c, '>', '&gt;');
		$c = troca($c, '.¢', '.#');
		$c = troca($c, '¢', ' ');
		$c = troca($c, '___.', '#%%%');
		$c = troca($c, '_', '');
		$c = troca($c, '#%%%','___.');
		$c = troca($c, '  ', ' ');

		$c = troca($c, '#Acesso', '. Acesso');
		$c = troca($c, '#Disponível', '. Disponível');
		$c = troca($c, '#0', '. 0');
		$c = troca($c, '#1', '. 1');
		$c = troca($c, '#2', '. 2');
		$c = troca($c, '#3', '. 3');
		$c = troca($c, '#4', '. 4');
		$c = troca($c, '#5', '. 5');
		$c = troca($c, '#6', '. 6');
		$c = troca($c, '#7', '. 7');
		$c = troca($c, '#8', '. 8');
		$c = troca($c, '#9', '. 9');
		$c = troca($c, '#(', '. (');
		$c = troca($c, '..', '.');

		$ln = splitx('#', $c);

		$h = ' ABCDEFGHIJKLMNOPQRSTUWVXYZ_';
		$autor = '';
		for ($r = 1; $r < count($ln); $r++) {
			$l = trim(substr($ln[$r], 1, 1));
			$pos = strpos($h, $l);
			/* ___ */
			if (substr($ln[$r],0,3) == '___')
				{
					$xautor = substr($ln[$r-1],0,strpos($ln[$r-1],'.'));
					if (strlen($xautor) > 0)
						{
							$autor = $xautor;
						}
					if (strlen($ln[$r]) > 5)
						{
							$ln[$r] = troca($ln[$r],'___',$autor);
						}
				}
			
			
			if ($pos > 0) {

			} else {
				//echo '<br>==>' . $l . '---' . substr($ln[$r], 0, 20);
				$ln[$r - 1] .= ' ' . $ln[$r];
				$ln[$r] = '';
			}

		}
		return ($ln);
	}

	function save_ref($id = 0, $txt) {
		$work = strzero($id, 10);
		$ln = $this -> save_ref_pre($txt);

		$sql = "delete from mar_works where m_work = '$work' ";
		$this -> db -> query($sql);

		for ($r = 0; $r < count($ln); $r++) {
			$ttt = $ln[$r];
			if (strlen($ttt) > 5) {
				$sql = "insert into mar_works 
								(
								m_status, m_ref, m_work,
								m_norma
								)
								values
								('@','$ttt','$work',
								'ABNT')";
				$this -> db -> query($sql);
			}
		}
	}

	function show_cited($id = '') {
		$id = strzero($id, 10);
		$sql = "select * from mar_works where m_work = '$id' order by id_m";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<ul>' . cr();
		$admin = 1;
		$link = '';
		$link_a = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			
			if ($admin == 1)
				{
					$link = '<a href="#" onclick="newwin(\''.base_url('index.php/cited/ref_edit/'.$line['id_m'].'/'.checkpost_link($line['id_m'])).'\',600,800);">';
				}
			$sx .= '<li>' . $link. $line['m_ref'] . $link_a. '</li>' . cr();
		}
		$sx .= '</ul>' . cr();
		return ($sx);
	}

	function show_cited_pdf($id = 0) {
		$id = strzero($id, 10);
		$sql = "select * from brapci_article_suporte where bs_article = '$id'
							and bs_adress like 'http%' 
							order by bs_type";
		$rlt = db_query($sql);
		$pdf = 0;
		$sx = '<table width="100%" class="tabela00">';
		$sx .= '<tr><th>archive</th></tr>';
		$idbs = 0;
		while ($line = db_read($rlt)) {
			$xlink = trim($line['bs_adress']);
			$tipo = trim($line['bs_type']);
			$link = '';
			$linkf = '';
			$ajax = '';

			if (substr($xlink, 0, 4) == 'http') {
				$link = '<a href="' . trim($line['bs_adress']) . '" target="_new">';
				$linkf = '</a>';
				if (((trim($line['bs_status']) == '@') or (trim($line['bs_status']) == 'B') or (trim($line['bs_status']) == 'A')) and ($idbs == 0)) {
					$ajax = '<div id="coletar_cited" style="color: blue; cursor: pointer; width: 100px; border: 1px #A0A0A0 solid; text-align: center;">coletar ' . $line['id_bs'] . '</div>';
					$idbs = $line['id_bs'];
				}
			}

			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link . trim($line['bs_adress']) . $linkf;
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= trim($line['id_bs']);
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= trim($line['bs_status']);
			$sx .= '</td>';
			$sx .= '<td>' . $ajax . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';

		if ($idbs > 0) {
			$sx .= '
			<script>
			$("#coletar_cited").click(function() {
				$("#coletar_cited").html("coletando..."); 
				$.ajax({
  					method: "POST",
  					url: "' . base_url('index.php/oai/coletar_cited/' . $idbs) . '",
  					data: { name: "OAI", location: "PDF" }
					})
  					.done(function( data ) {
    						$("#coletar").html(data);
  					});
			});
			</script>
			';
		}
		return ($sx);
	}

}
