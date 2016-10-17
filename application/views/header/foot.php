<?php
if (isset($_SESSION['bp_session'])) {
	$ssid = $_SESSION['bp_session'];
} else {
	$ssid = '';
}

/* Page count */
$pc = page_count();
?>
<!------- Facebook -------->
<div id="fb-root"></div>
<script>
	( function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id))
				return;
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.7&appId=547858661992170";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk')); 
</script>

<div class="container-fluid" id="conteudo_foot" style="background-color: #cccccc; padding: 20px;">
	<div class="row">
		<div class="col-md-3 col-md-offset-2">
			<b>BRAPCI</b> - Base de Dados em Ciência da Informação<br>
			Acervo de Publicações Brasileiras em Ciência da Informação<br>
			Universidade Federal do Paraná<br>
			Versão 3.1 beta | 2016<br>
			brapcici@gmail.com
			<br>
			<br>
		</div>
		<div class="col-md-1">
			<div class="fb-like" data-href="https://www.facebook.com/brapci.ci/" data-layout="box_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
			<br>
			<br>
		</div>
		<div class="col-md-3 hidden-xs">
			<div class="fb-page"
			data-href="https://www.facebook.com/brapci.ci/"
			data-width="400"
			data-hide-cover="false"
			data-show-facepile="false"
			data-show-posts="false"
			show_facepile="true"></div>
			<br>
			<br>			
		</div>
		<div class="col-md-2">
			<img src="<?php echo base_url('img/logo/oai_access.png'); ?>"><br>
			<?php echo msg('session') . ':'; ?>
			<b><?php echo $ssid; ?></b>			
		</div>
	</div>
</div>
