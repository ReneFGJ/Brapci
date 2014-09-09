<?php
require("../db.php");
?>

<head>
	<title>::Brapci - Base Referencial de Periodicos em Ciência da Informacao::</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="STYLESHEET" type="text/css" href="../css/style.css">
	<link rel="STYLESHEET" type="text/css" href="../css/style_menu.css">
	<link rel="STYLESHEET" type="text/css" href="../css/style_roboto.css">
	<link rel="shortcut icon" href="http://www.brapci.inf.br/favicon.png" />
	
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.corner.js"></script>
	<script type="text/javascript" src="../js/jquery.example.js"></script>
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
</head>
<BODY>
<?
require('../include/_class_form.php');
$form = new form;
$tabela = 'brapci_article_author';

$cp = array();
array_push($cp,array('$H8','id_ae','',True,True));
array_push($cp,array('$O 0:NAO&1:SIM','ae_aluno','Estudante',False,True));
array_push($cp,array('$O 0:NAO&1:SIM','ae_professor','Professor',False,True));
array_push($cp,array('$O 0:NAO&1:SIM','ae_ss','Stricto Sensu',False,True));
//array_push($cp,array('$O 0:NAO&1:SIM','ae_pos','Pos-Graduação',False,True));
array_push($cp,array('$O 0:NAO&1:SIM','ae_mestrado','Mestrado',False,True));
array_push($cp,array('$O 0:NAO&1:SIM','ae_doutorado','Doutorado',False,True));
array_push($cp,array('$O 0:NAO&1:SIM','ae_profissional','Profissional',False,True));

array_push($cp,array('$Q inst_nome:inst_codigo:select * from instituicoes where inst_pref=1','ae_instituicao','Instituicao',False,True));

$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		echo 'FIM';
		exit;		
	} else {
		echo $tela;
	}



function msg($id)
	{
		return($id);
	} 
?>
