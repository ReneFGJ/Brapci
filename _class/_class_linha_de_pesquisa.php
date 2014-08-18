<?php
class linha_de_pesquisa
	{
	var $id_lp;
	var $lp_nome;
	var $lp_ativo;
	var $lp_codigo;
	var $lp_keyworks;
	var $lp_objetivo;
	var $lp_area_1; 
	var $lp_area_2; 
	var $lp_area_3; 
	var $lp_area_4;
	var $lp_setor_1;
	var $lp_cnpq_link;
	
	var $tabela = 'linha_de_pesquisa';
	
	function mostra_linhas_de_pesquisa($grupo)
		{
			$grupo = strzero($grupo,7);
			
			$sql = "select * from ".$this->tabela."_grupo 
						inner join ".$this->tabela." on lpg_linha = lp_codigo 
						where lpg_grupo  = '".$grupo."'";
			$rlt = db_query($sql);
			
			$sx = '<table width="100%">';
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $line['lp_nome'];
					$sx .= '<TD>';
					$sx .= $line['lp_area_1'];
				}
			$sx .= '</table>';
			return($sx);
		}
	
	function cp()
		{
			$sql = "select * from ajax_areadoconhecimento order by a_cnpq";
			$rltt = db_query($sql);
			$op = ' : ';
			while ($tline = db_read($rltt))
				{ $op .= '&'.trim($tline['a_cnpq']).':'.trim($tline['a_cnpq']).' - '.trim($tline['a_descricao']).chr(13); }
			
			$cp = array();
			array_push($cp,array('$H8','id_lp','id_gp',False,True,''));
			array_push($cp,array('$S150','lp_nome','Nome da linha de pesquisa',True,True,''));
			array_push($cp,array('$O 1:SIM&0:NÃO','lp_ativo','Ativo',True,True,''));
			array_push($cp,array('$H8','lp_codigo','codigo',False,True,''));
			array_push($cp,array('$T50:3','lp_keyworks','Palavras-chave',False,True,''));
			array_push($cp,array('$T50:6','lp_objetivo','Objetivo da linha',False,True,''));
			array_push($cp,array('$O '.$op,'lp_area_1','Área Específica',True,True,''));
			array_push($cp,array('$H1','lp_area_2','Área 2',False,True,''));
			array_push($cp,array('$H1','lp_area_3','Área 3',False,True,''));
			array_push($cp,array('$H1','lp_area_4','Área 4',False,True,''));
			array_push($cp,array('$S100','lp_setor_1','Setor de aplicação',False,True,''));
			array_push($cp,array('$S100','lp_cnpq_link','Link CNPq',False,True,''));
			return($cp);
		}	
	function structure()
		{
			$sql = "CREATE TABLE linha_de_pesquisa (
  				id_lp serial NOT NULL,
  				lp_nome char(150),
  				lp_ativo int(11),
  				lp_codigo char(7),
  				lp_keyworks text,
  				lp_objetivo text,
  				lp_area_1 char(15),
  				lp_area_2 char(15),
  				lp_area_3 char(15),
  				lp_area_4 char(15),
  				lp_setor_1 text,
  				lp_cnpq_link char(100)
			)";
			//$rlt = db_query($sql);
			
			$sql = "CREATE TABLE linha_de_pesquisa_membros (
  				    id_lpm serial,
    				lpm_linha char(7),
    				lpm_cracha char(8),
    				lpm_tipo char(1),
    				lpm_ativo int2,
    				lpm_update int8
  				) ";
			//$rlt = db_query($sql);		
			
			$sql = "CREATE TABLE  linha_de_pesquisa (
  				id_lp serial,
    				lp_nome char(150),
    				lp_ativo int2,
  				  lp_codigo char(7),
    				lp_keyworks text,
    				lp_objetivo text,
    				lp_area_1 char(15),
    				lp_area_2 char(15),
    				lp_area_3 char(15),
    				lp_area_4 char(15),
    				lp_setor_1 text,
    				lp_cnpq_link char(100)
  				);
  				
  				INSERT INTO linha_de_pesquisa (id_lp, lp_nome, lp_ativo, lp_codigo, lp_keyworks, lp_objetivo, lp_area_1, lp_area_2, lp_area_3, lp_area_4, lp_setor_1, lp_cnpq_link) VALUES
  				(1, 'Processos Estratégicos', 1, '0000001', 'Competitividade; Controle estratégico; Estratégia; Estratégia como conteúdo; Estratégia como processo;', '', '7', '', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=02076021CMOK4L&seqlinha=1'),
  				(2, 'Agentes de Software', 1, '0000002', '', '', '1', '', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207103ISZ9LU2&seqlinha=1'),
  				(3, 'Aspectos Celulares e Moleculares em Patogênese', 1, '0000003', '', '', '1', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207401IBU85L3&seqlinha=2', ''),
  				(4, 'Engenharia e Transplante Celular', 1, '0000004', '', '', '1', '', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207401IBU85L3&seqlinha=1'),
  				(5, 'Investigação Clínica e Epidemiológica Básica e Aplicada', 1, '0000005', '', '', '1', '', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207401IBU85L3&seqlinha=3'),
  				(6, 'Bioinformática', 1, '0000006', '', '', '1', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207103AZEMVKK&seqlinha=3', ''),
  				(7, 'Documentos Semi-Estruturados', 1, '0000007', '', '', '1', '', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207103AZEMVKK&seqlinha=6'),
  				(8, 'Multimídia', 1, '0000008', '', '', '1', '', '', '', '', 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207103AZEMVKK&seqlinha=5');
				";	
				//$rlt = db_query($sql);			
				
				$sql = "CREATE TABLE  linha_de_pesquisa_grupo (
  					id_lpg serial,
  					  lpg_linha char(7),
  					  lpg_grupo char(7),
  					  lpg_ativo int2
  					) ;

  					INSERT INTO linha_de_pesquisa_grupo (id_lpg, lpg_linha, lpg_grupo, lpg_ativo) VALUES
  					(1, '0000001', '0000010', 1),
  					(2, '0000002', '0000032', 1),
  					(3, '0000003', '0000043', 1),
  					(4, '0000004', '0000043', 1),
  					(5, '0000005', '0000043', 1),
  					(6, '0000006', '0000020', 1),
  					(7, '0000007', '0000020', 1),
  					(8, '0000008', '0000020', 1);";
  					//$rlt = db_query($sql);	
		}
		function linha_de_pesquisa_atualizar($nome,$link,$grupo,$linha)
			{	
			//require_once('_class')
			//$sql = "delete from linha_de_pesquisa_grupo where 1=1 ";	
			//$rlt = db_query($sql);
			//$sql = "delete from linha_de_pesquisa where 1=1 ";
			//$rlt = db_query($sql);
			
			$nome = uppercase($nome);
			$rsql = "select * from linha_de_pesquisa where lp_nome = '".$nome."' ";
			$rlt = db_query($rsql);
			if (!($line = db_read($rlt)))
				{
					$sql = "insert into linha_de_pesquisa 
							(lp_nome,lp_ativo,	lp_codigo,	lp_keyworks,lp_objetivo,
							lp_area_1,lp_area_2,lp_area_3,lp_area_4,
							lp_setor_1,lp_cnpq_link )
							values
							('$nome',2,'','','',
							'$linha','','','',
							'','$link')";
					$rlt = db_query($sql);
					$this->updatex();
					
					$sql = "select * from linha_de_pesquisa where Upper(asc7(lp_nome)) = '".UpperCaseSql($nome)."'";
					$rlt = db_query($sql);
					
					if ($line = db_read($rlt)) { $linha = trim($line['lp_codigo']); }
					
					$sql = "insert into linha_de_pesquisa_grupo
								(lpg_linha, lpg_grupo, lpg_ativo)
								values
								('$linha','$grupo',1);
							";
					$rlt = db_query($sql);
				}
			$this->updatex();
			$rlt = db_query($rsql);
			$line = db_read($rlt);		
			return($line['lp_codigo']);
			}	
		function recupera_linha($nome)
			{
				$sql = "select * from ".$this->tabela." where Upper(asc7(lp_nome)) = '".uppercasesql($nome)."'";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						return($line['lp_codigo']);
					}
			}
		function cp_linhas()
			{
				$cp = array();
				array_push($cp,array('$H8','id_lp','id_gp',False,True,''));
				array_push($cp,array('$H8','','',False,True,''));
				array_push($cp,array('$H8','','',False,True,''));
				array_push($cp,array('$S80','lp_nome','Nome da linha',True,True,''));
				array_push($cp,array('$HV','lp_ativo','1',True,True,''));
				$sql = "a_descricao:a_cnpq:select a_cnpq || ' ' || a_descricao as a_descricao, a_cnpq from ajax_areadoconhecimento where 1=1 order by a_descricao  ";
				$ob = False;
				if (date("Ymd") > 20140800) { $ob = True; } 
				array_push($cp,array('$Q '.$sql,'lp_area_2','Área do Conhecimento',$ob,True,''));
				return($cp);				
				
			}
	function updatex()
		{
			global $base;
			$c = 'lp';
			$c1 = 'id_'.$c;
			$c2 = $c.'_codigo';
			$c3 = 7;
			$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
			if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
			 $line = db_query($sql);	
			return(1);
		}		
	}
?>
