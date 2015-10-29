<?php
$this -> load -> view("header/header");
$this -> load -> view('header/analytics.google.php');
$this -> load -> view('header/cab_ajax_loading');

$user = $this -> session -> userdata('user');
$email = $this -> session -> userdata('email');
$nivel = $this -> session -> userdata('nivel');
?>
<style>
	body {
		background-color: #2076A7;
		margin: 100px 0px 0px 0px;
	}
</style>

<div class="cab_admin" style="z-index: 999;">
	<table width="98%" border=0 align="center">
		<tr valign="top">
			<td class="lt1" >
			<ul id="nav_cab">
				<li class="nav_menu">
					<a href="<?php echo base_url("index.php/home"); ?>">home</a>
				</li>
				<li class="nav_menu">
				<?php if (strlen($user) > 0) {
					echo '<a href="'.base_url("index.php/social/logout").'"><font color="#9090ff">logout<font></a>';
				} else {
					echo '<a href="'.base_url("index.php/social/login").'">login</a>';
				}
				
				/* Admin */
				if ($nivel == 9)
					{	
						echo '</li>'.cr();
						echo '<li class="nav_menu">';
						echo '<a href="'.base_url("index.php/admin").'">admin</a>';						
					}
				?>					
				</li>
				<li class="nav_menu">
					<a href="<?php echo base_url("index.php/brapci/about"); ?>">sobre a brapci</a>
				</li>
			</ul></td>
			<td align="right" width="300" rowspan=4 ><img src="<?php echo base_url('img/logo_cab.png'); ?>"></td>
		</tr>
		<tr>
			<td>
				<a href="https://twitter.com/BrapciCI"> <img src="<?php echo base_url('img/icone_twitter.png'); ?>" width="32" border=0 target="twitter"></A>
				<a href="https://www.facebook.com/brapci.ci" target="facebook"> <img src="<?php echo base_url('img/icone_face.png'); ?>" width="32" border=0 ></A>
			</td>
		</tr>
		<?php if (strlen($user) > 0) { ?>
		<tr>
			<td>
				<font class="lt1"><font color="#ffffff"><?php echo $user; ?>(<?php echo $email;?>)</font></font>
			</td>
		</tr>
		<? } ?>
	</table>
	<!-- Logo -->
	<!-- login -->
	<!-- Menu -->
	<!-- Basket -->
	<!-- Export -->
	<!-- Send e-mail-->
</div>

<!-- Cabecalho -->
<div class="cab_adminx" style="z-index: 999;">
	<div class="menu_left">

	</div>
	<div class="geral">
		<div id="div1">
			<?php
			//echo '&nbsp;&nbsp;<a href="' . base_url('index.php/home/pt_BR') . '"><img src="' . base_url('img/ididoma_br.png') . '" border=0 title="Portugues" alt="Portugues"></A> | ';

			/* se nao estiver logado */
			if (strlen($user) == 0) {
				echo '';
			} else {
				/* Mostra nome se logado */
				echo $user . ' (' . $email . ')';
				switch ($nivel) {
					case '9' :
						echo ' - Coordenador';
						break;
					default :
						echo ' - ' . $nivel;
						break;
				}
				echo '<BR><A href="' . base_url('index.php/home/logout') . '"><I>logout</I></A>';
			}
			?>
		</div>

	</div>
</div>
<center>
	<div class="versao"></div>
	<BR>
	<BR>
