<?
require ("db.php");
require ("_class/_class_header.php");
$hd = new header;
echo $hd -> head();

require ('../_class/_class_oauth_v1.php');
$user = new oauth;
$user -> token();

require ("../_class/_class_message.php");

/* journals */
require("../_class/_class_journals.php");
$jnl = new journals;
$jnl->le($dd[0]);

echo $jnl->mostra();

$journal = strzero($dd[0],7);

echo $jnl->journals_articles_lista_simple('',$journal);

echo date("d/m/Y H:i:s");
?>
