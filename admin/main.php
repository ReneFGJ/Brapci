<?php
require("cab.php");

echo '<table width="100%" border=0 cellpadding=4 cellspacing=0>';

echo '<TR valign="top">';

echo '<TD width="25%">';
echo '<iframe src="row_journals.php" name="frame_journal" border=0 width="100%" height="800" class="border0"></iframe>';
echo '<TD width="25%">';
echo '<iframe src="row_issue.php" name="frame_issue" border=0  width="100%" height="800" class="border0"></iframe>';
echo '<TD width="50%">';
echo '<iframe src="row_articles.php" name="frame_articles" border=0  width="100%" height="800" class="border0"></iframe>';
echo '</table>';

require("foot.php");
?>
