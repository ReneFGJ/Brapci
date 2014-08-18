<?php
class header
	{
		var $http;
		var $title = ':: Nome da página :: Editora Champagnat';
		var $charset = 'ISO 8859-1';
		var $user_name = 'Visitante';
		
		function header()
			{
				$http = $this->httpd;
				$sx = 
				'
    			<head>
    				<!-- NOME DA PÁGINA ONDE O USUÁRIO SE ENCONTRA-->
					<title>'.$this->title.'</title>
					<!-- META TAGS -->
						<meta charset="'.$this->charset.'">
						<meta name="autor" content="rene@sisdoc.com.br, evertonasme@gmail.com" />
						<link rev="made" href="rene@sisdoc.com.br, evertonasme@gmail.com" />
						<meta name="robots" content="noindex,nofollow">

						<!-- MAIN STYLE-->	
						<link rel="stylesheet" href="css/reol_main_cab.css" >
						<link rel="stylesheet" href="css/reol_menus.css" >
						<link rel="stylesheet" href="css/reol_footer.css" >
						<!-- END -->

						<!-- Classes styles -->
						<link rel="stylesheet" href="css/reol_tabelas.css"/>
						<link rel="stylesheet" href="css/reol_forms_links.css"/>
						<link rel="stylesheet" href="css/reol_fonts.css" >
						<link rel="stylesheet" href="css/reol_imgs.css" >
						<link rel="stylesheet" href="css/reol_print.css" >
						<!-- End-->

						<!-- Style Icones -->
						<link rel="stylesheet" href="css/font-awesome.css">
							<!-- end -->
					  	<!-- JScripts -->
							<script src="js/jquery-2.0.3.min.js"></script>
					  	<!-- end-->

				</head>
				';
				return($sx);
			}
		
		function cab()
			{
				$sx .= '<!DOCTYPE html>'.chr(13).chr(10);
				$sx .= '<html lang="pt-BR">'.chr(13).chr(10);
			
				$sx .= $this->header();
				
				$sx .= '<body>'.chr(13).chr(10);
				
				$sx .= '
				<div id="container">
					<!-- Cabeçalho -->
					<div id="header">
						<div class="logo"></div>
						<div class="content_cab">
							<div class="logado">
								<span class="log">

									<!-- AQUI O NOME DA PESSOA-->
									
									Olá <strong>'.$this->user_name.'</strong>

									</span>
								<a href="'.$http.'logout.php">Sair</a>
							</div>
						<div class="clear"></div>
						
						<!-- AQUI BUSCA-->
						 
						<form class="busca">
					        <input type="text" name="busca" placeholder="Search" value="">
					    	<label for="busca"><a href="#">Busca</a></label>
						</form>
						<!-- FIM BUSCA-->
					</div>
					</div>
					<div class="clear"></div>

					<section id="container_down">
					<!--end cabeçalho-->		
				';
				return($sx);
			}
			
		function menu()
			{
				$sx .= '
					<div class="main_menu_container">
						<!-- MENU NAV-->
						<div class="nav_main_menu">
							<ul>
						    	<li class="nav_main_menu_active"><a href="#">Home</a></li>
						        <li><a href="#">Publicações</a></li>
						        <li><a href="#">Edições</a>
						        <li id="menu1"><a href="#">Ordem de Serviço</a>
				                      <ul id="menu1_sub" class="menu_sub">
				                      	<li><a href="#">Incluir OS.</a></li>
				                      	<li><a href="#">Gerenciar OS.</a></li>
				                      	<li><a href="#">Relatório</a></li>
				                      </ul>
						        </li>
						        <li  id="menu2"><a href="#">Pareceristas</a>
						        	<ul id="menu2_sub" class="menu_sub">
				                      	<li><a href="pareceristas.php">Cadastro</a></li>
				                      	<li><a href="#">Parecer</a></li>
				                      	<li><a href="#">Parecerista</a></li>
				                      	<li><a href="#">Avaliações</a></li>
				                      	<li><a href="#">Comunicação</a></li>
				                      	<li><a href="#">Relatório</a></li>
				                    </ul> 
						        </li>
						        <li id="menu3"><a href="#">Em produção</a>
						        	<ul id="menu3_sub" class="menu_sub">
				                      	<li><a href="#">Inscrições p/ evento</a></li>
				                      	<li><a href="#">Relatório de acesso</a></li>
				                      	<li><a href="#">Parecerista</a></li>
				                      	<li><a href="#">Parecer</a></li>
				                      	<li><a href="#">Comunicação</a></li>
				                      	<li><a href="#">Avaliações</a></li>
				                      	<li><a href="#">Relatório de produção</a></li>
				                      </ul> 
						        <li><a href="#">Cadastro</a>
								<li><a href="#">F.A.Q.</a></li>
							</ul>
						</div>
						<!-- Fim MENU NAV-->
							<div class="menu_bottom">
							</div>
					</div>
					
				<script src="js/main_sub_menu.js"></script>
					';
				return($sx);				
			}
			
		function foot()
			{
				$sx .= '
				</section>
				</body></html>
				';
				return($sx);
				
			}
	}
?>