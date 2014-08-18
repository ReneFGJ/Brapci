<?
class ic
	{
		var $titulo;
		var $texto;
		var $journal=0;
		var $tabela = 'ic_noticia';
		var $enviar_email = 0;
		
		function mostrar($ln,$tipo='html')
			{
				return(mst($ln));
			}
		function form($ref='')
			{
				global $http,$dd,$acao;
				
				$ttt = $this->ic($ref);
				
				$titulo = $ttt['nw_titulo'];
				$texto = $ttt['nw_descricao'];
				$ic_id = $ttt['id_nw'];
				
				$this->titulo = $titulo;
				$this->texto = $texto;
				
				$link = '<A HREF="'.$http.'pibicpr/comunicacao_editar.php?dd0='.$ic_id.'&dd2='.$ref.'" target="_new">editar texto</A>';
				$sx = '<form method="get" action="'.page().'">
							<table width="100%" class="tabela00">
								<TR valign="top">
									<TD width="50%">
										<input type="hidden" name="dd0" value="'.$dd[0].'">
										<input type="hidden" name="dd1" value="'.$dd[1].'">
										<input type="hidden" name="dd2" value="'.$dd[2].'">
										<input type="hidden" name="dd3" value="'.$dd[3].'">
										<input type="hidden" name="dd4" value="'.$dd[4].'">
										<input type="hidden" name="dd5" value="'.$dd[5].'">
										<input type="hidden" name="dd90" value="'.$dd[90].'">
										<input type="submit" id="ic_send" value="enviar e-mail">
										<input type="button" id="ic_visible" value="visualizar texto do e-mail">
										<BR>
										<input type="checkbox" name="dd81" value="1">Confirma envio do(s) e-mail(s)
									<TD width="50%">
										<div id="ic_post" style="display: none;">
											<table width="100%" bgcolor="#d0d0d0" cellpadding="5" cellspacing="5">
												<TR>
													<TD class="lt3"><B>'.mst($titulo).'</B>
												<TR>
													<TD class="tabela01">'.mst($texto).'
												<TR>
													<TD>'.$link.'
											</table>
										</div>
							</table>				
					   </form>
					   <script>
					   	$("#ic_visible").click(function() {
					   		
							$("#ic_post").toggle();
					   	});
					   		
					   </script>
				';
				if ($dd[81]=='1') { $this->enviar_email = 1; }				
				return($sx);
			}
		function row_filter($filter)
			{
				if ($this->journal > 0) { $wh = ' nw_journal_id = '.$this->journal; }
				$sql = "select * from ".$this->tabela." 
						where nw_ref like '".$filter."%'  
						$wh
						order by nw_ref
						";
				$rlt = db_query($sql);
				$sx = '<table width="98%" class="tabela00">';
				$sx .= '<TR><TH>ref.<TH>Título';
				$js = '';
				while ($line = db_read($rlt))
				{
					$link = '<A HREF="comunicacao_editar.php?dd0='.$line['id_nw'].'&dd90='.checkpost($line['id_nw']).'">editar</a>';
					$divid = 'com'.strzero($line['id_nw'],6);
					$sx .= '<TR>';
					$sx .= '<TD width="10%" class="tabela01"><A HREF="#" id="'.$divid.'">'.$line['nw_ref'].'</a>';
					$sx .= '<TD width="90%" class="tabela01">'.$line['nw_titulo'];
					$sx .= '<TD width="90%" class="tabela01">'.$link;
					$sx .= '<TR>';
					$sx .= '<TD><TD width="90%"><div id="'.$divid.'a" style="display: none;">'.$this->mostrar($line['nw_descricao']).'</div>';
					
					$js .= '$("#'.$divid.'").click(function() { $("#'.$divid.'a").toggle(); });'.chr(13).chr(10);  
				}
				$sx .= '</table>';
				$sx .= '
				<script>
				'.$js.'
				</script>
				';
				
				return($sx);				
			}
			
		
		function row()
			{
				global $cdf, $cdm, $masc;
				$cdf = array('id_nw','nw_ref','nw_titulo');
				$cdm = array('Código','Ref.','Título');
				$masc = array('','','');
				return(1);				
			}
		function cp()
			{
				global $journal_id;
				$cp = array();
				$nc = $nucleo.":".$nucleo;
				array_push($cp,array('$H4','id_nw','id_nw',False,False,''));
				array_push($cp,array('$HV','nw_journal',round($journal_id),True,True,''));
				array_push($cp,array('$S20','nw_ref','Ref.página',True,True,''));
				array_push($cp,array('$S200','nw_titulo','Titulo da mensagem',True,True,''));
				array_push($cp,array('$T60:8','nw_descricao','Conteúdo em (HTML)',False,True,''));
				//dd5
				array_push($cp,array('$D8','nw_dt_de','Mostrar de',True,True,''));
				array_push($cp,array('$D8','nw_dt_ate','até',True,True,''));
				array_push($cp,array('$S120','nw_fonte','(Citar fonte)',False,True,''));
				array_push($cp,array('$S120','nw_link','(Link externo)',False,True,''));
				array_push($cp,array('$HV','nw_secao',1,False,True,''));
				//dd10
				array_push($cp,array('$O pt_BR:Portugues&en:Inglês','nw_idioma','Idioma',True,True,''));
				array_push($cp,array('$U8','nw_dt_cadastro','data',False,True,''));
				array_push($cp,array('$B8','','Salvar >>',False,True,''));
				return($cp);				
			}

		function ic($cod='',$jid='')
			{
				if (strlen($cod) > 0)
				{
				$sql = "select * from ic_noticia 
					where nw_ref = '".$cod."' ";
				
				if (strlen($jid) > 0) { $sql .= " and (journal_id = $jid)"; }
				$sql .= " limit 1";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				} else {
					$line=array();
				}
				return($line);
			}
		
	}
