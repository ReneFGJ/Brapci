<?php
require("cab.php");
require("../_class/_class_issue.php");
$iss = new issue;

require('../_class/_class_publications.php');
$pb = new publications;

$issue = trim($dd[0]);
echo $iss->issue_legend($issue);
echo '<font class="link">';
echo $iss->journal_go($iss->journal);
echo ' | 
<A class="link" HREF="publications_issue_ed.php?dd0='.$iss->id.'&dd2='.round($iss->journal).'">'.msg('editar').'</A>
 |  
<A class="link" HREF="article_new.php?dd2='.$iss->id.'&dd1='.round($iss->journal).'">'.msg('novo_trabalho').'</A>
</font>
';

echo $pb->list_issue_articles($issue);

require("../foot.php");
?>
