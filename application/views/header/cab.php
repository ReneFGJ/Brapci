<?php
if (!isset($title_page))
	{
		$data['title'] = ':: Brapci ::';;
	} else {
		$data['title'] = $title_page . ' :: Brapci ::';		
	}

if (isset($metadata))
	{
		$data['metadata'] = $metadata;
	} else {
		$data['metadata'] = '';
	}
$this -> load -> view("header/header",$data);
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

$bt_home = '<a href="' . base_url('index.php') . '" class="btn btn-warning">' . msg('bt_home') . '</a>';
$bt_sign_in = '';
$bt_sign_out = '';
$bt_admin = '';
$bt_about = '<a href="' . base_url('index.php/brapci/about') . '">' . msg('bt_about') . '</a>';
$bt_thes = '';
$bt_auth = '';
$sign = '';
/********* Tipo de login */
if (strlen($user) > 0) {

	$perfil_show = '
		<div style="width: auto; text-align: right; padding: 10px 20px 0px 0px; border: 0px solid #ffffff; float: right; margin: 0px 10px 0px 0px;">
			<font color="white">' . $user . ' (' . $email . ')</font>
			<br>
			<font color="white"><i>' . $user_type . '</i></font>
		</div>';
	$bt_thes = '<a href="' . base_url('index.php/skos') . '">' . msg('bt_thesauros') . '</a>';
	$bt_auth = '<a href="' . base_url('index.php/autority') . '">' . msg('bt_autority') . '</a>';
	if ($nivel == 9) {
		$bt_admin = '<a href="' . base_url('index.php/admin') . '">' . msg('bt_admin') . '</a>';
	} else {
	}
	$bt_sign_out = '<a href="' . base_url('index.php/social/logout') . '">' . msg('bt_sign_out') . '</a>';

} else {
	$bt_sign_in = '<a href="' . base_url('index.php/social/login') . '" class="btn btn-warning">' . msg('bt_sign_in') . 'xx</a>';
}
?>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo base_url('index.php');?>"><img alt="Brand" src="<?php echo base_url('img/logo.png'); ?>"></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<a href="<?php echo base_url('index.php');?>">HOME <span class="sr-only">(current)</span></a>
					</li>
					<!--
					<li>
						<a href="#">Link</a>
					</li>
					-->
					<?php if (strlen($user) > 0) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('menu_admin');?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo base_url('index.php/admin');?>"><?php echo msg('admin_home');?></a>
							</li>
							<li>
								<a href="<?php echo base_url('index.php/admin/resumo_status/0');?>"><?php echo msg('ADMIN_TASK');?></a>
							</li>
						</ul>
					</li>
					<?php } ?>	
					<?php if (strlen($user) > 0) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('menu_tools');?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo base_url('index.php/tool/change01');?>"><?php echo msg('change_xls');?></a>
							</li>
							<li>
								<a href="<?php echo base_url('index.php/tool/change02');?>"><?php echo msg('gerar_list_nomes');?></a>
							</li>
							<li>
								<a href="<?php echo base_url('index.php/tool/change03');?>"><?php echo msg('gerar_matriz');?></a>
							</li>
							<li>
								<a href="<?php echo base_url('index.php/tool/change04');?>"><?php echo msg('gerar_pajek');?></a>
							</li>							
						</ul>
					</li>
					<?php } ?>	
					
					<!-- Controle de Autoridade --->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('menu_authority_controle');?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo base_url('index.php/authority/person');?>"><?php echo msg('menu_authority_person');?></a>
							</li>
						</ul>
					</li>
								
				</ul>
				<!---
				<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">
						Submit
					</button>
				</form>
				-->
				<ul class="nav navbar-nav navbar-right">
					<!---
					<li>
						<a href="#">Link</a>
					</li>
					-->
					<?php
					/************************************************************************************ SIGN IN ************************************************/
					if (strlen($user) == 0) {
						echo '<li><a href="'.base_url('index.php/social/login').'" class="navbar-nav2 btn-warning">'.msg('sign_in').'</a></li>';
					} else {
						$user_name = $_SESSION['user'];
						echo '<li class="dropdown">'.cr();
						echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$user_name.'<span class="caret"></span></a>'.cr();
						echo '<ul class="dropdown-menu">'.cr();
						echo '<li><a href="#">'.msg('my_account').'</a></li>'.cr();
						echo '<li role="separator" class="divider"></li>'.cr();
						echo '<li>'.$bt_sign_out.'</li>'.cr();
						echo '</ul>'.cr();
						echo '</li>'.cr();
						echo '';
					}
					?>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>