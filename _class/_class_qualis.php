<?php
class qualis
	{
	var $issn;
	var $nome;
	var $abrev;
	var $pais;
	var $update;
	var $site;
	var $estado;
	
	var $session;
	
	
	
	var $tabela_estrato = "qualis_estrato";	
	var $tabela_journal = "cited_journals";
	var $tabela_area = "qualis_area";
		function cp_cited()
			{
				$cp = array();
				array_push($cp,array('$H8','id_qe','',False,True));
				array_push($cp,array('$S9','eq_issn','',False,True));
				array_push($cp,array('$S4','eq_estrato','',False,True));
				array_push($cp,array('$S5','eq_area','',False,True));
				array_push($cp,array('$[2010-'.date("Y").']','eq_ano','',False,True));
				return($cp);
			}
	
		function read_link($url,$data)
			{
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);	
				$contents = curl_exec($ch);
				if (curl_errno($ch)) {
  					echo curl_error($ch);
	  				echo "\n<br />";
  					$contents = '';
				} else {
	  				curl_close($ch);
				}
				if (!is_string($contents) || !strlen($contents)) {
					echo "Failed to get contents.";
					$contents = '';
				}
				if (strpos($contents,'encoding="UTF-8"') > 0)
					{
						$contents = troca($contents,'encoding="UTF-8"','encoding="ISO-8859-1"');
						$contents = utf8_decode($contents);
					}
				return($contents);				
			}	
	
	function read_qualis_page()
		{
			$url = 'http://qualis.capes.gov.br/webqualis/publico/pesquisaPublicaClassificacao.seam;jsessionid=DCE67E4FA868DEA2D16DF4E59633B881.qualismodcluster-node-66?conversationPropagation=begin';
						
			$data = array('consultaPublicaClassificacaoForm' => 'consultaPublicaClassificacaoForm', 
							'consultaPublicaClassificacaoForm:btnPesquisarISSN' => 'Pesquisar',
							'javax.faces.ViewState'=>'j_id7',
							'consultaPublicaClassificacaoForm:issn'=>'1984-3755');
			$rlt = $this->read_link($url,$data);
			echo $rlt;
		}
	
	function structure()
		{
			$sql = "CREATE TABLE qualis_area
				(
				id_qa SERIAL NOT NULL,
				qa_descricao char (50),
				qa_ativo integer,
				qa_codigo char(5),
				qa_atualizado char(4)
				)";
			//$rlt = db_query($sql);
			
			$sql = "CREATE TABLE qualis_estrato
				(
					id_qe serial NOT NULL,
					eq_issn char(9),
					eq_estrato char(4),
					eq_area char(5),
					eq_ano char(4)
				)";
			//$rlt = db_query($sql);
			
			$sql = "CREATE TABLE cited_journals
				(
					id_cj serial NOT NULL,
					cj_codigo char(7),
					cj_issn char(9),
					cj_nome char(100),
					cj_abrev char(30),
					cj_nome_asc char(130),
					cj_pais char(3),
					cj_estado char(2),
					cj_site char(100),
					cj_ativo integer,
					cj_update integer
				)";
			//$rlt = db_query($sql);
			
			$sql = "ALTER TABLE  cited_journals ADD INDEX  key_cited_journal_issn (j_issn)";
			$sql = "CREATE INDEX key_cited_journal_issn ON cited_journals (cj_issn); ";
			
			//$rlt = db_query($sql);

			$sql = "ALTER TABLE  cited_journals ADD INDEX  key_cited_journal_asc (cj_nome_asc)";
			$sql = "CREATE INDEX key_cited_journal_asc ON cited_journals (cj_nome_asc); ";
			//$rlt = db_query($sql);
		}

		function mostra_dados()
			{
				$sx .= '<fieldset><legend>'.msg('journal_info').'</legend>';
				$sx .= '<table width="100%" class="lt0">';
				$sx .=  '<TR><TD colspan=2>NOME DA PUBLICAÇÃO';
				$sx .=  '<TR class="lt2"><TD colspan=2><B>'.$this->nome;
				
				$sx .=  '<TR><TD colspan=1>ISSN';
				$sx .= '<TD>NOME ABREVIADO';
				$sx .=  '<TR class="lt2"><TD colspan=1><B>'.$this->issn;
				$sx .= '<TD colspan=1><B>'.$this->abrev;				
				
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);
			}
		function mostra_qualis()
			{
				$issn = $this->issn;
				$sql = "select * from ".$this->tabela_estrato." 
						inner join ".$this->tabela_area." on eq_area = qa_codigo 
						where eq_issn = '$issn' 
						order by qa_descricao ";
				$rlt = db_query($sql);
				$sx .= '<table class="lt1">';
				while ($line = db_read($rlt))
				{
					$link = '<A HREF="cited_journal_ed.php?dd0='.$line['id_qe'].'">';
					$sx .= '<TR><TD>'.trim($line['qa_descricao']);
					$sx .= '<TD>'.trim($line['eq_estrato']);
					$sx .= '<TD>'.trim($line['eq_ano']);
					$sx .= '<TD>'.$link.'ed'.'</A>';
				}
				$sx .= '</table>';
				return($sx);
			}
		function le($id)
			{
				$sql = "select * from ".$this->tabela_journal." where id_cj = ".$id;
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->issn = trim($line['cj_issn']);
						$this->nome = trim($line['cj_nome']);
						$this->abrev = trim($line['cj_abrev']);
						$this->pais = trim($line['cj_pais']);
						$this->estado = trim($line['cj_estado']);
						$this->site = trim($line['cj_site']);
						$this->update = trim($line['cj_update']);
					}
				return(1);
			}

		function qualis_area($area)
			{
				$sql = "select * from qualis_area where qa_descricao = '$area' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->update_area();
						return($line['qa_codigo']);
					} else {
						return('');
						
						$sql = "insert into qualis_area (
						qa_descricao, qa_codigo, qa_ativo,
						qa_atualizado 
						) values (
						'$area','',1,
						'".date("Y")."')";
						
						$rlt = db_query($sql);
						$this->update_area();
						$sql = "select * from qualis_area where qa_descricao = '$area' ";
						$rlt = db_query($sql);
						$line = db_read($rlt);
						return($line['qa_codigo']);						
					}
			}

		function cited_inport_qualis()
			{
				$page = page();
				$sx .= '<form id="upload" action="'.$page.'" method="post" enctype="multipart/form-data">
					'.msg('file_tipo').'
    				<span id="post"><input type="file" name="arquivo" id="arquivo" /></span>
    				<input type="hidden" name="dd0" value="'.$dd[0].'"> 
    				<input type="hidden" name="dd1" value="'.$dd[1].'"> 
    				<input type="hidden" name="dd90" value="'.$dd[90].'"> 
    				<input type="submit" value="enviar arquivo" name="acao" id="idbotao" />    			
					</form>';				
				return($sx);
			}
		function cited_inport_qualis_post()
			{
				global $dd,$acao;
				if (strlen($acao) > 0)
					{
    					$temp = $_FILES['arquivo']['tmp_name'];
						$size = $_FILES['arquivo']['size'];
						if (strlen($temp) > 0)
							{
								$fld = fopen($temp,'r');
								$sc = '';
								while (!(feof($fld)))
									{
										$sr = fread($fld,1024);
										$sc .= $sr;
									}
								fclose($fld);
								$this->cited_inport_journal($sc);
							}
					}
			}
		function cited_inport_journal($sx)
			{
				$sx .= chr(13);
				$loop = 0;
				$sx = troca($sx,'TÍTULO','');
				$sx = troca($sx,'ISSN','');
				$sx = troca($sx,'ÁREA DE AVALIAÇÃO','');
				$sx = troca($sx,'ESTRATO','');
				$sx = troca($sx,'STATUS','');
				$sx = troca($sx,'"','');
				
				$xarea = "X";
				$sx = troca($sx,'Atualizado','');
				$cs = splitx(chr(13),$sx);
				for ($r=0;$r < count($cs);$r++)
					{
						$issn = substr($cs[$r],0,9);
						
						/* trocas do qualids */
						$ss = $cs[$r];
						$ss = troca($ss,' A1 ','[<A1>]');
						$ss = troca($ss,' A2 ','[<A2>]');
						$ss = troca($ss,' B1 ','[<B1>]');
						$ss = troca($ss,' B2 ','[<B2>]');
						$ss = troca($ss,' B3 ','[<B3>]');
						$ss = troca($ss,' B4 ','[<B4>]');
						$ss = troca($ss,' B5 ','[<B5>]');
						$ss = troca($ss,' C ','[<C >]');
						
						/* Nome do journal */
						$pos = strpos($ss,'[<');
						$journal_name = trim(substr($ss,9,$pos-9)); 
						$journal_name = troca($journal_name,"'",'´'); 
						/* Iniciao string */
						$is = substr($cs[$r],0,9).';';
						$isn = splitx(';',$cs[$r]);
						
						/* Qualis */
						$qualis = substr($ss,strpos($ss,'[<')+2,2);
						
						/* Area */
						$area = trim(substr($ss,strpos($ss,'>]')+2,100));
										

						if (substr($issn,4,1)=='-')
							{
								
								if ($xarea != $area)
									{
										$area = troca($area,"'",'´');
										echo '<HR>'.$area.'<HR>';
										$cod_area = $this->qualis_area($area);
										$xarea = $area;
										echo '<HR>';
										echo $cod_area . ' - '.$area;
										echo '<HR>';
									}

								echo '<BR>'.$issn.'-'.$area.'-['.$qualis.']-'.$journal_name;

								if ($cod_area != '')
									{
									$rs = $this->cited_journal_insert($issn, $journal_name);
									$ano = date("Y");
									$this->qualis_estrado($issn,$area,$qualis,$ano,$cod_area);
									}
							}					
					}
				exit;
				
				echo '<BR>Processado '.$loop.' registro.';
			}
		function cited_process_inport_ln($ln)
			{
				global $xarea;
				if (!(isset($xarea))) { $xarea = ''; }
				$ar = array();
				$ln .= chr(9);
				$erro=0;

				while ((strlen($ln) > 0) and ($erro < 10))
					{
						$pos = round(strpos($ln,chr(9)));
						if ($pos==0) { $erro = 20; }
						$erro++;
						$wd = substr($ln,0,$pos);
						$ln = substr($ln,$pos+1,strlen($ln));
						array_push($ar,$wd);
					}
				
				$rs = $this->cited_journal_insert($ar[0], $ar[2]);
				if ($rs == 1) 
				{
					if (strlen($xarea == 0)) 
						{
						echo '<HR>'.$ar[1].'<HR>'; 
						exit;
						$xarea = $this->qualis_area($ar[1]); 
						}
					$this->qualis_estrado($ar[0],$xarea,$ar[3],sonumero($ar[4])); 
				}
			}
		function qualis_estrado($issn,$area,$estrato,$ano,$area)
			{
				$issn = trim($issn);
				$estrato = trim($estrato);
				$ano = trim($ano);
				$area = trim($area);
			
				if ((strlen($area)==5) and (strlen($issn)==9) and (strlen($estrato)==2))
				{
				$sql = "select * from ".$this->tabela_estrato."
					where eq_issn = '$issn' and eq_area = '$area' 
					and eq_ano = '$ano' 
				";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$sql = "update ".$this->tabela_estrato."
							set eq_estrato = '$estrato'
							where eq_issn = '$issn' and eq_area = '$area' 
							and eq_ano = '$ano'
						";
					} else {
						$sql = "insert into ".$this->tabela_estrato." (
								eq_issn, eq_estrato, eq_area,
								eq_ano 
								) values (
								'$issn', '$estrato','$area',
								'$ano'); ";
					}
					$rlt = db_query($sql);
					return(1);
				} else {
					return(0);
				}				
			}
		function cited_journal_insert($issn,$journal)
			{
				$journal = substr($journal,0,50);
				$issn = trim($issn);
				if (strlen($issn)==9)
				{
				$sql = "select * from ".$this->tabela_journal."
					where cj_issn = '$issn' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						return(1);
					} else {
						$data = date("Ymd");
						$sql = "insert into ".$this->tabela_journal."
							(
							cj_issn,cj_nome,cj_abrev,
							cj_nome_asc,cj_codigo, cj_pais,
							cj_estado, cj_site, cj_ativo,
							cj_update
							) values (
							'$issn','$journal','',
							'','','',
							'','',1,
							$data
							)
						";
						$rlt = db_query($sql);
						return(1);
					}
				} else {
					return(0);
				}
			}
		function row_journal() {
			global $cdf, $cdm, $masc;
			$cdf = array('id_cj', 'cj_nome', 'cj_issn', 'cj_pais','cj_codigo');
			$cdm = array('cod', msg('journal_name'), msg('journal_issn'), msg('journal_country'),msg('codigo'));
			$masc = array('', '', '','','','','','','','SN');
			return (1);
		}		

		function row_area() {
			global $cdf, $cdm, $masc;
			$cdf = array('id_qa', 'qa_descricao', 'qa_atualizado', 'qa_ativo');
			$cdm = array('cod', msg('area_nome'), msg('area_atualizado'), msg('ativo'));
			$masc = array('', '', '','','','','','','','SN');
			return (1);
		}		
		function insert_journal($issn,$journal_name,$country)
			{
				$name = $journal_name;
				$name_asc = UpperCaseSql($journal_name);
				$date = date("Ymd");
				
				$sql = "insert into ".$this->tabela_journal." 
						(cj_issn, cj_nome, cj_abrev,
						cj_nome_asc, cj_codigo, cj_pais,
						cj_estado, cj_site, cj_ativo,
						cj_update
						) values (
						'$issn','$name','',
						'$name_asc','','',
						'','',1,
						$date
						)
				";
				$rlt = db_query($sql);
				$this->updatex_journal();
			}
		function cp_journal()
			{
				global $dd;
				$dd[4] = uppercasesql($dd[2]);
				$cp = array();
				array_push($cp,array('$H8','id_cj','',False,True));
				array_push($cp,array('$S9','cj_issn','ISSN',False,True));
				array_push($cp,array('$S100','cj_nome','Nome',True,True));
				array_push($cp,array('$S20','cj_abrev','Abrev',False,True));
				array_push($cp,array('$HV','cj_nome_asc','',False,True));
				array_push($cp,array('$H8','cj_codigo','',Codigo,True));
				array_push($cp,array('$S3','cj_pais','',Pais,True));
				array_push($cp,array('$S2','cj_estado','',Estado,True));
				array_push($cp,array('$S100','cj_site','',Site,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','cj_ativo','Ativo',True,True));
				array_push($cp,array('$U8','cj_update','',False,True));
				return($cp);
			}
			
	function update_area()
		{
			global $base;
			$c = 'qa';
			$c1 = 'id_' . $c;
			$c2 = $c . '_codigo';
			$c3 = 5;
			$sql = "update ".$this->tabela_area." set $c2 = lpad($c1,$c3,0) where $c2='' ";
			if ($base == 'pgsql') { $sql = "update " . $this ->tabela_area . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' ";
			}
			$rlt = db_query($sql);
			
		}		
	function updatex_journal() {
			global $base;
			$c = 'cj';
			$c1 = 'id_' . $c;
			$c2 = $c . '_codigo';
			$c3 = 7;
			$sql = "update cited_journals set $c2 = lpad($c1,$c3,0) where $c2='' ";
			if ($base == 'pgsql') { $sql = "update cited_journals set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' ";
			}
			$rlt = db_query($sql);
			
			$sql = "select * from cited_journals where cj_nome_asc = '' ";
			$rlt = db_query($sql);
			$sqlu = "";
			while ($line = db_read($rlt))
				{
					$nome_asc = Uppercase(trim(trim($line['cj_nome']).' '.trim($line['cj_abrev'])));
					$id = $line['id_cj'];
					$sqlu = "update cited_journals set cj_nome_asc = '$nome_asc' where id_cj = $id; ".chr(13);
					$rlt = db_query($sqlu);
				}
		}
}
?>
