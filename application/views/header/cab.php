<?php
$this -> load -> view("header/header");
$this -> load -> view('header/analytics.google.php');
$this -> load -> view('header/cab_ajax_loading');
?>
<style>
body {
	background-color: #2076A7;
	margin: 0px 0px 0px 0px;
	}
</style>

<div class="cab">
	<div class="menu_left">
		<UL class="nav_menu">
			<LI>
				<a href="<?php echo base_url('home');?>">
					<img src="<?php echo base_url('/img/icone_menu.png');?>" border=0 height="20" title="main menu">
			</LI>
		</UL>
	</div>
	<div class="geral">
		<div id="div1">
			&nbsp;&nbsp;<a href="<?php echo base_url('pt_BR');?>"><img src="<?php echo base_url('img/ididoma_br.png');?>" border=0 title="Portugues" alt="Portugu�s"></A>
			| 	<a href="<?php echo base_url('social/session/facebook/');?>"><button class="btn btn-facebook"><i class="fa fa-facebook"></i> | Login com o Facebook</button></A>
				|
				<a href="<?php echo base_url('social/session/google/');?>"><button class="btn btn-google-plus"><i class="fa fa-google-plus"></i> | Login com o Google+</button></A>
			<BR>
			<BR>
		</div>

		<div class="topo">
			<a href="https://twitter.com/BrapciCI"> <img src="<?php echo base_url('img/icone_twitter.png');?>" width="32" border=0 target="twitter"></A>
			<a href="https://www.facebook.com/brapci.ci" target="facebook"> <img src="<?php echo base_url('img/icone_face.png');?>" width="32" border=0 ></A>
		</div>
	</div>
</div>
<center>
<div class="versao"></div>
<BR><BR>