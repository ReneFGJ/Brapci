<?php
class nucleo
	{
		var $id_n;
		var $n_codigo;
		var $n_descricao;
		var $n_ativo;
		
		var $tabela = "nucleo";
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_n','',False,True));
				array_push($cp,array('$S5','n_codigo','',True,True));
				array_push($cp,array('$S50','n_descricao',msg('descricao'),False,True));
				array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'n_ativo',msg('ativo'),False,True));
				return($cp);
			}
		function updatex()
			{
				return(1);
			}
	}
