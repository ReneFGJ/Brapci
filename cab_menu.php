<?
echo '
<div class="menu">
	<div class="menu_01"><A HREF="'.$http.'/index.php" class="link_menu">
			<img src="img/icone_home.png" border=0 height="20" >&nbsp;
			HOME</A></div>';
if (strlen($user_id) > 0)
	{
	echo '			
	<div class="menu_01"><A HREF="'.$http.'/mylibrary.php" class="link_menu">
			<img src="'.$http.'img/icone_my_library.png" border=0 height="20" >&nbsp;
			MY LIBRARY</A></div>';
	}

/* Seleção */
	echo '			
	<div class="menu_01"><A HREF="'.$http.'" class="link_menu" id="basket">
			<img src="img/icone_my_library.png" border=0 height="20" >&nbsp;
			0 SELECTED</A></div>';


/* Seleção */
	echo '			
	<div class="menu_01"><A HREF="'.$http.'index_cited.php" class="link_menu" id="cited">
			<img src="img/icone_my_library.png" border=0 height="20" >&nbsp;
			Busca por referências</A></div>';

echo '</div>';
?>
