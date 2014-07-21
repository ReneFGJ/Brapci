<?php
require("cab.php");

echo $user->logout();
redirecina($user->site.'index.php');
?>
