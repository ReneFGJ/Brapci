<?
if ($popup != 1)
{

$sx .= '
<div style="background-color: #FFFFFF;">
	<img src="../img/logo_brapci.png" height="90">
</div>
<div id="menu_bris">
	<UL>
	<LI><A HREF="index.php">HOME</A></LI>
	<LI><A HREF="publications.php">Journals</A></LI>
	<LI><A HREF="autores.php">Authors</A></LI>
	<LI><A HREF="keywords.php">Keywords</A></LI>
	<LI><A HREF="cited.php">Cited</A></LI>
	<LI><A HREF="oai.php">Harvesting</A></LI>
	<LI><A HREF="public.php">Public</A></LI>
	<LI><A HREF="processamento_i.php">Tools</A></LI>
	</UL>
</div>
<center>
<div id="context">
';
}
echo $sx;
?>