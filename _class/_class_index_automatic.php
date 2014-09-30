<?php
class index {
	function show_bookmark_buttom() {
		$onclick = ' onclick="alert(\'Segure e arrate este botão para sua barra de favoritos\'); return false;" ';
		$onclick .= ' href="javascript:document.getElementsByTagName(\'body\')[0].appendChild(document.createElement(\'script\'))';
		$onclick .= '.setAttribute(\'src\',\'http://www.brapci.inf.br/js/index_in_brapci.js\');" ';
		$sx .= '<a title="Index in Brapci" id="index-in-brapci" ' . $onclick . ');">Index in Brapci</a>';
		//save-to-mendeley
		return ($sx);
	}

}
?>
<style>
	a#index_in_brapci {
		display: inline-block;
		font-family: "Lucida Grande", "Segoe UI", Ubuntu, sans-serif;
		color: #fff;
		text-shadow: 0 1px 0 rgba(0,0,0,0.4);
		background: #999;
		border: 1px solid #666;
		padding: 2px 14px;
		border-radius: 20px;
		text-decoration: none;
		font-size: 13px;
		margin-top: 5px;
		box-shadow: inset 0 1px 2px rgba(0,0,0,0.2);
		position: relative;
		cursor: move;
	}
</style>
