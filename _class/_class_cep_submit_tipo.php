<?
class cep_submit_tipo
	{
	var $id_sp;
	var $sp_codigo;
	var $sp_descricao;
	var $sp_ordem;
	var $sp_content;
	var $sp_ativo;
	var $sp_nucleo;
	var $sp_caption;
	var $sp_cab_number;
	
	var $tabela = 'cep_submit_tipo';
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_sp','',False,True));
			array_push($cp,array('$H8','sp_codigo','',False,True));
			array_push($cp,array('$S30','sp_descricao',msg('descricao'),True,True));
			array_push($cp,array('$[1-20]','sp_ordem',msg('ordem'),True,True));
			array_push($cp,array('$T60:3','sp_content',msg('conteudo'),False,True));
			array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'sp_ativo',msg('ativo'),True,True));
			array_push($cp,array('$Q n_descricao:n_codigo:select * from nucleo where n_ativo=1 order by n_descricao','sp_nucleo',msg('nucleo'),False,True));
			array_push($cp,array('$H8','sp_caption',msg('caption'),False,True));
			array_push($cp,array('$[1-10]','sp_cab_number',msg('n_cabs'),False,True));
			return($cp);
		}
	
	function le($id='')
		{
			$sql= "select * from ".$this->tabela;
			$sql .= " where sp_codigo = '".$id."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->id_sp = $line['id_sp'];
					$this->sp_codigo = $line['sp_codigo'];
					$this->sp_descricao = $line['sp_descricao'];
					$this->sp_ativo = $line['sp_ativo'];
					$this->sp_nucleo = $line['sp_nucleo'];
					$this->sp_caption = $line['sp_caption'];
					$this->sp_cab_number = $line['sp_cab_number'];
					return(1);
				}
			return(0);
		}	
	function updatex()
		{
			return(1);
		}
	}