<?php
require("cab.php");
require("../include/sisdoc_data.php");

require("../_class/_class_ticket.php");
$tk = new ticket;

echo $tk->list_bugs($dd[1]);

require("../foot.php");
?>
