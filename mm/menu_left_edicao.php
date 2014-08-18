<?
$tit[0] = 'Menu Fontes';
$menu = array();
require("menu_left_edicao_sub.php");
//array_push($menu,array('Cap. livros','brapci_brapci_journal.php'));
//array_push($menu,array('Livros','brapci_brapci_journal.php'));
if (strlen($jidsel) > 0)
	{ 
	array_push($menu,array('Edições','journal_edition_mst.php'));
	array_push($menu,array('Edições (editar)','brapci_brapci_journals_issue.php'));	
	if (strlen($jedsel) > 0)
		{
		array_push($menu,array('Artigos','brapci_brapci_article_edition.php'));
		} else {
//		array_push($menu,array('Artigos',''));
		}
	} else {
//	array_push($menu,array('Edições',''));
//	array_push($menu,array('Artigos',''));
	}
array_push($menu,array('Autores','brapci_brapci_autor.php'));
array_push($menu,array('Palavras-chave','brapci_brapci_keyword.php'));
array_push($menu,array('Ferramentas','main_tools.php'));

require("menu_mostrar.php");
?>
