<?php
require("cab.php");
require("_class/_class_ajax.php");
require("_class/_class_search.php");
require("_class/_class_article.php");
require("_class/_class_email.php");
$email = new email;

/* journal page */
if (strlen($dd[90]) > 0)
	{
		redirecina("journal_view.php?dd0=".$dd[90]);
	}
$dd[2] = troca($dd[2],'&quote;','"');
$dd[2] = troca($dd[2],'"','´');	
$dd[2] = troca($dd[2],'\\','');

/* Search */
$sr = new search;
$sr->sessao = $sr->session();
echo $sr->busca_form();
echo $sr->tag;
echo '</table></div>';
require("foot.php");
?>

