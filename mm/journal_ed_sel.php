<?
require("db.php");
echo $dd[0];
setcookie("journal_ed",$dd[0]);
redirecina("article_edition.php?dd0=".$dd[0]);
?>