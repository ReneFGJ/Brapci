<?
$breadcrumbs=array();
array_push($breadcrumbs, array('pos_graduacao.php','Pós-graduação'));

require("cab_cip.php");
require("../db_diris.php");
echo '--->'.$base_name;
exit;
require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';
$menu = array();

		$programa_nome = $_SESSION['pos_nome'];
		$programa_pos = $_SESSION['pos_codigo'];
		$programa_pos_anoi = $_SESSION['pos_anoi'];
		$programa_pos_anof = $_SESSION['pos_anof'];
		//if (strlen($programa_pos_anoi)==0) { $programa_pos_anoi = 1996; }
		//if (strlen($programa_pos_anof)==0) { $programa_pos_anof = date("Y"); }
		
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('Programas','Pós-Graduação','pos_graduacao_resume.php'));

array_push($menu,array('Relatório Consultor Externo - Convidado','Seleção do Programa de Pós-Graduação (1)','pos_graduacao_1.php'));

if (strlen($programa_pos) == 0)
	{
					
	} else {
		if (strlen($programa_pos_anoi) == 0)
			{
				array_push($menu,array('Relatório Consultor Externo - Convidado','Definição da Delimitação da análise','pos_graduacao_0.php'));
			} else {		
				array_push($menu,array('Relatório Consultor Externo - Convidado','Delimitação da análise entre '.$programa_pos_anoi.' e '.$programa_pos_anof,'pos_graduacao_0.php'));
				array_push($menu,array('Relatório Consultor Externo - Convidado','Programa: <B>'.$programa_nome.'</B>',''));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Corpo Docente (3)','pos_graduacao_3.php')); 
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Compilação de dados do programa (4)',''));
				array_push($menu,array('Relatório Consultor Externo - Convidado','____Publicações Docentes (4a)','pos_graduacao_4.php'));
				array_push($menu,array('Relatório Consultor Externo - Convidado','____Publicações Discentes (4b)','pos_graduacao_4a.php')); 
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Quinze melhores produções bibliográficas (5)','pos_graduacao_5.php')); 
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Cinco melhores produções técnicas (6)','')); 
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Inovações de Destaque e Repercussões (7)',''));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Fluxo Discente (8)','pos_graduacao_8.php'));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Reconhecimento Internacional (9)','pos_graduacao_9.php'));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Inserção Social (10)',''));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Aspectos Relevantes (11)',''));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Metas Previstas para 2013 (12))',''));		
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Relação Discente (12))','pos_graduacao_20.php'));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Captação de Recursos (12))','pos_graduacao_21.php'));
				array_push($menu,array('Relatório Consultor Externo - Convidado','__Orientação PIBIC/PIBITI (12))','pos_graduacao_22.php'));
			}
	}
 
array_push($menu,array('Produção Científica','Produção em Revistas','producao_revistas.php'));		
	 
																	
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
<?
	$tela = menus($menu,"3");
?>
<? require("../foot.php");	?>