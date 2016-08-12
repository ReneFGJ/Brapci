<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php

			/* Titulo */
			if (isset($title)) {
				echo '<h1>' . $title . '</h1>';
			}

			/* Mostra formulario */
			echo $tela;

			if (isset($tela_array)) {
				print_r($tela_array);
			}
		?>
		</div>
	</div>
</div>