<?php
class user
	{
	var $nome;
	var $codigo;
	var $email;
	var $cidade;
	
	var $tabela = 'users';
	
	function user($us,$tp)
		{
			if ($tp == 'google')
				{
					$email = $us['email'];
					$nome = $us['name'];
					$genero = $us['gender'];
					$local = $us['locale'];
					$pais = '';
					$image = $us['picture'];
					$link = $us['link'];
					$autenticador = 'G';
				}
			$sql = "select * from ".$this->tabela." where us_email = '".$email."'";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$codigo = $line['us_codigo'];
				} else {
					$codigo = $this->user_insert($email,$nome,$pais,$local,$link,$image,$genero,$autenticador);
				}
			return($codigo);
		}	
	function recupera_id($email)
		{
			$sql = "select * from ".$this->tabela." where us_email = '".$email."'";
			$rlt = db_query($sql);
			return(trim($line['us_codigo']));			
		}
	function user_insert($email,$nome,$pais,$cidade,$link,$image,$genero,$autenticador)
		{
			if (strlen($email) == 0) { return(''); }
			$data = date("Ymd");
			$genero = trim($genero);
			if ($genero == 'male') { $genero = 'MASC'; }
			if ($genero == 'female') { $genero = 'FEME'; }
			$codigo = '';
			$sql = "insert into ".$this->tabela." 
					(
					us_nome, us_email, us_cidade,
					us_pais, us_codigo, us_link,
					us_ativo, us_nivel, us_image,
					us_genero, us_verificado, us_autenticador,
					us_cadastro
					) values (
					'$nome','$email','$cidade',
					'$pais','$codigo','$link',
					1,'0','$image',
					'$genero','1','$autenticador',
					$data
					)
			";
			$rlt = db_query($sql);
			$this->updatex();
		}
	function updatex()
			{
				global $base;
				$c = 'us';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
	}
?>
