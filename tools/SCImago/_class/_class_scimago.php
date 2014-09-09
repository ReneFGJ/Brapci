<?php
class scimago {
	function structure() {
		$sql = "alter table ajax_cidade add column cidade_nome_asc char(40)";
		$rlt = db_query($sql);
		
		$sql = "alter table ajax_pais add column pais_nome_asc char(30)";
		$rlt = db_query($sql);
		
		$sql .= "
					CREATE TABLE ajax_pais
					( 
					id_pais serial NOT NULL, 
					pais_nome char(30), 
					pais_nome_asc char(30),
					pais_codigo char(7), 
					pais_ativo int4 DEFAULT 1, 
					pais_use char(7), 
					pais_idioma char(5),, 
					pais_prefe int8 DEFAULT 0
					);
				ALTER TABLE ajax_cidade ADD CONSTRAINT id_cidade PRIMARY KEY(id_cidade);
				";	
		
		$sql = "CREATE TABLE ajax_cidade
				( 
					id_cidade serial NOT NULL, 
					cidade_nome char(40),
					cidade_nome_asc char(40), 
					cidade_codigo char(7), 
					cidade_ativo int4 DEFAULT 1, 
					cidade_use char(7), 
					cidade_pais char(7), 
					cidade_estado char(7), 
					cidade_idioma char(5) DEFAULT 'pt_BR'::bpchar, 
					cidade_sigla char(3) 
					); 
					
				ALTER TABLE ajax_cidade ADD CONSTRAINT id_cidade PRIMARY KEY(id_cidade);
				";
	}

	function updatex()
		{
			$sql = "select * from ajax_pais where pais_nome_asc = '' ";
			while ($line = db_read($rlt))
				{
					$sql = "update ajax_pais set pais_nome_asc = '".uppercasesql($line['pais_nome'])."' where id_pais = ".$line['id_pais'];
					echo $sql.'<HR>';
				}
		}
	function search_country($country) {
		$country_asc = UpperCaseSql($country);
		$sql = "select * from ajax_pais where pais_nome_asc = '" . $country_asc . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			return ($line['cnt_codigo']);
		} else {
			$sql = "select pais_codigo from ajax_pais order by pais_codigo desc ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$cod = strzero(round($line['pais_codigo']) + 1, 5);

			$sql = "insert into apoio_pais
								( 
								pais_nome, pais_name_asc, pais_codigo, 
								pais_use, pais_ativo,  )
								values 
								( '$country','$country_asc','$cod','$cod') 
							";
			$rlt = db_query($sql);
			return ($cod);
		}
	}

	function search_journal($issn, $name = '', $country = '') {
		if (substr($issn, 4, 1) == '-') {
			$sql = "select * from sci_journal where sci_issn = '" . $issn . "' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt)) {
				return (1);
			} else {
				$ctr = $this -> search_country($country);
				$sql = "insert into sci_journal 
									(
										sci_issn, sci_journal, sci_ativo,
										sci_country
									) values (
										'$issn','$name',1,
										'$ctr'
									)							
							";
				$rlt = db_query($sql);
			}
		}
	}

	/* Processar Arquivo */
	function process($filename) {
		$s = $this -> read_file($filename);
		if ($s != -1) {
			$s = $this -> process_i($s);
			$s = $this -> process_ii($s);
			$sa = $this -> process_iii($s);
			$this -> process_iv($sa);
		} else {
			Echo 'Erro na abertura de arquivo';
			exit ;
		}
	}

	/* Identificacao */
	function process_iv($sa) {
		print_r($sa[0]);
		for ($r = 0; $r < count($sa); $r++) {
			$sx = $sa[$r] . ';';
			$ss = splitx(';', $sx);
			$issn = substr($ss[2], 0, 4) . '-' . substr($ss[2], 4, 4);
			$journal = $ss[1];
			$q = $ss[3];
			$f1 = $ss[4];
			$f2 = $ss[5];
			$f3 = $ss[6];
			$f4 = $ss[7];
			$f5 = $ss[8];
			$f6 = $ss[9];
			$f7 = $ss[10];
			$f8 = $ss[11];
			$f9 = $ss[12];
			$country = $ss[13];

			echo '<BR>' . $issn . '-' . $journal.'-'.$q.'-';
			echo '=' . $f1 . '=' . $f2 . '=' . $f3 . '=' . $f4 . '=' . $f5 . '=' . $f6 . '=' . $f7 . '=' . $f8 . '=' . $f9;
			echo '====' . $country;
		}
	}

	/* Processa corta */
	function process_i($s) {
		$pos = strpos($s, '<tbody>');
		if ($pos > 0) {
			$s = substr($s, $pos + 7, strlen($s));
			$pos = strpos($s, '</tbody>');
			$s = substr($s, 0, $pos);
		}
		return ($s);
	}

	/* Processa arquivo */
	function process_ii($s) {
		$s = troca($s, '<', '[');
		$s = troca($s, '>', ']');
		$s = troca($s, "'", "´");
		$s = troca($s, '[tr]', chr(13) . chr(10));
		$s = troca($s, '[td]', '');
		$s = troca($s, '[td ]', '');
		$s = troca($s, '="', '');
		$s = troca($s, '"', '');
		$s = troca($s, '[/td]', ';');
		return ($s);
	}

	/* SplitX */
	function process_iii($s) {
		$sa = splitx(chr(13) . chr(10), $s);
		return ($sa);
	}

	/* Ler arquivo */
	function read_file($filename) {
		if (file_exists($filename)) {
			$rlt = fopen($filename, 'r');
			$s = '';
			while (!(feof($rlt))) {
				$s .= fread($rlt, 512);
			}
			fclose($rlt);
			return ($s);
		} else {
			return (-1);
		}

	}

}
?>
