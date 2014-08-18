<?php
class cr
	{
		var $tabela = "centro_resultado";
		var $recupera_ordenador_necessidade_funcao;
		var $recupera_ordenador_gasto_funcao;
		
		function cp()
			{
				
			}
		function recupera_ordenador_necessidade($cr)
			{
				$sql = "select * from ".$this->tabela."
						inner join pibic_professor on cr_ordenador_necessidade = pp_cracha 
						where cr_ncr = '".$cr."' ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$nome = trim($line['pp_nome']).' ('.trim($line['pp_cracha']).')';
				$this->recupera_ordenador_necessidade_funcao = trim($line['pp_funcao']);
				return($nome);
			}
		function recupera_ordenador_gasto($cr)
			{
				$sql = "select * from ".$this->tabela."
						inner join pibic_professor on cr_ordenador_gasto = pp_cracha 
						where cr_ncr = '".$cr."' ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$nome = trim($line['pp_nome']).' ('.trim($line['pp_cracha']).')';
				$this->recupera_ordenador_gasto_funcao = trim($line['pp_funcao']);
				return($nome);
			}
		function structure()
			{
				$sql = "create table centro_resultado
					(
					id_cr serial not null,
					cr_ncr char(6),
					cr_descricao char(100),
					cr_ordenador_necessidade char(8),
					cr_ordenador_gasto char(8)
					)
				";
				//$rlt = db_query($sql);
				
				$sql = "insert into centro_resultado (cr_ncr, cr_descricao, cr_ordenador_necessidade, cr_ordenador_gasto )
						values ('103309','Núcleo do Fundo de Pesquisa','70004750','88888951')
				";
				//$rlt = db_query($sql);		
			}
	}
