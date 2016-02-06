<?php
$this -> load -> view("header/header");
$this -> load -> view('header/analytics.google.php');
$this -> load -> view('header/cab_ajax_loading');

$user = $this -> session -> userdata('user');
$email = $this -> session -> userdata('email');
$nivel = $this -> session -> userdata('nivel');
$perfil_show = '';
/* $user_type */
$user_type = msg('user');
switch ($nivel) {
	case '9' :
		$user_type = msg('perfil_coordenador');
		break;
	default :
		$user_type = msg('perfil_user');
		break;
}

$bt_home = '<a href="' . base_url('index.php') . '">' . msg('bt_home') . '</a>';
$bt_sign_in = '';
$bt_sign_out = '';
$bt_admin = '';
$bt_about = '<a href="' . base_url('index.php/brapci/about') . '">' . msg('bt_about') . '</a>';

$sign = '';
/********* Tipo de login */
if (strlen($user) > 0) {

	$perfil_show = '
		<div style="width: auto; text-align: right; padding: 10px 20px 0px 0px; border: 0px solid #ffffff; float: right; margin: 0px 10px 0px 0px;">
			<font color="white">' . $user . ' (' . $email . ')</font>
			<br>
			<font color="white"><i>' . $user_type . '</i></font>
		</div>';

	if ($nivel == 9) {
		$bt_admin = '<a href="' . base_url('index.php/admin') . '">' . msg('bt_admin') . '</a>';
	} else {
	}
	$bt_sign_out = '<a href="' . base_url('index.php/social/logout') . '">' . msg('bt_sign_out') . '</a>';

} else {
	$bt_sign_in = '<a href="' . base_url('index.php/social/login') . '">' . msg('bt_sign_in') . '</a>';
}
?>
<style>
	body {
		background-color: #2076A7;
		margin: 120px 0px 0px 0px;
	}
</style>
<body>
	<div class="cab_admin" style="height: 120px; z-index: 2;" >
		<?php echo $perfil_show; ?>
		<div id="navbar">
			<ul>
				<li>
					<a href="<?php echo base_url('index.php'); ?>"> <img alt="Brand" src="<?php echo base_url('img/logo.png'); ?>"> </a>
				</li>
				<li class="lis">
					<?php echo $bt_home; ?>
				</li>
				<li class="lis">
					<?php echo $bt_about; ?>
				</li>
				<li class="lis">
					<?php echo $bt_admin; ?>
				</li>
				<li class="lis">
					<?php echo $bt_sign_out; ?>
					<?php echo $bt_sign_in; ?>
				</li>
			</ul>
		</div>
		
	</div>
	<center>
		<!-----
		<div class="versao"></div>
		------>
		<BR>
		<BR>
