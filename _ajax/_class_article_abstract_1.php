<?php
class ajax_class {
	function refresh($id) {
		global $dd, $acao;
		$ar = new article;
		$ar -> le($id);
		$sx = $ar->resumo;
		return($sx);
	}

}
?>
