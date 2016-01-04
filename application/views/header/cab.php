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
		margin: 120px 0px 0px 0px;
	}
	
.navbar-nav > li > a:hover {
   color: #fff;
   background-color: #1c4060;
}
.nav > li > a {
   color: #2076a7; 
}

.dropdown-menu {
    background-image: none;
    background-color: #2076A7;
    color: #e8e8e8; 
}

.dropdown-menu > li > a:hover {
   color: #fff;
   background-color: #1c4060;
}
</style>
<body>
	<div class="cab_admin" style="height: 120px; z-index: 2;" >
		<nav class="navbar navbar-fixed-top" style="background-color: #1c4060; border: 0px;">
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<div class="navbar-header">
					<!-- Collect the nav links, forms, and other content for toggling -->
					<ul class="nav navbar-nav">
						<li>
							<div class="container-fluid">
								<div class="navbar-header">
									<a class="navbar-brand" href="<?php echo base_url('index.php'); ?>"> <img alt="Brand" src="<?php echo base_url('img/logo.png'); ?>"> </a>
								</div>
							</div>
						</li>						
						<li>
							<a href="<?php echo base_url('index.php'); ?>">Home <span class="sr-only">(current)</span></a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sobre a Brapci <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo base_url("index.php/brapci/about");?>">Histórico</a>
								</li>
								<li>
									<a href="<?php echo base_url("index.php/brapci/indicators");?>">Indicadores</a>
								</li>
								<li role="separator" class="divider"></li>
								<li>
									<a href="<?php echo base_url("index.php/brapci/indicator_authors");?>">Autores Rank</a>
								</li>
								<li role="separator" class="divider"></li>
								<li>
									<a href="#">One more separated link</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="<?php echo base_url('index.php/social/login'); ?>">
							<button type="submit" class="btn btn-primary">
								Sign in
							</button> </a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<center>
		<div class="versao"></div>
		<BR>
		<BR>
