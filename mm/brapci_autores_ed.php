<? 
require("db.php"); 
require($include."sisdoc_debug.php");
require($include."sisdoc_autor.php");
require($include."sisdoc_cookie.php");
$jidsel = strzero(read_cookie("journal_sel"),7);
$jedsel = read_cookie("journal_ed");

///////////////////////////////////////////////////
if ((strlen($dd[0]) == 0) and (strlen($dd[5]) > 0))
	{
		$dd5 = nbr_autor($dd[5],5);
		$dd5a = nbr_autor($dd[5],1);
		$dd5b = nbr_autor($dd[5],1);
		$qsql = "select * from brapci_autor ";
		$qsql .= " where autor_nome_asc = '".UpperCaseSql($dd5a)."'";
		$xrlt = db_query($qsql);
		if (!($xline = db_read($xrlt)))
			{
			$xsql = "insert into brapci_autor ";
			$xsql .= "(autor_codigo,autor_nome,autor_nome_asc,";
			$xsql .= "autor_nome_abrev,autor_nome_citacao,autor_nasc,";
			$xsql .= "autor_lattes,autor_alias";
			$xsql .= ") values (";
			$xsql .= "'','".$dd5a."','".UpperCaseSql($dd5a)."',";
			$xsql .= "'".UpperCaseSql($dd5)."','".$dd5a."','',";
			$xsql .= "'',''";
			$xsql .= ")";
			$xrlt = db_query($xsql);	
			
			$xsql = "update brapci_autor set autor_codigo=lpad(id_autor,7,'0') where autor_codigo=''";			
			$xrlt = db_query($xsql);

			$xrlt = db_query($qsql);
			$xline = db_read($xrlt);
			}
		$xcod = $xline['autor_codigo'];
		$dd[6] = $xcod;
	}
////////////////////////////////////////////////////////
?>
<head>
<link rel="STYLESHEET" type="text/css" href="css/letras_popup.css">
</head>
<font class="lt5"><font color="white">
<CENTER><?=$dd[2];?></CENTER>
</font></font>
<?
$tabela = "brapci_article_author";
$cp = array();
array_push($cp,array('$H8','id_ae','id_ae',False,True,''));
array_push($cp,array('$HV','ae_article',$dd[1],False,True,''));
array_push($cp,array('$HV','',$dd[2],False,True,''));
array_push($cp,array('$HV','',$dd[3],False,True,''));
array_push($cp,array('$HV','ae_journal_id',$jidsel,False,True,''));
if (strlen($dd[0]) == 0)
	{ array_push($cp,array('$S100','','Nome do autor (completo)',True,True,'')); } else 
	{ array_push($cp,array('$Q autor_nome:autor_codigo:select * from brapci_autor where autor_codigo = '.chr(39).$dd[3].chr(39),'','Nome do autor (completo)',False,False,'')); }
	
array_push($cp,array('$H1','ae_author',$dd[6],True,True,''));
array_push($cp,array('$QA inst_nome:inst_codigo:select * from (select concat(inst_nome,inst_abreviatura) as inst_nome, inst_codigo, inst_ordem, inst_nome_asc from instituicoes) as tabela order by inst_ordem, inst_nome_asc','ae_instituicao','Instituição',False,True,''));
array_push($cp,array('$T60:3','ae_bio','Qualificação',False,True,''));
array_push($cp,array('$S100','ae_contact','e-mail',False,True,''));

array_push($cp,array('$[1-20]','ae_pos','posição',False,True,''));
array_push($cp,array('$C1','ae_professor','Professor (Docente)',False,True,''));
array_push($cp,array('$C1','ae_aluno','Estudante (Discente)',False,True,''));
array_push($cp,array('$C1','ae_doutorado','Doutor (Doutorando, marcar também opção estudante)',False,True,''));
array_push($cp,array('$C1','ae_mestrado','Mestre (Mestrando, marcar também opção estudante)',False,True,''));
array_push($cp,array('$C1','ae_ss','Programa Strict Sensus',False,True,''));
array_push($cp,array('$C1','ae_profissional','Profissional',False,True,''));

array_push($cp,array('$B8','','Gravar >>',False,True,''));

array_push($cp,array('$A','','Dados complementares',False,True,''));
array_push($cp,array('$S20','ae_telefone','Telefone',False,True,''));
array_push($cp,array('$T60:4','ae_endereco','Endereço',False,True,''));

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$http_edit = 'brapci_autores_ed.php';
$http_redirect = 'close.php';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
	{	
	echo $sql;

	}
