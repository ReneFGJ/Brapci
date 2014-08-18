<body bgcolor="#c0c0c0">
<center>
<?php
//require("sisdoc_graph_line_tipo_1.php");
require('OFC/open_flash_chart_object.php');
open_flash_chart_object( 1024, 450, 'http://'. $_SERVER['SERVER_NAME'] .'/brapci_rel_quanti_2_dados.php', false );
?>
