<?php
require("cab.php");

echo '<h1>Busca artigos</h1>';

require("_class/_class_article.php");

require("_class/_class_search.php");
$sr = new search;
if (strlen($dd[10]) > 0)
	{
		$_SESSION['ssid'] = $dd[10];
		$dd[1] = $dd[10];
	} else {
		$sr->sessao = $sr->session();		
	}

echo $sr->result_cited_selected($dd[1]);

require("foot.php");
?>
