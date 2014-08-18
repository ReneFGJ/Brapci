<?php
class submit
	{
		var $id_doc;
		var $doc_protocolo;
		var $doc_1_titulo;
		var $doc_autor_principal;
		var $doc_tipo;
		
		var $doc_clinic;
		var $doc_data;
		var $doc_hora;
		var $doc_dt_atualizado;
		var $doc_status;
		var $doc_xml;

		var $tabela = 'cep_submit_documento';
		function protocolos_em_submissao()
			{
				$rst = $this->protocolo_status('@');
				return($rst);
			}
			
		function le($id='')
			{
				if (strlen($id) > 0) { $this->doc_protocolo = $id; }
				$sql = "select * from ".$this->tabela;
				$sql .= " where doc_protocolo = '".$this->doc_protocolo."'";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->id_doc = $line['id_doc'];	
						$this->doc_1_titulo = $line['doc_1_titulo'];	
						$this->doc_protocolo = $line['doc_protocolo'];	
						$this->doc_tipo = $line['doc_tipo'];	
						$this->doc_clinic = $line['doc_clinic'];	
						$this->doc_data = $line['doc_data'];	
						$this->doc_hora = $line['doc_hora'];	
						$this->doc_dt_atualizado = $line['doc_dt_atualizado'];	
						$this->doc_autor_principal = $line['doc_autor_principal'];	
						$this->doc_status = $line['doc_status'];	
						$this->doc_xml = $line['doc_xml'];
						
						if (strlen($this->doc_1_titulo)==0) { $this->doc_1_titulo = msg('not_defined'); }	
					}
				return(1);
			}

		function protocolo_acoes()
			{
				$sta = $this->doc_status;
				$opc = array();
				if ($sta == '@')
					{
						array_push($opc,array(msg('acao_edit'),'protocolo_sel.php?dd0='.$this->doc_protocolo.'&dd90='.checkpost($this->doc_protocolo)));
						array_push($opc,array(msg('acao_cancel'),'protocolo_cancel.php?dd0='.$this->doc_protocolo.'&dd90='.checkpost($this->doc_protocolo)));
											}
				return($opc);
			}
		function protocolo_acoes_botoes($acoes)
			{
				$sx = '';
				$sj = "" ;
				for ($r=0;$r < count($acoes);$r++)
					{
						$sx .= '<input type="button" value="'.$acoes[$r][0].'" onclick="acao'.$r.'();">';chr(13);
						$sj .= "function acao".$r.'()'.chr(13);
						$sj .= "{ window.self.location.href = ".'"'.$acoes[$r][1].'"; }'.chr(13);
					}
				$sj = '<script>'.chr(13).$sj.'</script>'.chr(13);
				$sx = chr(13).chr(13).$sx.chr(13).$sj;
				return($sx);
			}
			
		function protocolo_cancelar()
			{
				global $dd;
				$this->le($dd[0]);
				$_SESSION['protocolo']='';
				$sql = "update ".$this->tabela." set doc_status = 'X', doc_dt_atualizado = ".date("Ymd")." where doc_protocolo = '".$this->doc_protocolo."'";
				$rlt = db_query($sql);
				return(1);				
			}
		function protocolo_seleciona()
			{
				global $dd;
				$this->le($dd[0]);
				$_SESSION['protocolo']=$this->dpc_protocolo;
				$_SESSION['id']=$this->doc_autor_principal;
				return(1);				
			}
			
		function protocolo_dados()
			{
				global $tab_max,$date,$messa;
				$sx .= '<table width="'.$tab_max.'">';
				$sx .= '<TR><TD>';
				$sx = '<fieldset><legend>'.msg('protocol_data').'</legend>';
				$sx .= '<table width="'.$tab_max.'" border=0>';
				$sx .= '<TR><TD class="lt0" colspan=4 >'.msg('titulo');
				$sx .= '<TR><TD class="lt2" colspan=4 ><B>'.$this->doc_1_titulo.'</B>';
				$sx .= '<TR>';
				$sx .= '<TD class="lt0" colspan=1 >'.msg('data');
				$sx .= '<TD class="lt0" colspan=1 >'.msg('time');
				$sx .= '<TD class="lt0" colspan=1 >'.msg('update');
				$sx .= '<TD class="lt0" colspan=1 >'.msg('status');

				/**
				 * Status
				 */

				 $sta = 'status_'.trim($this->doc_status);
				 
				$sx .= '<TR>';
				$sx .= '<TD class="lt2" colspan=1 >'.$date->stod($this->doc_data);
				$sx .= '<TD class="lt2" colspan=1 >'.$this->doc_hora;
				$sx .= '<TD class="lt2" colspan=1 >'.$date->stod($this->doc_dt_atualizado);
				$sx .= '<TD class="lt2" colspan=1 >'.msg($sta);
				
				$sx .= '</table>';
				$sx .= '</fieldset>';
				$sx .= '</table>';
				return($sx);
			}
		function protocolo_status($sta)
			{
				$sql = "select * from ".$this->tabela;
				$sql .= " where doc_autor_principal = '".$this->doc_autor_principal."' ";
				$sql .= " and doc_status = '".$sta."' ";
				$sql .= " order by doc_protocolo desc ";
				$rlt = db_query($sql);
				$rst = array();
				while ($line = db_read($rlt))
					{ array_push($rst,$line); }
				return($rst);
			}
		function protocolos_mostrar($rst)
			{
				global $colunas;
				$sx = '';
				$sh = '<TR>';
				$sh .= '<TH>'.msg('protocolo');
				$sh .= '<TH>'.msg('titulo');
				$sh .= '<TH>'.msg('data');
				$sh .= '<TH>'.msg('status');
				
				for ($r=0;$r < count($rst);$r++)
					{
						$line = $rst[$r];
						$id = $line['doc_protocolo'];
						$sx .= '<TR '.coluna().'>';

						$link = '<a href="protocolo.php?dd0='.$id.'&dd90='.checkpost($id).'">';
						 
						$sx .= '<TD align="center">';
						$sx .= $link;
						$sx .= $line['doc_protocolo'];
						
						$sx .= '<TD>';
						$sx .= $link;
						$tit = trim($line['doc_1_titulo']);
						if (strlen($tit)==0) { $tit = msg('not_definid'); }
						$sx .= $tit;
						
						$sx .= '<TD align="center">';
						$sx .= stodbr($line['doc_data']);

						$sx .= '<TD align="center">';
						$sta = $line['doc_status'];
						if ($sta == '@') { $sta = msg('in_submit'); }
						$sx .= $sta;
					}
				if (strlen($sx) > 0) { $sx = $sh.$sx; }
				return($sx);
			}
	}
