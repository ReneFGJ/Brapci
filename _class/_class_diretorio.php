<?php
	class diretorio
	{
	function diretorio_checa($vdir)
		{
		if(is_dir($vdir))
			{ $rst =  '<FONT COLOR=GREEN>OK';
			} else { 
				$rst =  '<FONT COLOR=RED>NÃO OK';	
				mkdir($vdir, 0777);
				if(is_dir($vdir))
					{
					$rst =  '<FONT COLOR=BLUE>CRIADO';	
					}
			}
			$filename = $vdir."/index.htm";	
			if (!(file_exists($filename)))
			{
				$ourFileHandle = fopen($filename, 'w') or die("can't open file");
			$ss 	='<META HTTP-EQUIV="Refresh" CONTENT="0;URL=http://www.pucpr.br/nao_encontrado.php">';
				$rst = $rst . '*';
				fwrite($ourFileHandle, $ss);
				fclose($ourFileHandle);		
			}
			return($rst);
		}		
	}
?>
