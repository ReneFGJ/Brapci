<?php
require("cab.php");
require('_class/_class_article.php');
$art = new article;
$id = round($dd[0])+1;
?>
<META HTTP-EQUIV="Refresh" CONTENT="60; URL=import.php?dd0=<?=($id-1);?>">
<?php
$art->inport_pdf($id);
?>
<font color="black">
	<H1><?=date("d/m/Y H:i:s");?></h1>
</font>

<META HTTP-EQUIV="Refresh" CONTENT="10; URL=import.php?dd0=<?=($id);?>">