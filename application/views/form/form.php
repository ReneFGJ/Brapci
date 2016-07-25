<div class="container">
	<div class="row">
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
</div>
</div>