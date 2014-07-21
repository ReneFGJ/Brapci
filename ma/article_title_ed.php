<?
$include = '../'; 
require("../db.php");
require($include.'sisdoc_debug.php'); 

require("../_class/_class_message.php");

require("../_class/_class_article.php");
$art = new article;
?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras_popup.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
$tabela = "brapci_article";
$cp = $art->cp_title();

require('../include/sisdoc_colunas.php');
require($include.'_class_form.php');
$form = new form;
$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		if (strlen($dd[0]) > 0) { $art->update_title_asc($dd[0]); }
		require("../close.php");
	} else {
		echo $tela;
	}

?>