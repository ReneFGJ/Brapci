<?php
require("cab.php");
require("cab_menu_left.php");

require("../_class/_class_ticket.php");
$tk = new ticket;
echo $tk->resumo();
 
echo '<form action="cited.php"><input type="submit" name="acao" value="CITED" style="width: 200px; height: 50px;"></form>';
require("../foot.php");
?>
