<?php
require ("cab.php");
echo '<h2>kw_key</h2>';
$sql = "ALTER TABLE `brapci_base`.`brapci_article_keyword` ADD INDEX `kw_key` (`kw_keyword`) COMMENT 'kw_key';";
$rlt = db_query($sql);

echo '<h2>kw_art</h2>';
$sql = "ALTER TABLE `brapci_base`.`brapci_article_keyword` ADD INDEX `kw_art` (`kw_article`) COMMENT 'kw_art';";
$rlt = db_query($sql);

echo '<h2>kw_key_art</h2>';
$sql = "ALTER TABLE `brapci_base`.`brapci_article_keyword` ADD INDEX `kw_key_art` (`kw_keyword`, `kw_article`) COMMENT 'kw_key_art';";
$rlt = db_query($sql);

echo '<h2>kw_art_key</h2>';
$sql = "ALTER TABLE `brapci_base`.`brapci_article_keyword` DROP INDEX `kw_art_key`, ADD INDEX `kw_art_key` (`kw_article`,`kw_keyword`) COMMENT 'kw_art_key';";
$rlt = db_query($sql);

?>
