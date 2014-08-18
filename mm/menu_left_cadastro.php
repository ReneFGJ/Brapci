<?
$tit[0] = 'Administração';
$menu = array();
array_push($menu,array('Avisos do sistema','brapci_brapci_avisos.php'));
array_push($menu,array('Páginas & Mensagens','brapci_ic_noticia.php'));
array_push($menu,array('Caracterização da pesquisa','brapci_brapci_metodologias_tp.php'));
array_push($menu,array('Metodologias & Técnicas','brapci_brapci_metodologias.php'));
array_push($menu,array('Instituições','brapci_instituicoes.php'));
array_push($menu,array('Periodicidade','brapci_brapci_periodicidade.php'));
array_push($menu,array('Idioma','brapci_ajax_idioma.php'));
array_push($menu,array('Cidade','brapci_ajax_cidade.php'));
array_push($menu,array('Estado','brapci_ajax_estado.php'));
array_push($menu,array('País','brapci_ajax_pais.php'));
array_push($menu,array('Seções','brapci_brapci_section.php'));
array_push($menu,array('Tipos de publicação','brapci_journal_tipo.php'));
require("menu_mostrar.php");
?>
