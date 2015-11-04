<?php
class doi extends CI_model {
	function trata_doi($doi) {
		return ($doi);
	}

	function find_doi_in_abstract($id) {
		/* Salva alteracao */
		if (strlen($id) > 0) {
			$op = $this -> input -> get("dd2");
			$sql = "select * from brapci_article where id_ar = " . $id;
			$rrr = $this -> db -> query($sql);
			$rrr = $rrr -> result_array();
			if (count($rrr) > 0) {
				$line = $rrr[0];
				$ars = $line['ar_resumo_1'];

				$ars = trim(troca($ars, $op, ''));
				$op = troca($op, 'http://dx.doi.org/', '');

				$sql = "update brapci_article set ar_resumo_1 = '$ars',
						ar_doi = '$op' 
						where id_ar = $id ";
				$rrr = $this -> db -> query($sql);
				redirect(base_url('index.php/admin/doi_find_abstract'));
			}
		}

		/* Busca novos */
		$sx = '<h1>' . msg('doi_select_valid') . '</h1>';
		$sx .= '<p>' . msg('doi_select_info') . '</p>';

		/* Count */
		$sql = "select count(*) as total from brapci_article where ar_resumo_1 like '%http://dx.doi.org%' limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$total = $rlt[0]['total'];
		$sx .= '<p>' . msg('found') . ' ' . $total . ' ' . msg('records') . '</p>';

		/* Select next */

		$sql = "select * from brapci_article where ar_resumo_1 like '%http://dx.doi.org%' limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$a1 = trim($line['ar_resumo_1']);

			$sx .= '<form action="' . base_url('index.php/admin/doi_find_abstract/' . $line['id_ar']) . '">';
			$sx .= '<input type="submit" value="' . msg('set_this') . '">';
			$sx .= '<br>';

			$doi = substr($a1, strpos($a1, 'http://dx.doi.org'), 300);
			$doi = substr($doi, 0, strpos($doi, ' '));

			for ($r = strlen($doi); $r > 25; $r--) {
				$sx .= '<input type="radio" name="dd2" value="' . substr($doi, 0, $r) . '">';
				$sx .= '<a href="' . substr($doi, 0, $r) . '" target="_new">';
				$sx .= substr($doi, 0, $r);
				$sx .= '</a>';
				$sx .= '<br>';
			}
			$sx .= '</form>';
		}
		
		return ($sx);
	}

}
?>
