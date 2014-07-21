<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';


$file = '../messages/msg_index.php';
if (file_exists($file)) { require($file); }

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';

$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array(msg('system'),msg('disk_space'),'disk_space.php')); 

array_push($menu,array(msg('system_message'),msg('message'),'')); 
array_push($menu,array(msg('system_message'),msg('message'),'message.php')); 
array_push($menu,array(msg('system_message'),msg('message_copile'),'message_create.php'));
array_push($menu,array(msg('system_message'),msg('message_email'),'ic.php'));
//bon_isencao_prod

array_push($menu,array(msg('calender'),msg('calender'),'calendario.php')); 
array_push($menu,array(msg('calender'),msg('calender_type'),'calendario_tipo.php')); 


array_push($menu,array(msg('faq'),'faq (CSF)','faq.php?dd80=CSF'));

array_push($menu,array(msg('country'),'Paises','admin_country.php'));  


array_push($menu,array(msg('suport'),'Idioma','apoio_idioma.php')); 

array_push($menu,array(msg('struct'),msg('campus'),'campus.php')); 
array_push($menu,array(msg('struct'),msg('centro'),'centro.php')); 
array_push($menu,array(msg('struct'),msg('curso'),'curso.php'));

array_push($menu,array(msg('instituition'),msg('instituition'),'instituicao.php')); 

array_push($menu,array(msg('scientific_iniciacion'),'Modalidade de Bolsas','pibic_bolsa_tipo.php')); 
array_push($menu,array(msg('scientific_iniciacion'),'Modalidade de Programas','pibic_bolsa_modalidade.php')); 
array_push($menu,array(msg('scientific_iniciacion'),'Estatus das Bolsas','pibic_status.php')); 

array_push($menu,array(msg('ethic_comite'),msg('nucleo'),'nucleo.php')); 
array_push($menu,array(msg('ethic_comite'),msg('submit_types'),'cep_submit_tipo.php')); 
array_push($menu,array(msg('ethic_comite'),msg('cep_documento_tipo'),'cep_ged_documento_tipo.php'));  

array_push($menu,array(msg('editora'),msg('journals'),'journals.php')); 
array_push($menu,array(msg('editora'),msg('journals_tipo'),'journals_tipo.php'));

array_push($menu,array(msg('ghost'),msg('ghost_mode'),'ghost.php'));  



array_push($menu,array(msg('pibic'),msg('pibic_areas'),'pibic_areas.php')); 
array_push($menu,array(msg('pibic'),msg('pibic_secoes'),'pibic_secoes.php'));
array_push($menu,array(msg('pibic'),msg('pibic_documento_tipo'),'pibic_ged_documento_tipo.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro'),'pibic_espelho.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro_resumo'),'pibic_espelho_resumo.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro_proc'),'pibic_espelho_processar.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro_month'),'pibic_espelho_create_month.php'));
array_push($menu,array(msg('pibic'),msg('pibic_mmanuscrit_type'),'submit_manuscrito_tipo.php'));

array_push($menu,array(msg('Lattes'),msg('importar_arquivo_lattes'),'cnqp_coleta.php'));
array_push($menu,array(msg('Lattes'),msg('lattes_producao'),'admin_lattes.php'));


//array_push($menu,array('Manutenção','Criar Tabelas','create_table.php')); 
///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{
		 if ($dd[1]==CharE($menu[$k][1])) {	header("Location: ".$menu[$k][2]); } 
		}
	}
?>

<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="index.php">
</TD></TR>
</TABLE>
<TABLE width="710" align="center" border="0">
<TR>
<?php
	$tela = menus($menu,"3");

require("../foot.php");	
?>