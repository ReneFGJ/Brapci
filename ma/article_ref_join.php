<?php
$xcab = '1';
$popup = 1;
require("cab.php");

require($include.'sisdoc_debug.php');
require('../_include/_class_form.php');
$form = new form;

require('../_class/_class_cited.php');
$cited = new cited;

$cited->join_cited($dd[0],$dd[1]);
?>
<script>
	close();
</script>
