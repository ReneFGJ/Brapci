<?php
class instituicao
	{
		var $tabela = 'instituicao';
		
		function insere_instituicao($name,$sigla)
			{
				$name = trim($name);
				$nome_asc = uppercasesql($name);
				$sql = "insert into ".$this->tabela."
					(inst_nome, inst_nome_asc, inst_abreviatura,
					inst_codigo, inst_cidade, inst_endereco,
					inst_site,inst_ordem )
					values
					('$name','$name_asc','$sigla',
					'','',''
					'',1)
				";
				$rlt = db_query($sql);
				$this->updatex();
				return(1);
			}
		
		function instituicao($name='')
			{
				echo '<HR>'.$name.'<HR>';
				$name = utf8_decode($name);
				$name = uppercasesql($name);
				if (strpos($name,'(') > 0)
					{
						$name = substr($name,0,strpos($name,'('));
					}
				$sqlx = "select * from ".$this->tabela." 
						where inst_nome_asc = '$name' ";
				$rlt = db_query($sqlx);
				if ($line = db_read($rlt))
					{
						$cod = $line['inst_codigo'];
					} else {
						$this->insere_instituicao($name,'');
						$rlt = db_query($sqlx);
						$line = db_read($rlt);
						$cod = $line['inst_codigo'];						
					}
				return($cod);
			}
		
		function cp()
			{
				global $dd;
				$dd[2] = UpperCaseSql($dd[1]);
				$cp = array();
				//$dd[0] = $par->codigo;
				array_push($cp,array('$H8','id_inst','','',False,False));
				array_push($cp,array('$S100','inst_nome','Nome da Instituição',True,True));
				array_push($cp,array('$H8','inst_nome_asc','',True,True));
				array_push($cp,array('$S100','inst_abreviatura','Sigla',True,True));
				array_push($cp,array('$[1-9]','inst_ordem','Ordem',True,True));
				return($cp);				
			}
		function updatex()
			{
				global $base;
				$c = 'inst';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
		
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_inst','inst_nome','inst_abreviatura','inst_ordem');
				$cdm = array('cod',msg('nome'),msg('abbrev'),msg('ordem'));
				$masc = array('','','','','','','','');
				return(1);				
			}		
		function strucuture()
			{
				$sql = '	
					CREATE TABLE instituicao (
  					id_inst serial not null,
  					inst_nome char(120),
  					inst_nome_asc char(120),
  					inst_abreviatura char(15),
  					inst_codigo char(7),
  					inst_cidade char(7),
  					inst_endereco text,
  					inst_site char(100),
  					inst_ordem int2 default 0
					)';
				$rlt = db_query($sql);
		} 
	}
