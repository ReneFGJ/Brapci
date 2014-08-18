<?php
class autor
	{
	var $id_autor;
	var $autor_codigo;
	var $autor_nome;
	var $autor_nome_asc;
	var $autor_nome_abrev;
	var $autor_nome_citacao;
	var $autor_nasc;
	var $autor_lattes;
	var $autor_alias;
	var $autor_fale;
	var $autor_tipo;
	var $autor_ano_first;
	var $autor_ano_last;
	var $autor_instituicao;
	
	function cp()
		{
		}

	function structure()	
		{
			$sx .= 'CREATE TABLE autor (
			  id_autor serial NOT NULL,
			  autor_codigo char(7),
			  autor_nome char(120),
			  autor_nome_asc char(120),
			  autor_nome_abrev char(40),
			  autor_nome_citacao char(120),
			  autor_nasc char(8),
			  autor_lattes char(100),
			  autor_alias char(7),
			  autor_fale varchar(10),
			  autor_tipo varchar(1),
			  autor_ano_first varchar(4),
			  autor_ano_last varchar(4),    
			  autor_instituicao varchar(7)
			  )';
			return($sx);
		}
	function mst_autor($autor,$tp)
		{
			$aut = array();
			$autor = $autor . chr(13);
			while (strpos($autor,chr(13)) > 0)
				{
				$wd = trim(substr($autor,0,strpos($autor,chr(13))));
				if (strlen($wd) > 0)
					{
					$s1=''; $s2='';$s3='';
					if (strpos($wd,';') > 0) { $s1 = substr($wd,0,strpos($wd,';')); $wd = substr($wd,strpos($wd,';')+1,500); }
					else { $s1 = $wd; $wd = ''; }

					if (strpos($wd,';') > 0) { $s2 = substr($wd,0,strpos($wd,';')); $wd = substr($wd,strpos($wd,';')+1,500); }
					else { $s2 = $wd; $wd = '';  }
					
					$s3 = $wd;

					array_push($aut,array($s1,$s2,$s3));
					}
				$autor = substr($autor,strpos($autor,chr(13))+1,strlen($autor));
				}
			return($aut);
		}
	function updatex()
		{
		$sql = "update ".$this->tabela." set autor_codigo = lpad(autor_codigo,7,0) where autor_codigo = '' ";
		$rlt = db_query($sql);
		
		return(1);
		}
	}
?>