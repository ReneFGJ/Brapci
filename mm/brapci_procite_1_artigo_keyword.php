<?

$sk = troca($keyword,'/','; ');
$sk = troca($sk,'. ','; ');
$keyw = array();
$sk .= '; ';

$sql = "select * from brapci_keyword ";
$sql .= "where ";
	while (strpos($sk,';') > 0)
		{
		$txt = ';';
		////////// Recupera palavra chave
		$sa = substr(substr($sk,0,strpos($sk,';','')),0,100);
		////////// Armazena se o nome for maior que 0
		if (strlen($sa) > 0)
			{
			if (count($keyw) > 0) { $sql .= ' or '; }
			array_push($keyw,array($sa,UpperCaseSql($sa),0,''));
			$sql .= " kw_word_asc = '".UpperCaseSql($sa)."' ";
			}
		$sk = trim(substr($sk,strpos($sk,';')+1,strlen($sk)));	
		}
		
	if (count($keyw) > 0)
		{
		$sqlc = "insert into brapci_keyword (";
		$sqlc .= "kw_word,kw_word_asc,kw_codigo,";
		$sqlc .= "kw_use,kw_idioma) values ";
		$sqli = "";
		
		$rlt = db_query($sql);
		while ($line = db_read($rlt))
			{
			$kw_termo  = trim($line['kw_word_asc']);
			$kw_codigo = $line['kw_codigo'];
			$ok = 0;
			for ($ka=0;$ka < count($keyw);$ka++)
				{ if ($keyw[$ka][1] == $kw_termo) { $keyw[$ka][2] = 1; $keyw[$ka][3] = $kw_codigo; } }
			}
		$up = 0;
		for ($ka=0;$ka < count($keyw);$ka++)
			{
//			echo '<BR>'.$keyw[$ka][0].' '.$keyw[$ka][1].' '.$keyw[$ka][2];
			if ($keyw[$ka][2] == 0) 
				{
				$sqli = $sqlc . "('".LowerCase($keyw[$ka][0])."','".UpperCaseSql($keyw[$ka][0])."','',";
				$sqli .= "'','pt_BR'); ".chr(13).chr(10);
				$rlt = db_query($sqli);
				$up++;
				}
			}
		if ($up > 0)
			{
			$sqli = "update brapci_keyword set kw_codigo=lpad(id_kw,'10','0') where (length(kw_codigo) < 10);";		
			$rlt = db_query($sqli);
			$sqli = "update brapci_keyword set kw_use=lpad(id_kw,'10','0') where (length(kw_use) < 10);";		
			$rlt = db_query($sqli);

			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
//				echo '.';
				$kw_termo  = trim($line['kw_word_asc']);
				$kw_codigo = $line['kw_codigo'];
				$ok = 0;
				for ($ka=0;$ka < count($keyw);$ka++)
					{ if ($keyw[$ka][1] == $kw_termo) { $keyw[$ka][2] = 1; $keyw[$ka][3] = $kw_codigo; } }
				}
			}
		}
	$sql = "delete from brapci_article_keyword where kw_article = '".$ar_codigo."'; ".chr(13).chr(10);
	$rlt = db_query($sql);
		
	for ($ka=0;$ka < count($keyw);$ka++)
		{	
		$sql = "insert into brapci_article_keyword (kw_article, kw_keyword,kw_ord ) values (";
		$sql .= "'".$ar_codigo."','".$keyw[$ka][3]."',".$ka.");".chr(13).chr(10);
		$rlt = db_query($sql);
		}
	
	//// Processa na base

?>