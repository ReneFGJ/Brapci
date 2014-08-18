<?php
class ged
	{
		var $id_file;
		var $file_name;
		var $file_size;
		var $file_path;
		var $file_type;
		var $file_date;
		var $file_time;
		var $file_saved;
		var $protocol;
		
		/* Ref. Upload */
		var $up_path; /* Pasta de destino */
		var $up_maxsize; /* Tamanho mï¿½ximo do upload */
		var $up_format = array('*'); /* Formatos aceitos */
		var $up_month_control = 1; /* Criar pastas conforme o mes de postagem */
		var $up_doc_type;
		/* Dados da tabela */
		var $tabela = '';

		/**
		 * Function upload
		 * var1 $tp tipo do objeto
		 * var2 $bt nome ou tipo do botão
		 */
		 
		function upload_botton_with_type($proto='',$base='',$tp='',$bt='')
			{
				$sx = '
				<select id="filetype_1">
					<option>::'.msg('documento_type').'::</option>
				';
				$sql= "select * from ".$this->tabela.'_tipo where doct_ativo = 1 order by doct_nome';
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$sx .= '<option value="'.trim($line['doct_codigo']).'">';
						$sx .= trim($line['doct_nome']);
						$sx .= '</option>';
					}				
								
				$sx .= '
				</select>
				<span id="fileup">Upload</span>
				<input type="hidden" id="filetype_2" value="">
				<input type="hidden" id="filetype_3" value="">
				<script>
					$("#fileup").click(function() 
						{
				  		var dd10=$("#filetype_1").val();
						var dd11=$("#filetype_2").val();
						var dd13=$("#filetype_3").val();
						var url = \'ged_upload.php?dd1='.$proto.'\&dd2=\'+dd10;
						NewWindow=window.open(url,\'newwin3\',\'scrollbars=yes,resizable=yes,width=600,height=300,top=10,left=10\');  
						NewWindow.focus(); 
						void(0);
						});
				</script>';

				return($sx);
			}
		function upload_botton($tp='',$bt='')
			{
				if (strlen($bt)==0) { $bt=msg('upload'); }
				$link = "javascript:newxy2('ged_upload.php?dd1=".$this->protocol."&dd2=".$tp."',400,400);";
				$link = '<A HREF="'.$link.'">';
				$link .= $bt;
				$link .= '</A>';
				return($link); 
			}

		function cp_type()
			{
				$cp = array();
				array_push($cp,array('$H8','id_doct','',False,True));
				array_push($cp,array('$S50','doct_nome',msg('descricao'),False,True));
				array_push($cp,array('$H8','doct_codigo','',False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_publico',msg('ged_pub'),False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_avaliador',msg('ged_adhoc'),False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_autor',msg('ged_autor'),False,True));
				array_push($cp,array('$O 0:NÃO&1:SIM','doct_restrito',msg('ged_restrict'),False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_ativo',msg('ativo'),False,True));				
				return($cp);
			}
		function row_type()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_doct','doct_nome','doct_codigo','doct_publico');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('publico'));
				$masc = array('','','','','','','');
				return(1);
			}

		function download_send()
			{
        		header("Pragma: public");
        		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");					
				header("Expires: 0");
				//header('Content-Length: $len');
				header('Content-Transfer-Encoding: none');
				$file_extension = $this->file_type;
				switch( $file_extension ) {
	      			case "pdf": $ctype="application/pdf"; break;
    	  			case "exe": $ctype="application/octet-stream"; break;
	      			case "zip": $ctype="application/zip"; break;
	      			case "doc": $ctype="application/msword"; break;
	      			case "xls": $ctype="application/vnd.ms-excel"; break;
	      			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	      			case "gif": $ctype="image/gif"; break;
	      			case "png": $ctype="image/png"; break;
	      			case "jpeg":
	      			case "jpg": $ctype="image/jpg"; break;
	      			case "mp3": $ctype="audio/mpeg"; break;
	      			case "wav": $ctype="audio/x-wav"; break;
	      			case "mpeg":
	      			case "mpg":
	      			case "mpe": $ctype="video/mpeg"; break;
	      			case "mov": $ctype="video/quicktime"; break;
	      			case "avi": $ctype="video/x-msvideo"; break;
					}
				header("Content-Type: $ctype");
				header('Content-Disposition: attachment; filename="'.$this->file_name.'"');
				header("Content-type: application-download");
				header("Content-Transfer-Encoding: binary");				
				readfile($this->file_path);
			}
		
		function download($id='')
			{
				$arq = $this->file_path;
				if (strlen($id) > 0) { $this->id_file = $id; }
				if ($this->le($this->id_file))
					{
						$arq = $this->file_path;
						if (!(file_exists($arq)))
							{
								echo $arq;
								echo '<BR> Arquivo não localizado ';
								echo '<BR> Reportando erro ao administrador';
							exit;
							} else {
								/** Download do arquivo **/
								$this->download_send();
							}
					} else { echo '<BR><font color="red">ID not found'; }							
			}
		
		function le($id)
			{
				if (strlen($id) > 0) { $this->id_file = $id; }
				if (strlen($this->tabela) > 0)
					{
						$sql = "select * from ".$this->tabela;
						$sql .= " where id_doc = ".round($this->id_file);
						$sql .= " limit 1 ";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								$this->id_file = trim($line['id_doc']);
								$this->file_name = trim($line['doc_filename']);
								$this->file_size = trim($line['doc_size']);
								$this->file_path = trim($line['doc_arquivo']);
								$this->file_type = trim($line['doc_extensao']);
								$this->file_date = trim($line['doc_data']);
								$this->file_saved = trim($line['doc_ativo']);
								return(1);
							} else {
								echo msg('file_not_found');
							}
						
					} else { echo msg('table_not_set'); }
				return(0);								
			}
			
		function filelist()
			{
				global $messa,$secu,$ged_del,$dd;
				
				$sx = '<table width=100% cellpadding=2 cellspacing=0 border=1 class="lt1">';
				$sx .= '<TR>';
				$sx .= '<TH>'.msg('file_name');
				$sx .= '<TH>'.msg('file_filename');
				$sx .= '<TH>'.msg('file_size');
				$sx .= '<TH>'.msg('file_data');
				$sql = "select * from ".$this->tabela;
				$sql .= " left join ".$this->tabela."_tipo on doc_tipo = doct_codigo ";
				$sql .= " where doc_dd0 = '".$this->protocol."' and doc_ativo=1 ";
				
				$rlt = db_query($sql);
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$capt = trim($line['doct_nome']);
						if (substr($capt,0,1)=='#') { $capt = msg(substr($capt,1,strlen($capt))); }
						//$link = 'ged_download.php?dd0='.$line('id_doc').'&dd90='.checkpost($line['id_doc'].$secu);
						$link = 'ged_download.php?dd0='.$line['id_doc'];
						$link .= '&dd50='.$this->tabela;
						$link .= '&dd90='.checkpost($line['id_doc'].$secu);
						$link .= '&dd91='.$secu;
						$link = newwin($link,300,150);
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>'.$link.$capt.'</A>';
						$sx .= '<TD>'.$link.$line['doc_filename'].'</A>';
						$sx .= '<TD align="center" class="lt0">'.$this->size_mask($line['doc_size']).'</A>';
						$sx .= '<TD align="center" class="lt0">'.stodbr($line['doc_data']).' '.$line['doc_hora'].'</A>';
						$tot++;
					}
				$frame = $dd[3];
				$sx .= '</table>'.chr(13);
				return($sx);
			}	
			
		function file_list()
			{
				global $messa,$secu,$ged_del,$dd,$page,$popup;
				$sx = '<table width=100% cellpadding=2 cellspacing=0 border=1 class="lt1">';
				$sx .= '<TR>';
				$sx .= '<TH>'.msg('file_name');
				$sx .= '<TH>'.msg('file_filename');
				$sx .= '<TH>'.msg('file_size');
				$sx .= '<TH>'.msg('file_data');
				$sx .= '<TH>'.msg('file_acao');
				$sql = "select * from ".$this->tabela;
				$sql .= " left join ".$this->tabela."_tipo on doc_tipo = doct_codigo ";
				$sql .= " where doc_dd0 = '".$this->protocol."' and doc_ativo=1 ";
				if (strlen($this->file_type) > 0)
					{ $sql .= " and doc_tipo = '".$this->file_type."' ";}
				$rlt = db_query($sql);
				
				$tot = 0;
				while ($line = db_read($rlt))
					{
						
						$capt = trim($line['doct_nome']);
						if (substr($capt,0,1)=='#') { $capt = msg(substr($capt,1,strlen($capt))); }
												
						//$link = 'ged_download.php?dd0='.$line('id_doc').'&dd90='.checkpost($line['id_doc'].$secu);
						$link = 'ged_download.php?dd0='.$line['id_doc'];
						$link .= '&dd50='.$this->tabela;
						$link .= '&dd90='.checkpost($line['id_doc'].$secu);
						$link .= '&dd91='.$secu;
						$link = newwin($link,300,150);
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>'.$link.$capt.'</A>';
						$sx .= '<TD>'.$link.$line['doc_filename'].'</A>';
						$sx .= '<TD align="center" class="lt0">'.$this->size_mask($line['doc_size']).'</A>';
						$sx .= '<TD align="center" class="lt0">'.stodbr($line['doc_data']).' '.$line['doc_hora'].'</A>';
						$sx .= '<TD align="center">';
				
						if ($line['doc_status'] == '@')
							{
								if ((strlen($frame) > 0) or (strlen($popup) > 0))
								{
									if (strlen($popup) > 0)
										{
											$sx .= '<a href="javascript:newxy2(\'ged_delete.php?dd0='.$line['id_doc'].'&dd2=DEL&dd90='.checkpost($line['id_doc']).'&dd91='.$secu.'&dd50='.$this->tabela.'\',400,200);">';
								 			$sx .= '<img src="img/icone_remove.png" border=0 >';
											$sx .= '</A>';
										} else {
								 			$sx .= '<img src="img/icone_remove.png" id="remove" onclick="ged_excluir('.$line['id_doc'].');">';
											$sx .= '</A>';
										}
								} else {
									
									$link = page().'?page='.$page.'&ddf='.$line['id_doc'].'&ddg=DEL';
									$link .= '&ddh='.checkpost($line['id_doc'].$secu);
									$sx .= '<A HREF="'.$link.'">';
								 	$sx .= '<img src="img/icone_remove.png" id="remove" onclick="ged_excluir('.$line['id_doc'].');" border=0>';
									$sx .= '</A>';				
								}
							}
						$tot++;
					}
				$frame = $dd[3];
				if ($tot == 0) { $sx .= '<TR><TD colspan=5 align=center><font color="red"><B>'.msg('not_file_posted').'</B></font>'; }
				$sx .= '</table>'.chr(13);
				if (strlen($frame) > 0)
					{
					$sx .= '<script type="text/javascript">'.chr(13);
					$sx .= 'function ged_excluir(id)'.chr(13);
					$sx .= " { alert('ola'); ";
					$sx .= '    var tela01 = $.ajax( "'.page().'?dd0="+id+"&dd1='.$dd[1].'&dd2=files_del&dd3='.$frame.'&dd90='.$dd[90].'" ) .done(function(data) { $("#'.$frame.'").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#'.$frame.'").html(data); }); '.chr(13);
					$sx .= " } ";		
					$sx .= '</script>'.chr(13);
					}
				return($sx);
			}	

		function convert($tb1,$tb2)
			{
				$sql .= "select * from ".$tb1." where pl_codigo = '".$this->protocol."' ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$protocolo = $line['pl_codigo'];
						$tipo = trim($line['pl_tp_doc']);
						$ano = substr($line['pl_data'],0,4);
						$filename = trim($line['pl_texto']);
						$data = $line['pl_data'];
						$hora = $line['pl_hora'];
						$file = $line['pl_codigo'];
						$ext = $line['pl_type'];
						$size = $line['pl_size'];
						$file = '/pucpr/httpd/htdocs/www2.pucpr.br/reol/pibic/public/submit/';
						$file .= substr($data,0,4).'/'.substr($data,4,2).'/';
						$file .= trim($line['pl_filename']);
						$ok=0;
						if ($tipo == '00006') { $tipo = 'PLANO'; $ok=1; }
						echo '</font>';
						if ($ok==0) { echo 'Erro de tipo '.$tipo; exit; }
						echo '<HR>';
						$sql = "insert into ".$tb2." (
							doc_dd0, doc_tipo, doc_ano, 
							doc_filename, doc_status, doc_data, 
							doc_hora, doc_arquivo, doc_extensao, 
							doc_size, doc_ativo
							) values (
							'$protocolo','$tipo','$ano',
							'$filename','A','$data',
							'$hora','$file','$ext',
							$size,1
							)";
						$rrr = db_query($sql);
						$sql = "update ".$tb1." set pl_codigo = 'X".substr($protocolo,1,6)."' where id_pl = ".$line['id_pl'];
						$rrr = db_query($sql);
					}
				return(1);
			}

		function file_delete()
			{
				$sql = "update ".$this->tabela;
				$sql .= " set doc_ativo = 0 ";
				$sql .= " where id_doc = ".$this->id_doc;
				$rlt = db_query($sql);
				return(1);
			}	
		
		function file_undelete()
			{
				$sql = "update ".$this->tabela;
				$sql .= " set doc_ativo = 1 ";
				$sql .= " where id_doc = ".$this->id_doc;
				$rlt = db_query($sql);
				return(1);
			}	

		function file_attach_form()
			{
				global $dd,$messa,$acao,$tipo;
				$page = page().'?';
				$page .= 'dd0='.$dd[0].'&dd2='.$dd[2].'&dd1='.$dd[1].'&dd90='.$dd[90];
				$saved = 0;
				
				if (strlen($acao) > 0)
					{
						$tipo = $dd[2];
					    $nome = lowercasesql($_FILES['arquivo']['name']);
    					$temp = $_FILES['arquivo']['tmp_name'];
						$size = $_FILES['arquivo']['size'];

						$path = $this->up_path;
						$extensoes = $this->up_format;
						
						/* valida extensao */
						$ext = strtolower($nome);
						while (strpos(' '.$ext,'.') > 0)
							{ $ext = substr($ext,strpos($ext,'.')+1,strlen($ext)); }
						$ext = '.'.$ext;
										
						$ind = -1;
						
						for ($rt=0;$rt < count($extensoes);$rt++)
							{ if ($ext == $extensoes[$rt]) { $ind = $rt; }	}
							
						if ($extensoes[0] == '*') { $ind=0; }
						if ($ind < 0) { $erro = '<font color=red >Erro:01 - '.msg('erro_extensao').'</font>'; }

						/* diretï¿½rio */
						$nome = substr($nome,0,strlen($nome)-4);
						$nome = lowercasesql(troca($nome,' ','_'));
						$nome .= $ext;
		
						if (strlen($tipo)==0)
							{ $erro = msg('type_doc_not_defined'); }

						$this->dir($path);
						if ($this->up_month_control == 1)
							{
								$path .= date("Y").'/'; $this->dir($path);
								$path .= date("m").'/'; $this->dir($path);
							}
						
						/* caso nï¿½o apresente erro */
						if (strlen($erro)==0) 
						{
							$compl = $dd[1].'-'.substr(md5($nome.date("His")),0,5).'-';
							$compl = troca($compl,'/','-');
        					if (!move_uploaded_file($temp, $path .$compl.$nome))
            					{ $erro = msg('erro_save'); } else 
            					{
            						$this->file_saved = $path.$compl.$nome;
            						$this->file_name = $nome;
									$this->file_size = $size;
									$this->file_path = $path;
									$this->file_data = date("Ymd");
									$this->file_time = date("H:i:s");
									$this->file_type = $tipo;
									$this->protocol = $dd[1];
									$this->save();          						
									$saved = 1;
									if (file_exists('close.php')) { require("close.php"); exit; }
									require("../close.php");
								}		
						} else {
							echo '<center>'.msg($erro).'</center>';
						}
						
				}

			if ($saved == 0)
				{
				$options = '<option value="">'.msg('not_defined').'</option>';
				$options .= $this->documents_type_form();
				$page = page();
				$sx .= '<form id="upload" action="'.$page.'" method="post" enctype="multipart/form-data">
					'.msg('file_tipo').'
    				<select name="dd2" size=1>'.$options.'</select><BR>
	    			<nobr>Arquivo: 
    				<span id="status" style="display: block;"><img src="img/icone_loader.gif" alt="Enviando..." /></span>
    				<span id="post"><input type="file" name="arquivo" id="arquivo" /></span>
    				<input type="hidden" name="dd0" value="'.$dd[0].'"> 
    				<input type="hidden" name="dd1" value="'.$dd[1].'"> 
    				<input type="hidden" name="dd90" value="'.$dd[90].'"> 
    				<input type="submit" value="enviar arquivo" name="acao" id="idbotao" />    			
					</form>';
				}
				$sc .= '<center><h2>'.msg('gt_'.substr($this->tabela,0,10)).'</h2></center>';
				$sc .= '<div>'.msg('gi_'.substr($this->tabela,0,10)).'</div>';
			return($sc.$sx);
			}

		function documents_type_form()
			{
				global $dd;
				$sql = "select * from ".$this->tabela."_tipo where doct_ativo = 1 ";
				$rlt = db_query($sql);
				$sx = '';
				while ($line = db_read($rlt))
					{
						$sel = '';
						if ($dd[2] == trim($line['doct_codigo'])) { $sel = 'selected'; }
						$sx .= '<option value="'.$line['doct_codigo'].'" '.$sel.'>';
						$sx .= msg(trim($line['doct_nome']));
						$sx .= '</option>';
						$sx .= chr(13);
					}
				return($sx);
				
			}
		function save()
			{
				$sql = "insert into ".$this->tabela;
				$sql .= " (doc_dd0,doc_tipo,doc_ano,doc_filename,doc_status,doc_data,doc_hora,doc_arquivo,doc_extensao,doc_size,doc_ativo)";
				$sql .= " values ";
				$sql .= " ('".$this->protocol."',";
				$sql .= "'".$this->file_type."',";
				$sql .= "'".date("Y")."',";
				$sql .= "'".$this->file_name."',";
				$sql .= "'@',";
				$sql .= "'".$this->file_data."',";
				$sql .= "'".$this->file_time."',";
				$sql .= "'".$this->file_saved."',";
				$sql .= "'".$this->file_extensao($this->file_name)."'";
				$sql .= ",".round($this->file_size);
				$sql .= ",1 )";
				$rlt = db_query($sql);
			}
			
		/* recupera a extensï¿½o do aquivo */
		function file_extensao($fl)
			{
				$fl = lowercase($fl);
				$fs = strlen($fl);
				$ex = '???';
				if (substr($fl,$fs-1,1) == '.') { $ex = substr($fl,$fs,1); }
				if (substr($fl,$fs-2,1) == '.') { $ex = substr($fl,$fs-1,2); }
				if (substr($fl,$fs-3,1) == '.') { $ex = substr($fl,$fs-2,3); }
				if (substr($fl,$fs-4,1) == '.') { $ex = substr($fl,$fs-3,4); }
				if (substr($fl,$fs-5,1) == '.') { $ex = substr($fl,$fs-4,5); }
				return(substr(trim($ex),0,4));
				}

		/* checa e cria diretorio */
		function dir($dir)
			{
				if (is_dir($dir))
					{ $ok = 1; } else 
					{
						mkdir($dir);
						$rlt = fopen($dir.'/index.php','w');
						fwrite($rlt,'acesso restrito');
						fclose($rlt);
					}
				return($ok);
			}
			
		/* Mascara do tamanho em Bytes */
		function size_mask($limit)
			{
				$limit = round($limit);
				if ($limit >= (1024 * 1024))
				{
					$limit_u = 's';
					$limit_msk = round(10 * $limit / (1024*1024))/10;
					$limit_unidade = "Mega"; 
					if ($limit_msk == 1) { $limit_u = ''; }
				} else {
					$limit_u = 's';
					$limit_msk = round(10 * $limit / (1024))/10;
					$limit_unidade = "k";
					if ($limit_msk == 1) { $limit_u = ''; }
				}
			return($limit_msk.' '.$limit_unidade. 'B'.$limit_u);
			}
		function structure()
			{
				//$sql = "DROP TABLE ".$this->tabela;
				//$rlt = db_query($sql);
				
				$table = $this->tabela;
				if (strlen($this->tabela)==0) { echo 'Table name not found'; exit; }
				$sql = "CREATE TABLE ".$table." (
  						id_doc serial NOT NULL,
  						doc_dd0 char(7),
  						doc_tipo char(5),
  						doc_ano char(4),
  						doc_filename text,
  						doc_status char(1),
  						doc_data integer,
  						doc_hora char(8),
  						doc_arquivo text,
  						doc_extensao char(4),
  						doc_size float,
  						doc_ativo integer
						) ";
				$rlt = db_query($sql);
						
				
				$sql = "CREATE TABLE  ".$table."_tipo (
					  id_doct serial NOT NULL,
  						doct_nome char(50),
  						doct_codigo char(5),
  						doct_publico integer,
  						doct_avaliador integer,
  						doct_autor integer,
  						doct_restrito integer,
  						doct_ativo integer
						) ";
				$rlt = db_query($sql);						
			}		
	}
