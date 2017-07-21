<?php
/***********************************************************************************/
if (!isset($title_page))
    {
        $data['title'] = 'Brapci - Base de Dados em Ciência da Informação';
    } else {
        $data['title'] = $title_page . ' :: Brapci ::';     
    }

if (isset($metadata))
    {
        $data['metadata'] = $metadata;
    } else {
        $data['metadata'] = '';
    }
?>    
<header>
	<head lang="pt-br">
		<meta charset="utf-8">
		<title><?php echo $data['title'];?></title>
		<META NAME="title" CONTENT="<?php echo $data['title'];?>">
		<META NAME="url" CONTENT="http://www.brapci.inf.br/">
		<META NAME="description" CONTENT="Base de dados de Periódicos em Ciência da Informação publicadas no Brasil desde 1972.">
		<META NAME="keywords" CONTENT="artigos científicos, revistas científicas, ciência da informação, biblioteconomia, arquivologia">
		<META NAME="copyright" CONTENT="Brapci">
		<LINK REV=made href="brapcici@gmail.com">
		<META NAME="language" CONTENT="Portugues">
		<META NAME="Robots" content="All">
		<META NAME="City" content="Curitiba">
		<META NAME="State" content="PR - Paraná">
		<META NAME="revisit-after" CONTENT="7 days">
		<META HTTP-EQUIV="Content-Language" CONTENT="pt_BR">

		<script src="<?php echo base_url('js/jquery.js');?>"></script>
		<!-- Bootsratp -->
		<!-- CSS -->
		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap.css');?>">

		<!-- JS -->
		<script src="<?php echo base_url('js/bootstrap.min.js');?>"></script>
		<script src="<?php echo base_url('js/jquery.mask.js');?>"></script>
		<script src="<?php echo base_url('js/sisdoc_form.js');?>"></script>
		<link rel="stylesheet" href="<?php echo base_url('css/m/style.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('css/style_roboto.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('css/m/style_topmenu.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.css');?>">

</header>

