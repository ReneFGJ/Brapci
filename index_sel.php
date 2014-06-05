<?php
require("cab.php");

echo '<h1>Busca artigos</h1>';

require("_class/_class_article.php");

require("_class/_class_search.php");
$sr = new search;
$sr->sessao = $sr->session();
echo $sr->result_search_selected($dd[1]);

require("foot.php");
?>
