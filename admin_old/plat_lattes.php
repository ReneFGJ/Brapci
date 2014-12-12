<?php
require("cab.php");
require("../_class/_class_lattes.php");
$lattes = new lattes;


$link = 'http://buscatextual.cnpq.br/buscatextual/visualizacv.do?id=K4791249J9';
$s = $lattes->http_lattes_site($link);
echo $s;
?>
