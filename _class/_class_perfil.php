<?php
class perfil
	{
		var $id_per;
		var $per_nome;
		var $per_ativo;
		var $per_codigo;
		
		var $tabela = 'perfil';
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_per','key',False,True));
				array_push($cp,array('$H8','per_codigo','cod',False,True));
				array_push($cp,array('$S40','per_nome',msg('Nome do perfil'),True,True));
				array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'per_ativo','Perfil',True,True));
				return($cp);
			}
			
		function le($id)
			{
				if (strlen($id) > 0) { $this->id_per = $id;}
				$sql = "select * from ".$this->tabela." where id_per = ".$this->id_per;
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->id_per = $line['id_per'];
						$this->per_nome = $line['per_nome'];
						$this->per_ativo = $line['per_ativo'];
						$this->per_codigo = $line['per_codigo'];
						return(1);
					} else {
						return(0);
					}
			}
		
		function perfil_usuario_nomear($perfil,$usuario)
			{
				if ((strlen($perfil)==3) and (strlen($usuario) > 4))
					{
					$sql = "select * from usuario_perfis ";
					$sql .= " where up_perfil = '".$perfil."' ";
					$sql .= " and up_usuario = '".$usuario."' ";
					$rlt = db_query($sql);
					$ok = 1;
					if ($line = db_read($rlt))
						{
							if (($line['up_ativo']) == 0)
								{
									$sql = "update usuario_perfis set up_ativo = 1, up_data = ".date("Ymd");
									$sql .= " where up_perfil = '".$perfil."' ";
									$sql .= " and up_usuario = '".$usuario."' ";
									$rlt = db_query($sql);
								} else 
								{ $ok = -1; }
						} else {
							$sql = "insert into usuario_perfis ";
							$sql .= "(up_perfil, up_usuario, up_data, up_ativo)";
							$sql .= " values ";
							$sql .= "('".$perfil."','".$usuario."',".date("Ymd").",1)";
							$rlt = db_query($sql);
							$ok = -2;
						}
					} else {
						$ok = -3;
					}
				return($ok);
			}
			
		function perfil_usuario_destituir($perfil,$usuario)
			{
				if ((strlen($perfil)==3) and (strlen($usuario) > 4))
					{
					$sql = "select * from usuario_perfis ";
					$sql .= " where up_perfil = '".$perfil."' ";
					$sql .= " and up_usuario = '".$usuario."' ";
					$rlt = db_query($sql);
					$ok = 1;
					if ($line = db_read($rlt))
						{
							if (($line['up_ativo']) == 1)
								{
									$sql = "update usuario_perfis set up_ativo = 0, up_data = ".date("Ymd");
									$sql .= " where up_perfil = '".$perfil."' ";
									$sql .= " and up_usuario = '".$usuario."' ";
									$rlt = db_query($sql);
								} else 
								{ $ok = -1; }
						} 
						$ok = -3;
					}
				return($ok);
			}			
		
		function perfil_usuario_ativos()
			{
				$sql = "select * from usuario_perfis ";
				$sql .= " left join usuario on up_usuario = us_codigo ";
				$sql .= " where up_perfil = '".$this->per_codigo."' ";
				$sql .= " and up_ativo = 1 ";
				$sql .= " and us_ativo = 1 ";
				$rlt = db_query($sql);
				$us = array();
				while ($line = db_read($rlt))
				{
					array_push($us,array($line['us_login'],$line['us_nome'],$line['us_cracha'],$line['us_codigo']));
				}
				return($us);
			}
			
		function perfil_usuario_inativos()
			{
				$sql = "select * from usuario ";
				$sql .= " left join usuario_perfis on up_usuario = us_codigo ";
				$sql .= " where us_ativo = 1 ";
								
				$rlt = db_query($sql);
				$us = array();
				
				while ($line = db_read($rlt))
				{
					$ok = 0;
					if (strlen(trim($line['up_perfil'])) == 0) { $ok = 1; }
					if ((strlen(trim($line['up_perfil'])) == 3) and ($line['up_ativo'] == 0)) { $ok = 1; }
						
					if ($ok == 1)
						{ array_push($us,array($line['us_login'],$line['us_nome'],$line['us_cracha'],$line['us_codigo'])); }
				}
				return($us);
			}


		function updatex()
			{
				$c = 'per';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 3;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = db_query($sql);
			}
	}
?>