<?
require("cab.php");
require("../_class/_class_lattes.php");
$lt = new lattes;

require("../_class/_class_banco_projetos.php");
$bp = new projetos;

$arq = $lt->next_file();
echo '</center>';

if (strlen($arq) > 0)
	{
		echo '<h1>Processando arquivo</h1>';
		echo '<h3>Arquivo: '.$arq.'</h3>';
		
		$farq = fopen('tmp/'.$arq,'r');
		$sData = '';
		while (!feof($farq))
			{
				$sData .= fread($farq,1024);
			}
		fclose($farq);
		$sData = troca($sData,"'",'´');
		
		$ln = splitx(chr(13),$sData);
		/* tipo */
		$tipo = $lt->tipo_obra($ln[0]);
		echo '<h4>tipo: '.$tipo.'</h4>';
		
		if ($tipo == 'LVORG') { $lt->inport_livros_organizados($sData); $lt->delete_file($arq);  }
		if ($tipo == 'EVENT') { $lt->inport_eventos($sData); $lt->delete_file($arq); }
		if ($tipo == 'ARTIG') { $lt->inport_artigos($sData); $lt->delete_file($arq); }
		if ($tipo == 'LIVRO') { $lt->inport_livros($sData); $lt->delete_file($arq); }
		if ($tipo == 'CAPIT') { $lt->inport_livros_capitulos($sData); $lt->delete_file($arq); }
		if ($tipo == 'PROJE') { $lt->inport_projetos($sData); $lt->delete_file($arq); }
		
		if (strlen($tipo) > 0)
			{
				echo '
				<meta http-equiv="refresh" content="5;url='.page().'" />
				';
			}
		
		echo '<pre>'.$sx.'</pre>';
	}
?>
