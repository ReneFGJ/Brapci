<?php
require("db.php");

$id = sonumero($dd[0]);
echo $id;

/* Recupera Link */
$link = 'http://www.sbu.unicamp.br/seer/ojs/index.php/rbci/article/view/4076';

require("ojs_download_pdf_method_1.php");
?>
