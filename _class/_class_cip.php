<?
class cip
	{
		/**
		 * Resumo do numero de Grupos de Pesquisa
		 */
		function resumo_grupos()
			{
				$sql = "SELECT gp_status as status , count( * ) AS total, 
						gps_descricao as nome 
						FROM grupo_de_pesquisa
						inner join grupo_de_pesquisa_status on gp_status = gps_codigo
						GROUP BY gp_status, gps_descricao
						order by gp_status
						";
				$rlt = db_query($sql);
				$rsp = array();
				while ($line = db_read($rlt))
					{
						array_push($rsp,$line);
					}
				return($rsp);
			}
		/**
		 * Resumo no numero de linhas de pesquisa
		 */
		function resumo_linhas()
			{
				$sql = "SELECT count( * ) AS total, lp_ativo as ativo
						FROM linha_de_pesquisa
						group by lp_ativo
						";
				$rlt = db_query($sql);
				$rsp = array();
				while ($line = db_read($rlt))
					{
						array_push($rsp,$line);
					}
				return($rsp);
			}
	}
?>