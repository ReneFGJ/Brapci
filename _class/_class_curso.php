<?
class curso
	{
		var $id_curso;
		var $curso_nome;
		var $curso_codigo;
		var $curso_tipo;
		var $curso_area;
		var $curso_sigla;
		var $curso_ativo;
		
		var $centro_codigo;
		var $curso_centro;
		
		var $tabela = "curso";
		
		function relatorio_escolas_cursos()
			{
				$sql = "select * from curso 
						inner join centro on curso_centro = centro_codigo
						where curso_codigo_use = ''
						order by centro_nome, curso_nome
				";
				$rlt = db_query($sql);
				$sx = '<table class="tabela01" cellpadding=0 cellspacing=0 width="100%">';
				$esx = 'x';
				while ($line = db_read($rlt))
					{
						$esc = trim($line['centro_nome']);
						if ($esc != $esx)
							{
								$tot1 = 0;
								$sx .= '<TR><TD colspan=1>';
								$sx .= '<h2>'.$esc.'</h2>';
								$esx = $esc;
							}
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= trim($line['curso_nome']);
						//print_r($line);
						//echo '<HR>';
					}
				$sx .= '</table>';
				return($sx);
			}
		function le($id)
			{
				$sql = "select * from ".$this->tabela." 
					where id_curso = ".$id." or curso_codigo = '".$id."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$this->id = $line['id_curso'];
					$this->curso_codigo = trim($line['curso_codigo']);
					$this->curso_nome = trim($line['curso_nome']);
					$this->curso_centro = trim($line['curso_centro']);
				}
				return(1);
			}
		function cp()
			{
				global $dd;		

				$dd[2] = uppercasesql(utf8_decode($dd[1]));
				$cp = array();
				array_push($cp,array('$H8','id_curso','',False,True));
				array_push($cp,array('$S100','curso_nome',msg('curso'),True,True));
				array_push($cp,array('$H8','curso_nome_asc','',True,True));
				array_push($cp,array('$H8','curso_codigo','',False,True));
				array_push($cp,array('$Q centro_nome:centro_codigo:select * from centro where centro_tipo = 1 order by centro_nome','curso_centro',msg('centro'),True,True));
				array_push($cp,array('$S10','curso_sigla',msg('sigla'),False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','curso_ativo',msg('ativo'),False,True));
				array_push($cp,array('$S5','curso_codigo_use',msg('remissiva'),False,True));
				
				array_push($cp,array('$I8','curso_carga_1' ,msg('carga_1') ,True,True));
				array_push($cp,array('$I8','curso_carga_2' ,msg('carga_2') ,True,True));
				array_push($cp,array('$I8','curso_carga_3' ,msg('carga_3') ,True,True));
				array_push($cp,array('$I8','curso_carga_4' ,msg('carga_4') ,True,True));
				array_push($cp,array('$I8','curso_carga_5' ,msg('carga_5') ,True,True));
				array_push($cp,array('$I8','curso_carga_6' ,msg('carga_6') ,True,True));
				array_push($cp,array('$I8','curso_carga_7' ,msg('carga_7') ,True,True));
				array_push($cp,array('$I8','curso_carga_8' ,msg('carga_8') ,True,True));
				array_push($cp,array('$I8','curso_carga_9' ,msg('carga_9') ,True,True));
				array_push($cp,array('$I8','curso_carga_10',msg('carga_10'),True,True));
				array_push($cp,array('$I8','curso_carga_11',msg('carga_11'),True,True));
				array_push($cp,array('$I8','curso_carga_12',msg('carga_12'),True,True));
				
//				array_push($cp,array('$Q curso_nome:curso_codigo:select * from curso where curso_ativo=1 order by curso_nome_asc','curso_codigo_use',msg('remissiva'),False,True));
				return($cp);
			}
		function curso_grava_novo($nome)
			{
				$nome_asc = uppercaseSQL($nome);
				$sql = "insert into ".$this->tabela." (
				curso_nome, curso_nome_asc, curso_ativo
				) values (
				'$nome','$nome_asc',1
				)";
				$rlt = db_query($sql);
				$this->updatex();
				echo ' (inserido) '.$nome;
				return(1);
			}

		function curso_busca($crs)
			{
				$crs = UpperCaseSql($crs);
				$crs = troca($crs,'HAB.:','');
				$crs = troca($crs,'  ',' ');
				$crs = trim($crs);
				if (strpos($crs,'(') > 0)
					{ $crs = substr($crs,0,strpos($crs,'(')); }
				echo '<BR>>>'.$crs.'<BR>';
				$sql = "select * from ".$this->tabela;
				$sql .= " where (curso_nome_asc = '".substr(trim($crs),0,100)."') ";				
				$sql .= " or (curso_nome = '".substr(trim($crs),0,100)."') ";				
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						
						$remissiva = $line['curso_codigo_use'];
						//if (round($remissiva) > 0)
						//	{
						//		$sql = "select * from ".$this->tabela." where curso_codigo = '".$remissiva."' ";
						//		$rlt = db_query($sql);
						//		$line = db_read($rlt);
						//	}
						$this->curso_codigo = $line['curso_codigo'];
						$this->centro_codigo = $line['curso_centro'];
						$this->curso_nome = $line['curso_nome'];
						echo '>>'.$line['curso_nome'].'<BR>';
						return(1);
					} else {
						$this->curso_codigo = '';
						$this->centro_codigo = '';
						echo "Not found ".$crs.'<BR><BR>';
						$this->curso_grava_novo($crs);
						exit;		
					}
				
				return(0);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_curso','curso_nome','curso_codigo','curso_codigo_use','curso_ativo');
				$cdm = array('cod',msg('curso'),msg('codigo'),msg('use'),msg('ativo'));
				$masc = array('','','','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				global $base;
				$c = 'curso';
				$dx2 = 'id_'.$c;
				$dx1 = $c.'_codigo';
				$dx3 = 5;
				$sql = "update ".$this->tabela." set ".$dx1." = lpad(".$dx2.",".$dx3.",0) where ".$dx1." = '' ";
				if ($base="pgsql")
					{ $sql = "update ".$this->tabela." set ".$dx1."=trim(to_char(".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";}
				
				$rlt = db_query($sql);	
			return(1); 

			}
		
	}
