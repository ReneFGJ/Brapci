<?php
$include = '../';
$no_cab = 1; /* n�o mostrar cabecalho */
/* Estitlos adicionais */ 
$style_add = array('style_bris.css');

require($include.'cab.php');
if ($popup != 1)
{
$sx = '
<style>
	body { background-color: #FFFFFF; }
</style>
<div style="background-color: #FFFFFF;">
	<img src="../img/logo_brapci.png" height="90">
</div>
<div id="menu_bris">
	<UL>
	<LI><A HREF="index.php">HOME</A></LI>
	<LI><A HREF="publications.php">Journals</A></LI>
	<LI><A HREF="autores.php">Authors</A></LI>
	<LI><A HREF="cited.php">Cited</A></LI>
	<LI><A HREF="oai.php">Harvesting</A></LI>
	<LI><A HREF="public.php">Public</A></LI>	
	</UL>
</div>
<center>
<div id="context">
';
}
echo $sx;
?>
	

