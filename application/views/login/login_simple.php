<!--- LOGIN --->
<link rel="STYLESHEET" type="text/css" href="<?echo base_url('css/style_login.css');?>">
<link rel="STYLESHEET" type="text/css" href="<?echo base_url('css/social-buttons.css');?>">

	<!--- LOGIN FORMULARIO --->
		<!-- Abertura do formulario -->
		<center>
		<table width="640" border=0 align="center" style="padding: 20px;">
			<tr valign="top"><td>
		<?php
			$link = index_page();
			if (strlen($link) > 0) { $link .= '/'; }
			$link = base_url($link.'social/login_local');
			echo form_open($link);?>
		
			<?php
			/* Login user */ 
			echo msg('login_name').'<BR>';
			$data = array('name'=>'dd1','class'=>'formulario-entrada','id'=>'login_name','value'=>get("dd1"));
			echo form_input($data);
			
			echo '<BR><BR>';
			
			/* Login user */ 
			echo msg('login_password').'<BR>';
			$data = array('name'=>'dd2','class'=>'formulario-entrada','id'=>'login_password','value'=>'');
			echo form_password($data);
			
			echo '<br><br>';			
		
			/* Submit Buttom */ 
			$data = array('name'=>'acao','class'=>'estilo-botao','id'=>'login_entrar','value'=>msg('login_enter'));
			echo form_submit($data);			
			?>
			<BR>
			<BR>
			</form>
			</td><td>
			<table width="200" class="border1" cellpadding="5" cellspacing="10" align="center">
				<tr><td class="lt1"><?php echo msg('login_social');?></td></tr>
				<tr><td><a href="<?php echo base_url('index.php/social/session/facebook/');?>"><button class="btn btn-facebook"><i class="fa fa-facebook"></i> | Login com o Facebook</button></A></td></tr>
				<tr><td><a href="<?php echo base_url('index.php/social/session/google/');?>"><button class="btn btn-google-plus"><i class="fa fa-google-plus"></i> | Login com o Google+</button></A></td></tr>
			</table>					
		<?php echo form_close(); ?>
		</td></tr>
		</table>
		</center>
	
	<!--- FIM --->

