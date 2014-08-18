<?
require_once($include.'nusoap/nusoap.php');
require_once("../admin/_pucpr_login.php");

class Estudante
{
	var $al_cracha;
	var $al_centroAcademico;
	var $al_cpf;
	var $al_nivelCurso;
	var $al_nomeAluno;
	var $al_nomeCurso;
	var $al_pessoa;
	var $al_tel1;
	var $al_tel2;
	var $al_email1;
	var $al_email2;
	var $al_valido;
	var $al_update;
	var $erro;

function le_cracha($cr)
	{
	$this->al_cracha = '';
	if (strlen($cr) == 8) { $this->al_cracha = $cr; }
	if (strlen($cr) == 12) { $this->al_cracha = substr($cr,3,8); }
	if (strlen($cr) == 9) { $this->al_cracha = substr($cr,0,8); }

	if (strlen($this->al_cracha) == '') { $this->erro = (-1); echo 'erro'; exit; return(0); }

	$this->erro = $this->le();
	return(1);
	}
function le()
	{
	$sql = "select * from pibic_aluno ";
	$sql .= "where pa_cracha = '".$this->al_cracha."' ";
	$rlt = db_query($sql);
	if ($aline = db_read($rlt))
		{
			$this->al_centroAcademico 	= trim($aline['pa_centro']);
			$this->al_cpf 			= trim($aline['pa_cpf']);
			$this->al_nivelCurso 		= trim($aline['pa_curso']);
			$this->al_nomeAluno 		= trim($aline['pa_nome']);
			$this->al_nomeCurso 		= $aline['nomeCurso'];
			$this->al_pessoa 		= $aline['pessoa'];
			$this->al_tel1 			= $aline['pa_tel1'];
			$this->al_tel2 			= $aline['pa_tel2'];
			$this->al_email1  		= $aline['pa_email'];
			$this->al_email2  		= $aline['pa_email_1'];
			$this->al_valido  		= $aline[''];
			$this->al_update 		= $aline['pa_update'];
			$this->al_nivelCurso 		= $aline['pa_escolaridade'];
		}
	}

function gravar()
	{
	$ssql = "select * from pibic_aluno ";
	$ssql .= " where pa_cracha = '".$ap_cracha."' ";
	$rrlt = db_query($ssql);
	if ($result = db_read($rrlt))
		{
		/* Atualiza dados */
				$ssql = "update pibic_aluno set ";
				$ssql .= "pa_centro='".$this->al_centroAcademico."',";
				$ssql .= "pa_curso='".$this->al_nomeCurso."',";
				$ssql .= "pa_tel1='".$this->al_tel1."',";
				$ssql .= "pa_tel2='".$this->al_tel2."',";
				$ssql .= "pa_escolaridade='".$this->al_nivelCurso."',";
				$ssql .= "pa_update='".date("Ymd")."',";
				$ssql .= "pa_email='".$this->al_email1."',";
				$ssql .= "pa_email_1='".$this->al_email2."' ";
				$ssql .= " where pa_cracha = '".$this->al_cracha."' ";
				$rrlt = db_query($ssql);
		} else {
		/* Insere dados na tabela */
				$ssql = "insert into pibic_aluno ";
				$ssql .= "(pa_nome,pa_nome_asc,pa_nasc,";
				$ssql .= "pa_cracha,pa_cpf,pa_centro,";
				$ssql .= "pa_curso,pa_tel1,pa_tel2,";
				$ssql .= "pa_escolaridade,pa_update ";
				$ssql .= ",pa_email,pa_email_1";
				$ssql .= ") ";
				$ssql .= " values ";
				$ssql .= "('".UpperCase($this->al_nomeAluno)."','".UpperCaseSQL($this->al_nomeAluno)."','',";
				$ssql .= "'".$this->al_pessoa."','".$this->al_cpf."','".$this->al_centroAcademico."',";
				$ssql .= "'".$this->al_nomeCurso."','".$this->al_tel1 ."','".$this->al_tel2."',";
				$ssql .= "'".$this->al_nivelCurso."','".date("Ymd")."'";
				$ssql .= ",'".$this->al_email1."','".$this->al_email2."'";
				$ssql .= ")";		
				$rrlt = db_query($ssql);
		}	
	}

/** 
 * Método de Consulta 
 * $arg1 = $cracha
**/
function consulta($ap_cracha,$forca_consulta = False)
	{
	$this->setCracha($ap_cracha);
	$this->soap_webservice();
	
	/* Antes do WebService Consulta dados da Base de dados */
	$consulta = True;
	$ssql = "select * from pibic_aluno ";
	$ssql .= " where pa_cracha = '".$ap_cracha."' ";
	$rrlt = db_query($ssql);
	if ($result = db_read($rrlt))
		{
		$data = substr($result['pa_update'],0,6);
		/* Se já foi consultado no mês não realiza nova consulta */
		if (($data == date("Ym")) and ($forca_consulta != '1'))
			{
			$consulta = False;
			$rst = True;
			}
		}
	/* Executa consulta */
	if ($consulta == True)
		{ 
			/* Executa SOAP */
			$this->soap_webservice(); 
		} else {
			/* Recupera dados do banco */
			$this->al_centroAcademico = $result['pa_centro'];
			$this->al_cpf = $result['pa_cpf'];
			$this->al_nivelCurso = $result['pa_curso'];
			$this->al_nomeAluno = troca($result['pa_nome'],"'","´");
			$this->al_nomeCurso = troca($result['nomeCurso'],"'","´");
			$this->al_pessoa = $result['pessoa'];
			$this->al_tel1 = $result['pa_tel1'];
			$this->al_tel2 = $result['pa_tel2'];
			$this->al_email1  = $result['pa_email'];
			$this->al_email2  = $result['pa_email_1'];
			$this->al_valido  = 2;
			$this->al_update = $result['pa_update'];
			$this->al_nivelCurso = $result['pa_escolaridade'];
		}
		if ($this->al_valido == 1) { $this->gravar(); }
	}

function soap_webservice()
	{
	global $user, $pass;
	$client = new soapclient('https://portalintranet.pucpr.br:8081/servicePibic?wsdl');
	$client->setCredentials($user, $pass); 	
	$err = $client->getError();
	/* Informa parametros para o WebService */

	$param = array('arg0' => $this->al_cracha);	
	$result = $client->call('pesquisarPorCodigo', $param,'http://consultas.servicos.apc.br/', '', false, true);

	/* Retorna parametro da consulta */
	$this->al_centroAcademico = $result['centroAcademico'];
	$this->al_cpf = $result['cpf'];
	$this->al_nivelCurso = $result['nivelCurso'];
	$this->al_nomeAluno = troca($result['nomeAluno'],"'","´");
	$this->al_nomeCurso = troca($result['nomeCurso'],"'","´");
	$this->al_pessoa = $result['pessoa'];
	$this->al_tel1 = $result['tel1'];
	$this->al_tel2 = $result['tel2'];
	$this->al_email1  = $result['email1'];
	$this->al_email2  = $result['email2'];
	$this->al_update = date("Ymd");
	/* Correções de centro */	
	if ($this->al_nivelCurso == 'Biotecnologia') { $this->al_centroAcademico = 'Centro de Ciências Biológicas e da Saúde - CCBS'; }
	
	/* Valida a entrada do campo */
	if (strlen(trim($result['nomeAluno'])) > 0)
		{
			$this->al_valido  = 1;
		} else {
			$this->al_valido  = 0;
		}
	}
function setCracha($ap_cracha)
	{
	global $al_cracha;
	$this->al_cracha = $ap_cracha;
	$this->al_valido = 0;
	}
}
?>