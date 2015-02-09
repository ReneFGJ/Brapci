<?
require ("db.php");
require ("_class/_class_header.php");
$hd = new header;
echo $hd -> head();

require ('../_class/_class_oauth_v1.php');
$user = new oauth;
$user -> token();

require ("../_class/_class_message.php");
echo '<h1>' . msg('publications') . '</h1>';

/* journals */
require("../_class/_class_journals.php");
$jnl = new journals;
echo $jnl->list_journals('','','row_issue.php','frame_issue');
?>
