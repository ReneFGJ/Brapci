<?php
require("cab.php");

require("../_class/_class_ticket.php");
$tk = new ticket;
echo $tk->resumo();

require("../foot.php");
?>
