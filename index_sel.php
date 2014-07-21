<?php
require("cab.php");

echo '<h1>Busca artigos</h1>';

require("_class/_class_article.php");

require("_class/_class_search.php");
$sr = new search;
if (strlen($dd[10]) > 0) { $sr->session_set($dd[10]); }
$sr->sessao = $sr->session();
echo $sr->result_search_selected($dd[1]);

echo $sr->result_cited();

require("foot.php");
?>
