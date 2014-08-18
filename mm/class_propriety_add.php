<?
	require("cp/cp_concept.php");
	?>
	<table width="100%" border="1">
	<? editar(); ?>
	</table>
	
	<?
	if ($saved > 0)
		{ 
		$idioma = $dd[5];
		$termo = trim($dd[3]);
		$termo_asc = trim(uppercasesql($dd[3]));
		require("descriptor_append.php");			
		////////////////////// FASE II - Ver se esse termo já não foi utilizado
		$sql = "select * from tci_conceito_relacionamento ";
		$sql .= " where crt_termo = '".$termo."' and crt_rela='PD' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{
				
			} else {
				//// Fase III - Salva conceito e adiciona termo preferencial
				/// Cria Conceito
				$conc = LowerCaseSql($dd[3]);
				$conc = substr(troca($conc,' ',''),0,29);
				$conc = '¢'.$conc;
				$sql = "insert into tci_conceito ";
				$sql .= "(cnt_descricao,cnt_codigo) ";
				$sql .= " values ";
				$sql .= "('".$conc."','')";
				$rlt = db_query($sql);
				
				$sql = "update tci_conceito set cnt_codigo = lpad(id_cnt,7,0) where cnt_codigo = ''";
				$rlt = db_query($sql);
				
				$sql = "select * from tci_conceito where cnt_descricao = '".$conc."' ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$conceito = $line['cnt_codigo'];

				//////////// Insere relacionamento
				$sql = "insert into tci_conceito_relacionamento ";
				$sql .= "(crt_conceito,crt_termo,crt_rela,crt_tema)";
				$sql .= " values ";
				$sql .= "('".$conceito."','".$termo."','PD','".$dd[6]."')";
				$rlt = db_query($sql);
				
				redirecina('concept.php?dd0='.$conceito);
			}
		echo $sql;
		}
	?>