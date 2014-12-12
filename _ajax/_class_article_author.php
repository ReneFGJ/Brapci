<?php
class ajax_class {
	function refresh($id) {
		global $dd, $acao;
		$ar = new article;
		$ar -> le($id);
		$sx = $ar->resumo;
		$id = strzero($id,10);
		$sql = "select * from brapci_article_author 
				inner join brapci_autor on ae_author = autor_codigo
				where ae_article = '$id'
				order by ae_position		
		";
		$rlt = db_query($sql);
		$sx = '<UL class="autores">';
		while ($line = db_read($rlt))
			{
				$sx .= '<LI>'.trim($line['autor_nome_citacao']);'</LI>';
			}
		$sx .= '</UL>';
		
		return($sx);
	}

}
?>
