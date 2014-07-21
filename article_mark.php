<?php
require("db.php");

$trab = $dd[1];
$value = $dd[2];

require("_class/_class_search.php");
$search = new search;
$ssid = $search->session();
$search->sessao = $ssid;
$search->mark($trab,$value);

$selected = $search->mark_resume();

if ($selected > 0)
	{
		$link = '<A HREF="'.$http.'index_sel.php" class="link_menu">';
		echo $link;
		echo '<img src="'.$http.'img/icone_my_library.png" width="20">';
		echo '&nbsp;';
		echo 'Total de '.$selected.' artigos selecionados';
		echo '</A>';
	} else {
		$link = '<A HREF="'.$http.'mylibrary.php" class="link_menu">';
		echo $link;
		echo '<img src="'.$http.'img/icone_my_library.png" width="20" border=0>';
		echo '</A>';
		echo '&nbsp;';
		echo ('sem seleção');		
	}

?>
