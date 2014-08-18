<?
class mirror
	{
		var $protocolo;
		var $protocolo_mae;
		var $orientador;
		var $estudante;
		var $bolsa;
		var $data;
		var $st;
		var $edital;
		
		var $data_gr;
	
		var $tabela = 'pibic_mirror';
		
	function cp()
		{ }
	function row()
		{
			global $cdf,$cdm,$masc;
			$cdf = array('id_mr','mr_protocolo','mr_ano','mr_mes','mr_orientador','mr_estudante');
			$cdm = array('cod',msg('protocolo'),msg('ano'),msg('mes'),msg('orientador'),msg('estudante'));
			$masc = array('','','','','','','');
			return(1);				
		}		
	function espelho_geral_ano()
		{
		$sql = "select mr_ano,mr_mes,count(*) as bolsas,
			pbt_descricao as mr_modalidade, centro_nome from ".$this->tabela."
			left join pibic_bolsa_tipo on mr_modalidade = pbt_codigo 
			left join centro on centro_codigo = mr_orientador_centro 
			where mr_mes = '09'
			group by mr_ano, mr_mes, pbt_descricao, centro_nome
			order by mr_ano, mr_mes ";
		echo $sql;
		$rlt = db_query($sql);
		$rsp = array();
		$cab = array();
		$gr = '';
		while ($line = db_read($rlt))
			{
				$mod = caseSql(trim($line['mr_modalidade']));
				$ano = trim($line['mr_ano']);
				$mes = trim($line['mr_mes']);
				$total = trim($line['bolsas']);
				$centro = trim($line['centro_nome']);
				array_push($rsp,array($line));
				if (strlen($gr) > 0) { $gr .= ', '; }
				$gr .= chr(13)."['$mod',new Date ($ano,$mes,1),$total,300,'$centro']";
			}
		$this->data_gr = $gr;
		return(1);			
		}
		
	function espelho($ano,$mes)
		{
			return($this->espelho_01($ano,$mes));
		}
	function espelho_01($ano='',$mes='')
		{
			if (strlen($ano) == 0)
				{
					$ano = substr($this->data,0,4);
					$mes = substr($this->data,4,2);
				}
			
			$sql = "select * from ".$this->tabela;
			$sql .= " where mr_ano = '".$ano."' and mr_mes = '".$mes."' ";
			echo $sql;
			$rlt = db_query($sql);
		}
		
	function grava_novo()
		{
			$ano = substr($this->data,0,4);
			$mes = substr($this->data,4,2);
			
			if (($ano < 2005) or ($ano > date("Y")))
				{
					echo 'Erro do Ano '.$ano.'===';$this->data;
					exit;
				}
			$orientador = $this->orientador;
			$estudante = $this->estudante;
			$protocolo = $this->protocolo;
			$protocolo_mae = $this->protocolo_mae;
			$modalidade = $this->bolsa;
			$edital = $this->edital;
			$st = $this->st;

			$sql = "select * from pibic_mirror
				where mr_ano = '$ano' and mr_mes = '$mes'
				and mr_protocolo = '$protocolo' ";
			
			$rlt = db_query($sql); 
			if (!($line = db_read($rlt)))
				{
					$sql = "insert into pibic_mirror 
						( mr_protocolo, mr_protocolo_mae, mr_ano,
						mr_mes, mr_orientador, mr_estudante, 
						mr_modalidade, mr_orientador_curso, mr_orientador_centro,
						mr_ss, mr_orientador_area, mr_estutante_area, 
						mr_status, mr_vlr_bolsa, mr_st, mr_edital
						) values (
						'$protocolo','$protocolo_mae','$ano',
						'$mes','$orientador','$estudante',
						'$modalidade','','',
						'','','','A',0,'$st','$edital');";
					$rlt = db_query($sql);
					return(1);
				} else {
					return(0);
				}
			
		}

	function espelho_resumo_estatus()
		{
			$st = array('A'=>'Coletado','B'=>'Analisado Orientador','C'=>'Analisado Estudante');
			$sql = "SELECT count(*) as total, mr_status FROM pibic_mirror WHERE 1=1
					group by mr_status";
			$rlt = db_query($sql);
			$sx = '<table width=400>';
			$sx .= '<TR><TH>Total<TH>Status';
			while ($line = db_read($rlt))
				{
					$sta = $line['mr_status'];
					$sx .= '<TR><TD align="center">'.$line['total'];
					$sx .= '<TD>'.$sta.'-'.$st[$sta];
				}	
			$sx .= '</table>';
			return($sx);
		}

	function espelho_status_a()
		{
			require("_class_curso.php");
			$curso = new curso;
			
			$sql = "select * from ".$this->tabela." ";
			$sql .= " inner join docentes on pp_cracha = mr_orientador ";
			$sql .= " where mr_status = 'A' limit 1";
			
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				
					$curso->curso_busca(trim($line['pp_curso']));
					$curson = $curso->curso_codigo;
					$centro = $curso->centro_codigo;
					$orientador = $line['mr_orientador'];					
					if (strlen($centro)==0)
						{ echo 'Erro, centro não localizado'; exit; }
					$ss = $line['pp_ss'];
					$tit = $line['pp_titulacao'];
					$prod = $line['pp_prod'];
					$id = $line['id_mr'];
					
					$sql = "update ".$this->tabela." set ";
					$sql .= " mr_orientador_titulacao = '$tit', 
							mr_orientador_prod = '$prod',
							mr_ss = '$ss',
							mr_orientador_centro = '$centro',
							mr_orientador_curso = '$curson',
							mr_status = 'B'
							where (id_mr = $id) or
							( mr_orientador = '$orientador' and mr_status = 'A') ";
					$rlt = db_query($sql);
				}
			return(1);
		}
		
	function espelho_status_b()
		{
			//require("_class_curso.php");
			$curso = new curso;
			
			$sql = "select * from (select * from ".$this->tabela." ";
			$sql .= " where mr_status = 'B' limit 1)  as tabela ";
//			$sql .= " inner join discentes on pa_cracha = mr_estudante ";
			$sql .= " inner join pibic_aluno on pa_cracha = mr_estudante ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					//echo '<PRE>';
					//print_r($line);
					//echo '</PRE>';
					//echo '<HR>';
					//$sql = "CREATE INDEX key_estudantes2 ON ".$this->tabela." (mr_status);";
					//$rlt = db_query($sql);
					if (round($line['mr_estudante']) < 1)
						{
							echo 'Erro do código do estudante';
							exit;
						}
					echo '[1]';
					$curso->curso_busca(trim($line['pa_curso']));
					$curson = $curso->curso_codigo;
					$centro = $curso->centro_codigo;
					$estudante = $line['mr_estudante'];					
					if (strlen($centro)==0)
						{ echo 'Erro, centro não localizado'; exit; }
					$id = $line['id_mr'];
					
					echo '[2]';
					$sql = "update ".$this->tabela." set ";
					$sql .= " mr_estudante_centro = '$centro',
							mr_estudante_curso = '$curson',
							mr_status = 'C'
							where (id_mr = $id) or
							( mr_estudante = '$estudante' and mr_status = 'B') ";
					$rlt = db_query($sql);
				}
			return(1);	
		}
		
	function gerar_espelho($ano,$mes)
		{
			$ano = round($ano);
			$edital = $this->edital;
			$mes = strzero($mes,2);
			
			$xmes = round($mes);
			$sql = "select * from pibic_bolsa_contempladas 
				where pb_ano = '$edital' 
				";
			echo $sql;
			echo '<H4>'.$ano.'-'.$mes.'</h4>';
			echo '<BR>Buscando dados...';
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					echo '<BR>'.$line['pb_protocolo'].'...';
					$sta = $line['pb_status'];
					$encerra = round($line['pb_data_encerramento']);
					$this->estudante = $line['pb_aluno'];
					$this->orientador = $line['pb_professor'];
					$this->protocolo = $line['pb_protocolo'];
					$this->protocolo_mae = $line['pb_protocolo_mae'];
					$tipo = $line['pb_tipo'];
					$this->bolsa = $tipo;
					$this->st = $sta;
					$this->edital = $edital;
					$this->data = strzero($ano,4).strzero($mes,2).'01';
					$rt = $this->grava_novo();
					if ($rt==1) { echo ' salvado'; }					
				}
		}
		function structure()
			{
				$sql = "DROP TABLE pibic_mirror";
				$rlt = db_query($sql);
				$sql = "CREATE TABLE pibic_mirror (
  					id_mr serial NOT NULL,
  					mr_protocolo char(7),
  					mr_protocolo_mae char(7),
  					mr_ano char(4),
  					mr_mes char(3),
  					mr_orientador char(8),
  					mr_estudante char(8),
  					mr_estudante_curso char(5),
  					mr_estutante_area char(5),
  					mr_estudante_centro char(5),
  					mr_modalidade char(1),
  					mr_orientador_curso char(5),
  					mr_orientador_centro char(5),
  					mr_ss char(1),
  					mr_orientador_area char(5),
  					mr_status char(1),
  					mr_vlr_bolsa float,
  					mr_st char(1),
  					mr_edital char(4),
  					mr_orientador_prod char(3),
  					mr_orientador_titulacao char(3))";
				$rlt = db_query($sql);
				return($sql);
			}	
	}
?>
