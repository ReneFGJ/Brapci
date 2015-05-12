<?php

/* Titulo */
echo '<h1>'.$title.'</h1>';

/* Mostra formulario */
echo $tela;

if (isset($tela_array))
{
	print_r($tela_array);
}

?>