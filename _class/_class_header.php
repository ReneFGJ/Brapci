<?php
class header
	{
	var $class;
	var $title;
	var $path;
	var $name;
	
	function search()
		{
			global $dd;
			$bt = 'BUSCAR';			
			$sx = '
			<div id="busca">
				<form method="get" action="'.page().'">
				<span class="titulo-busca">Deseja fazer uma busca rápida?</span><br />
				<input type="text" name="dd10" value="'.$dd[10].'" class="campo-busca" placeholder="Procure por assuntos, pesquisadores, pesquisas... " />
				<input type="submit" name="acao" class="botao-busca" value="'.$bt.'">
				</form>
			</div>
			';
			return($sx);		
		}
	
	function foot()
		{
			$sx .= '
				</table>
				<table width="100%" cellpading=0 cellspacing=0 border=0 class="tabela00">
				<TR><TD>
				<div class="rodape" id="rodape">
					<a href="http://www.pucpr.br/" target="_new">
					<img src="'.http.'img/logo-puc-rodape.png" class="logo-puc-rodape" border=0 />
					</a>			
				</div>
				</table>
			</body>	
			</html>
			';
			return($sx);
		}
		
	function breadcrumb($path=array())
		{
		if (count($path) > 0)
			{
			$sx .= '<div id="breadcrumb">'.$cr;
			for ($r=0;$r < count($path);$r++)
				{
					if ($r > 0) { $sx .= ' > '; }
					$sx .= '<a href="'.$path[$r][1].'">'.$path[$r][0].'</a>'.$cr;
				}
			$sx .= '</div>'.$cr;
			}
		return($sx);			
		}
		
	function id_pagina($tp='')
		{
			if ($tp=='nc') {
					$this->class="identificacao-atividades";
					$this->title=''; 		
				}			
			if ($tp=='at') {
					$this->class="identificacao-atividades";
					$this->title='Atividades'; 		
				}
			if ($tp=='pf') {
					$this->class="identificacao-perfil";
					$this->title='Perfil do Pesquisador'; 		
				}			
			if ($tp=='ic') {
					$this->class="identificacao-iniciacao-cientifica";
					$this->title='Iniciação Científica'; 
					$this->path = '../';
				}
			if ($tp=='in') {
					$this->class="identificacao-indicadores";
					$this->title='Indicadores de Pesquisa';
				}
			if ($tp=='cp') {
					$this->class="identificacao-pesquisa";
					$this->title='Diretoria de Pesquisa';
				}	
			if ($tp=='po') {
					$this->class="identificacao-indicadores";
					$this->title='Pós-Graduação';
				}	
			if ($tp=='sm') {
					$this->class="identificacao-pesquisa";
					$this->title='SEMIC & Mostra de Pesquisa';
				}		
		}
		
	function mostra($tp='')
		{
			$this->name = $tp;
			$this->id_pagina($tp);	
			$sx = $this->head();
			$sx .= $this->cab();
			if ($tp!='nc') { $sx .= $this->menus(); }
			$sx .= '<table cellpadding=0 cellspacing=0 id="total" align="center" border=0>'.$cr;
			$sx .= '<TR><TD>';
			$sx .= $this->breadcrumb();
			return($sx);
		}	
		
	function menus()
		{
		global $menu;
		if (empty($menu)) 
				{
				 $menu = array(); 
				 array_push($menu,array('home','main.php'));
				}
		array_push($menu,array('voltar',http.'main.php'));
				
		$sx .= '<div id="menu-pagina">'.$cr;
		$sx .= '<ul>'.$cr;
		for ($r=0;$r < count($menu);$r++)
			{
			$sx .= '<a href="'.$menu[$r][1].'"><li>'.msg($menu[$r][0]).'</li></a>'.$cr;
			}
		$sx .= '</ul>'.$cr;
		$sx .= '</div>'.$cr;
			
		return($sx);			
		}
		
	function head()
		{
		global $LANG;
		$cr = chr(13).chr(10);
		$pth = $this->path;
		header ('Content-type: text/html; charset=ISO-8859-1');
		//$sx .= ''.$cr;
		$sx .= '<head>'.$cr;
    	$sx .= '<META HTTP-EQUIV=Refresh CONTENT="3600; URL='.http.'logout.php">'.$cr;
		$sx .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
        $sx .= '<meta name="description" content="">'.$cr;
        $sx .= '<link rel="shortcut icon" type="image/x-icon" href="'.http.'favicon.ico" />'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_cabecalho.css">'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_midias.css">'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_body.css">'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_fonts.css">'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_botao.css">'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_table.css">'.$cr;
        $sx .= '<link rel="stylesheet" href="'.http.'css/style_font_roboto.css">'.$cr;
        $sx .= '<link rel="stylesheet" href="'.http.'css/style_font-awesome.css">'.$cr;
		$sx .= '<link rel="stylesheet" href="'.http.'css/style_form.css">'.$cr;
		
        //$sx .= '<link rel="stylesheet" href="'.http.'css/estilo.css">'.$cr;
        //$sx .= '<link rel="stylesheet" href="'.http.'css/estilo_fontes.css">'.$cr;
        
		//$sx .= '<link rel="stylesheet" href="'.http.'css/table.css">'.$cr;
        		
        //$sx .= '<link rel="stylesheet" href="'.http.'css/font-awesome.css">'.$cr;
		//$sx .= '<link rel="stylesheet" href="'.http.'css/table.css">'.$cr;

		
		$sx .= '<script language="JavaScript" type="text/javascript" src="'.http.'js/jquery-1.7.1.js"></script>'.$cr;
		$sx .= '<script language="JavaScript" type="text/javascript" src="'.http.'js/jquery.corner.js"></script>'.$cr;
    	$sx .= '<title>CIP | Centro Integrado de Pesquisa | PUCPR</title>'.$cr;
		$sx .= '</head>';
		
		$sx .= "
		<script>
  			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', 'UA-12712904-10', 'pucpr.br');
  			ga('send', 'pageview');
			</script>		
			";
		
		$LANG='pt_BR';
		return($sx);
		}

	function cab() 
		{
		global $ss,$nw,$include,$perfil;
		
		require_once($include.'sisdoc_security_pucpr.php');
		$nw = new usuario;
		$nw->Security();
		$nw->LiberarUsuario();
		$ss = $nw;

		$sx = '<body>'.$cr;
		
		/* Cabecalho para impressao */		
			$sx .= '
			<table width="100%" id="cabecalho-impressao-cip" border=0>
			<TR><TD width="95%"><img  src="'.http.'cip/img/logo-cip-print.png" height="80" />
			<TD align="right" width="5%"><img id="cabecalho-impressao-pucpr" src="'.http.'cip/img/logo-pucpr-pb.png" height="80" />
			<TR class="lt0"><TD>'.date("d/m/Y H:i:s").' - '.$nw->user_nome.' ('.$nw->user_cracha.')
			</table>
			';

		/* cabecalho da página */
		$sx .= '<table border=0 width="100%" cellpadding=0 cellspacing=0 id="cabecalho-user-screen">';
			$sx .= '<TR valign="top">';
			$sx .= '<TD width="10%" rowspan=2>&nbsp;';
		
			$sx .= '<TD width="40%" >';
				$sx .= '<a href="'.http.'main.php"><img src="'.http.'img/logo-cip.png" id="logo-cip" /></a>'.$cr;
		
			$sx .= '<TD width="40%" align="right" >';
				$sx .= '<div id="menu-user"><BR>'.$cr;
				$sx .= '<ul>'.$cr;
				/* Libera Item do Administrador */
				if (($perfil->valid('#ADM#SCR#COO')))
					{ $sx .= '<a href="'.http.'/admin/"><li><i class="icon-wrench"></i> Administração</li></a>'.$cr; }
				$sx .= '	<a href="'.http.'minha_conta.php"><li><i class="icon-refresh"></i> <NOBR>Atualizar dados</nobr></li></a>'.$cr;
				$sx .= '	<a href="'.http.'logout.php"><li><i class="icon-remove"></i> Sair</li></a>'.$cr;
				$sx .= '</div>';
			$sx .= '</ul>'.$cr;
			
		$sx .= '<TD width="10%" rowspan=2>&nbsp;';
		
		/* Parte II */
		$sx .= '<TR valign="bottom">';
	
			/* Dados do usuario */
			$nome = trim($nw->user_nome);
			if (strlen($nome)==0) { $nome = $nw->user_login; }
			$cracha = trim($nw->user_cracha);
			if (strlen($cracha) > 0) { $nome .= ' ('.$cracha.')'; }
			
			$sx .= '<TD valign="top" height="20">';
			if (strlen(trim($this->title)) > 0)
				{
				$sx .= '<div id="identificacao-pagina" class="'.$this->class.'">';
				$sx .= $this->title;
				$sx .= '</div>';
				}
			
			$sx .= '<TD class="seja-bem-vindo" valign="top">';
			$sx .= 'Seja bem-vindo, '.$nome.$cr;
			$sx .= '</div>'.$cr;
								
		$sx .= '<TD width="10%">&nbsp;';
		$sx .= '</table>';
		return($sx);		
		}
	}
?>