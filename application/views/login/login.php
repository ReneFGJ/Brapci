<!--- LOGIN --->
<link rel="STYLESHEET" type="text/css" href="<?echo base_url('css/style_login.css'); ?>">
<link rel="STYLESHEET" type="text/css" href="<?echo base_url('css/social-buttons.css'); ?>">

<table width="100%" border=0 >
	<tr valign="top"><td>
			<h2>O que a Brapci?</h2>
			A Brapci é uma base de dados em acesso aberto que concentra as publicações em Ciência da Informação
		
			<h2>Contato Brapci</h2>
			<h3>Coordenação</h3>
			renefgj@gmail.com<BR> 
			santiagobufrem@gmail.com:<BR>
			</h2>
			
		<div id="login_versao"><?php echo $login_versao; ?> <?php echo $versao; ?></div>
	</div>
	</td><td>
	<!--- LOGIN FORMULARIO --->
	<div id="login_form">
		<!-- Abertura do formulario -->
		<?php
		$link = index_page();
		if (strlen($link) > 0) { $link .= '/';
		}
		$link = base_url($link . 'social/login_local' . $link_debug);
		echo form_open($link);
	?>
		
			<?php
			/* Login user */
			echo $login_name . '<BR>';
			$data = array('name' => 'dd1', 'class' => 'formulario-entrada', 'id' => 'login_name', 'value' => $lg_name);
			echo form_input($data);

			echo '<BR><BR>';

			/* Login user */
			echo $login_password . '<BR>';
			$data = array('name' => 'dd2', 'class' => 'formulario-entrada', 'id' => 'login_password', 'value' => '');
			echo form_password($data);

			echo '<BR>';
			echo '<font color="red">' . $login_error . '</font>';
			echo '<br><br>';

			/* Submit Buttom */
			$data = array('name' => 'acao', 'class' => 'estilo-botao', 'id' => 'login_entrar', 'value' => $login_entrar);
			echo form_submit($data);
			?>
			<BR>
			<BR>
			</form>
			<table width="200" class="border1" cellpadding="5" cellspacing="10" align="center" class="tabela00">
				<tr><td class="lt1"><?php echo msg('login_social'); ?></td></tr>
				<tr><td><a href="<?php echo base_url('index.php/social/session/facebook/'); ?>"><button class="btn btn-facebook"><i class="fa fa-facebook"></i> | Login com o Facebook</button></A></td></tr>
				<tr><td><a href="<?php echo base_url('index.php/social/session/google/'); ?>"><button class="btn btn-google-plus"><i class="fa fa-google-plus"></i> | Login com o Google+</button></A></td></tr>
			</table>					
			<div id="modo"><?php echo $modo; ?></div>
		<?php echo form_close(); ?>
	</div>
	
	<!--- FIM --->
</div>
</td></tr>
</table>
