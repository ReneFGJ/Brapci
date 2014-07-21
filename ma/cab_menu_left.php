<link rel="STYLESHEET" type="text/css" href="../css/style_menus.css">
<div class="navbox">
	<ul class="nav">
	<?php
	$sql .= "select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem ";
	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
			echo '<li><a href="publications.php?dd0='.trim($line['jtp_codigo']).'">'.trim($line['jtp_descricao']).'</a></li>';
		}
	?>
	<li><a href="diris.php">DIR!S</a></li>
</ul>
</div>