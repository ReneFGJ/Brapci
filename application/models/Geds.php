<?php
class geds extends CI_model {
	function upload($id) {
		$sx = '
				<form enctype="multipart/form-data" method="POST">
    				<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
    				Enviar esse arquivo: <input name="userfile" type="file" />
    				<input type="submit" value="Enviar arquivo" />
				</form>
				';
		return ($sx);
	}
	
	function check_dir($dir) {
		if (is_dir($dir)) {
			return (1);
		} else {
			mkdir($dir);
			$rlt = fopen($dir . 'index.php', 'w+');
			fwrite($rlt, '<TT>Acesso negado</tt>');
			fclose($rlt);
		}
	}	

	function save_post_file($id) {

		/* Prepara o nome do arquivo */
		$arti = 0;
		$filename = '_repositorio';
		$this -> check_dir($filename);
		$filename .= '/' . date("Y");
		$this -> check_dir($filename);
		$filename .= '/' . date("m");
		$this -> check_dir($filename);
		$filename .= '/pdf_' . substr(md5(date("Ymdis")), 10, 10) . '_' . $id . '.pdf';
		while (file_exists($filename))
			{
				$arti++;
				$filename .= '/pdf_' . substr(md5(date("Ymdis")), 10, 10) . '_' . $id . '.pdf';
			}

		echo '<pre>';
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $filename)) {

				/* Novo registro do PDF */
				$jour = '0000000';
				$sql = "insert brapci_article_suporte 
						(
						bs_status, bs_article, bs_type, 
						bs_adress, bs_journal_id, bs_update
						) values (
						'B','$id','PDF',
						'$filename','$jour'," . date("Ymd") . ')';
				$this -> db -> query($sql);
		} else {
			echo "Possível ataque de upload de arquivo!\n";
		}

		echo 'Aqui está mais informações de debug:';
		print_r($_FILES);

		print "</pre>";
	}

}
?>
