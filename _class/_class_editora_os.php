<?php
class os
	{
		var $os;
		var $titulo;
		var $cliente;
		var $projeto;
		var $contato;
		var $contato_email;
		var $entrada_data;
		var $entrada_hora;
		var $obs;
		var $data_previsao;
		var $saida_data;
		var $saida_hora;
		var $status;
		var $contrato;
		var $valor;
		var $line;
		
		var $tabela = "editora_os";		
		function os_historico($historico)
			{
				echo $historico.'=------';
				$data = date("Ymd");
				$hora = date("H:i:s");
				$os = $this->os;
				$sql = "insert into ".$this->tabela."_historico 
					(hr_data, hr_hora, hr_os,
					hr_historico
					) values (
					'$data','$hora','$os',
					'$historico')
				";
				
				$rlt = db_query($sql);
				return(1);
			}
		function os_historico_mostra()
			{
				$sql = "select * from ".$this->tabela."_historico 
					where hr_os = '".$this->os."' ";
					
				$rlt = db_query($sql);
				$sx .= '<table class="lt1" width="100%">';
				$sx .= '<TR><TH>Data<TH>Hora<TH>Histórico';
				while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD>'.stodbr($line['hr_data']);
					$sx .= '<TD>'.($line['hr_hora']);
					$sx .= '<TD>'.($line['hr_historico']);
				}
				$sx .= '</table>';
				return($sx);
			}
		function designar_atual()
			{
				global $dd,$acao;
				$status = $this->line['os_status'];
				if (($status != '@') and ($staqtus != 'A'))
					{ return(1); }
				
				if (strlen($dd[10]) > 0)
					{
						$assunto = 'Editora - OS '.$this->os;
						$texto = 'Prezado colaborador, <BR><BR>';
						$texto .= 'Foi indicado um serviço para sua execução.';
						$texto .= '<BR><BR>Acesse sistema.';
						$texto .= '<BR><BR>OS: '.$this->os;
						$sql = "select * from usuario where id_us = ".$dd[10];
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
						{
							enviaremail('viviane.goncalves@pucpr.br','',$assunto,$texto);
							enviaremail(trim($line['us_email']),'',$assunto,$texto);
							echo '<BR>Enviado para :'.trim($line['us_email']);
							echo '==============';
							$this->os_historico('Indicado para '.trim($line['us_nome']));
							echo '<BR>FIM';							
						}
						
						$sql = "update ".$this->tabela." set os_revisor_atual = '".$dd[10]."' ";
						$sql .= " where os_codigo = '".$this->os."' ";
						$rlt = db_query($sql);

						return(1);
					}
				$sql = "
					select * from (
					select grm_user from editora_grupos
					inner join editora_grupos_membros on grm_grupo = grp_codigo 
					where grp_codigo = '0000002' or
							grp_codigo = '0000003' or
							grp_codigo = '0000006' 
					group by grm_user
					) as tabela 
					inner join usuario on grm_user = trim(to_char(id_us,'0000000'))
					where us_ativo = 1
					order by us_nome
				";
				$rlt = db_query($sql);
				$sa = '';
				while ($line = db_read($rlt))
					{
						$sa .= '<option value="'.strzero($line['id_us'],7).'">';
						$sa .= trim($line['us_nome']);
						$sa .= '</option>';
					}
				$sr = '';
				$sr .= '<fieldset><legend>Indicar Executor da OS</legend>';
				$sr .= '<form method="post" action="">';
				$sr .= '<select name="dd10">';
				$sr .= '<option value="">:::Indicar:::</option>';
				$sr .= $sa;
				$sr .= '</select>';
				$sr .= '<input type="submit" value="Indicar >>>">';
				$sr .= '</form>';
				$sr .= '</fieldset>';
				return($sr);
				}
		function cp()
			{
				global $dd;
				$cp = array();
				array_push($cp,array('$H8','id_os','id',False,true));
				array_push($cp,array('$H8','os_codigo','',False,true));
				array_push($cp,array('$S100','os_cliente','Cliente',true,true));
				array_push($cp,array('$S100','os_cliente_contato','Solicitante',true,true));
				array_push($cp,array('$S100','os_cliente_email','E-mail do solicitante',true,true));
				
				array_push($cp,array('${','','Descrição do serviço',False,true));
				array_push($cp,array('$S100','os_titulo','Título do serviço',true,true));
				array_push($cp,array('$T60:5','os_descricao','Descrição das atividades',true,true));
				
				if (strlen($dd[0])==0)
					{
					array_push($cp,array('$U8','os_entrada_data','',False,true));
					array_push($cp,array('$HV','os_entrada_hora',date("H:i:s"),False,true));
					array_push($cp,array('$HV','os_status','@',False,true));
					array_push($cp,array('$HV','os_obs','',False,true));
					array_push($cp,array('$HV','os_resolucao','',False,true));
					
					array_push($cp,array('$HV','os_saida_data','19000101',False,true));
					array_push($cp,array('$HV','os_saida_hora','',False,true));
					array_push($cp,array('$HV','os_ativo','1',False,true));
					}
				
				array_push($cp,array('$S20','os_contrato','Nº contrato',true,true));
				array_push($cp,array('$N8','os_valor','Valor dos serviços',true,true));
				array_push($cp,array('$D8','os_previsao','Previsão de entrega',False,true));
				
				array_push($cp,array('$}','','Descrição do serviço',False,true));
				
				return($cp);
			}

		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_os','os_codigo','os_titulo','os_cliente','os_cliente_contato','os_previsao','os_status');
				$cdm = array('cod','os_codigo',msg('trabalho'),msg('cliente'),msg('contato'),msg('previsao'),msg('status'));
				$masc = array('','','','','','D','#','');
				return(1);				
			}	
			
		function le($id)
			{
				$sql = "select * from ".$this->tabela." where id_os = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->os = $line['os_codigo'];
						$this->cliente = $line['os_cliente'];
						$this->contato = $line['os_cliente_contato'];
						$this->contato_email = $line['os_cliente_contato'];
						$this->line = $line;
						return(1);
					}			
			}
		function mostra_arquivos()
			{
				
			}
		function mostra_os()
			{
				$sx = '<fieldset><legend>Ordem de Serviço</legend>';
				$sx .= '<TABLE width="100%" class="lt1">';
				$sx .= '<TR valign="top"><TD class="lt0">NOME DO CLIENTE';
				
				$sx .= '<TD align="right" width="100" rowspan=6>';
					$sx .= '<fieldset><legend>os nr</legend>';
					$sx .= '<center><font class="lt4">'.$this->os.'</font>';
					$sx .= '<BR><font class="lt0">';
					$sx .= stodbr($this->line['os_entrada_data']).'<BR>'.$this->line['os_entrada_hora'];
					$sx .= '</font>';
					$sx .= '</fieldset>';
				
				$sx .= '<TR><TD class="lt2"><B>'.$this->cliente;
				
				$sx .= '<TR><TD class="lt0">CONTATO';
				$sx .= '<TR><TD class="lt2"><B>'.$this->contato;

				$sx .= '<TR><TD class="lt0">e-mail';
				$sx .= '<TR><TD class="lt2"><B>'.$this->line['os_cliente_email'];
				$sx .= '</table>';
				$sx .= '</fieldset>';
				$sx .= '<h2>Descrição do serviço</h2>';
				$sx .= '<fieldset>';
				$sx .= '<TABLE width="100%" class="lt1">';
				$sx .= '<TR><TD class="lt0">TÍTULO';
				$sx .= '<TR><TD class="lt2"><B>'.$this->line['os_titulo'];				
				$sx .= '<TR><TD class="lt0">DESCRIÇÃO';
				$sx .= '<TR><TD class="lt2"><B>'.mst($this->line['os_descricao']);				
				$sx .= '<TR><TD class="lt0">PREVISÃO DE ENTREGA';
				$sx .= '<TR><TD class="lt2"><B>'.stodbr($this->line['os_previsao']);				
				$sx .= '</table>';
				$sx .= '</fieldset>';
				
				$sx .= '<h2>Finalização do serviço</h2>';
				$sx .= '<fieldset>';
				$sx .= '<TABLE width="100%" class="lt1">';
				$sx .= '<TR><TD class="lt0">DATA DE ENCERRAMENTO';
				$sx .= '<TR><TD class="lt2"><B>'.stodbr($this->line['os_saida_data']).' '.$this->line['os_saida_hora'];				
				$sx .= '<TR><TD class="lt0">DESCRIÇÃO';
				$sx .= '<TR><TD class="lt2"><B>'.mst($this->line['os_resolucao']);				
								
				$sx .= '</table>';
				$sx .= '</fieldset>';				

				return($sx);
			}

		function structure()
			{

				$sql = "CREATE TABLE ".$this->tabela."_historico
					(id_hr serial,
						hr_data integer, 
						hr_hora char(8), 
						hr_os char(7),
						hr_historico char(100)
					);";
					$rlt = db_query($sql);
					exit;

				$sql = "DROP TABLE editora_os";
				//$rlt = db_query($sql);
				
				$sql = "CREATE TABLE editora_os
					(
					id_os serial NOT NULL,
					os_codigo char(7),
					os_cliente char(100),
					os_cliente_contato char(100),
					os_cliente_email char(100),
					
					os_recepcao_log char(8),
					os_revisor_atual char(8),
					
					os_titulo char(100),
					os_descricao text,
					os_entrada_data int,
					os_entrada_hora char(8),
					
					os_saida_data int,
					os_saida_hora char(8),
					os_previsao int,
					os_resolucao text,
					
					os_obs text,					
					os_status char(1),
					os_contrato char(20),
					os_valor float,
					os_ativo int										
					)
					";
				//$rlt = db_query($sql);
			}
		function updatex()
			{
				global $base;
				$c = 'os';
				$c1 = 'id_' . $c;
				$c2 = $c . '_codigo';
				$c3 = 7;
				$sql = "update " . $this -> tabela . " set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base == 'pgsql') { $sql = "update " . $this -> tabela . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' "; }
				$rlt = db_query($sql);
			}
	}
