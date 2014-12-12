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
array_push($menu,array(msg('system_message'),msg('message'),'')); 
array_push($menu,array(msg('system_message'),msg('message'),'message.php')); 
array_push($menu,array(msg('system_message'),msg('message_copile'),'message_create.php'));

array_push($menu,array(msg('user'),msg('user_system'),'usuario.php'));  
array_push($menu,array(msg('user'),'Perfis','perfil.php')); 

array_push($menu,array(msg('calender'),msg('calender'),'calendario.php')); 
array_push($menu,array(msg('calender'),msg('calender_type'),'calendario_tipo.php')); 

array_push($menu,array(msg('faq'),'faq (CSF)','faq.php?dd80=CSF'));

array_push($menu,array(msg('suport'),'Idioma','apoio_idioma.php')); 

array_push($menu,array(msg('struct'),msg('centro'),'centro.php')); 
array_push($menu,array(msg('struct'),msg('curso'),'curso.php')); 

array_push($menu,array(msg('coleta_cnpq'),msg('cnpq_coleta'),'cnqp_coleta.php'));

array_push($menu,array(msg('scientific_iniciacion'),'Modalidade de Bolsas','pibic_bolsa_tipo.php')); 
array_push($menu,array(msg('scientific_iniciacion'),'Modalidade de Programas','pibic_bolsa_modalidade.php')); 
array_push($menu,array(msg('scientific_iniciacion'),'Estatus das Bolsas','pibic_status.php')); 

array_push($menu,array(msg('ethic_comite'),msg('nucleo'),'nucleo.php')); 
array_push($menu,array(msg('ethic_comite'),msg('submit_types'),'cep_submit_tipo.php')); 
array_push($menu,array(msg('ethic_comite'),msg('cep_documento_tipo'),'cep_ged_documento_tipo.php'));  

array_push($menu,array(msg('editora'),msg('journals'),'journals.php')); 
array_push($menu,array(msg('editora'),msg('journals_tipo'),'journals_tipo.php')); 



array_push($menu,array(msg('pibic'),msg('pibic_areas'),'pibic_areas.php')); 
array_push($menu,array(msg('pibic'),msg('pibic_secoes'),'pibic_secoes.php'));
array_push($menu,array(msg('pibic'),msg('pibic_documento_tipo'),'pibic_ged_documento_tipo.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro'),'pibic_espelho.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro_resumo'),'pibic_espelho_resumo.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro_proc'),'pibic_espelho_processar.php'));  
array_push($menu,array(msg('pibic'),msg('pibic_mirro_month'),'pibic_espelho_create_month.php'));
array_push($menu,array(msg('pibic'),msg('pibic_codigo_duplicados'),'pibic_codigo_duplicados.php'));

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