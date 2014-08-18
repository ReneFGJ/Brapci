<?php
class formulario
	{
	var $nr_ordem = 1;
	var $nr_log = 'RENE.GABRIEL';
	var $vinculo = "COLABORADOR COM VÍNCULO";
	
	var $solicitante;
	var $solicitante_telefone;
	var $solicitante_nome; 
	
	var $ig_centro_resultado = "103507";
	var $ig_centro_nome = "Administração da Pró-Reitoria de Pesquisa e Pós-Graduação";
	var $ig_empresa = "Associação Paranaense de Cultura - APC";
	var $ig_filial = "PUCPR - Campus Curitiba";
	var $ig_classificacao = "DESPESA";
	var $ig_pa = "Repasse de 3% - projeto Atração e Retenção de Talentos";
	var $ig_periodo_de = 'junho';
	var $ig_periodo_ate = 'junho/2014';
	var $ig_vr = 100;
	var $ig_horas = 0;
	
	var $ordenador_necessidade;
	var $ordenador_gasto;
	
	var $beneficiario = '';
	var $beneficiario_nome = '';
	var $beneficiario_valor = 0;
	var $artigo_id = 0;
	
	var $tabela = 'bonificacao'; 
	
	function crs($cr)
		{
			switch ($cr)
				{
				case '103507': $ncr = 'Adminstração da Pró-Reitoria de Pesquisa e Pós-Graduação';
					break;
				case '103300': $ncr = 'Diretoria de Pesquisa e Programas Stricto Sensu';
					break;
				case '103309': $ncr = 'Núcleo do Fundo de Pesquisa';
					break;
				default:
					$ncr = '??????';
				}
			return($ncr);
		}
	

	function cp_cr()
		{
			global $dd,$acao,$user;
			$cr = ' : ';
			$cr .= '&103507:103507 - Adminstração da Pró-Reitoria de Pesquisa e Pós-Graduação';
			$cr .= '&103300:103300 - Diretoria de Pesquisa e Programas Stricto Sensu';
			$cr .= '&103309:103309 - Núcleo do Fundo de Pesquisa';
			$hv = 0;
		
			$cp = array();
			array_push($cp,array('$H8','id_bn','',False,False));
			array_push($cp,array('$O '.$cr,'bn_cr','Centro de resultado',True,True));						
			return($cp);			
		}

	function cp()
		{
			global $dd,$acao,$user;
			$cr = ' : ';
			$cr .= '&103507:103507 - Adminstração da Pró-Reitoria de Pesquisa e Pós-Graduação';
			$cr .= '&103300:103300 - Diretoria de Pesquisa e Programas Stricto Sensu';
			$cr .= '&103309:103309 - Núcleo do Fundo de Pesquisa';
			$hv = 0;

			$dd[6] = $user->user_login;
		
			$cp = array();
			array_push($cp,array('$H8','id_crp','',False,False));
			array_push($cp,array('$O ART:Bonificação de artigo','crp_tipo','Tipo',True,True));
			array_push($cp,array('$O '.$cr,'crp_tipo','Centro de resultado',True,True));
			array_push($cp,array('$N8','crp_valor','Valor pagamento',True,True));
			array_push($cp,array('$HV','crp_valor_hora','1',True,True));
			array_push($cp,array('$HV','crp_horas_minuto','1',True,False));
			array_push($cp,array('$S20','crp_login','Login',True,False));
			array_push($cp,array('$S8','','Beneficiário',True,False));
			array_push($cp,array('$S60','','Nome',True,False));
			array_push($cp,array('$HV','crp_beneficiario',$dd[7],True,False));
			
			array_push($cp,array('$O '.$dd[8].':'.$dd[8],'crp_doc_original','Prof. Original',True,True));
			
			array_push($cp,array('$A','','Período de execução da atividade',False,False));
			array_push($cp,array('$S20','crp_data_1_str','De',True,True));
			array_push($cp,array('$S20','crp_data_2_str','à',True,True));
			//array_push($cp,array('$}','','Período de execução da atividade',True,False));
			
			array_push($cp,array('$T60:5','crp_descricao','descrição',True,True));		
			//$sql = "alter table ".$this->tabela." add column crp_data_1_str char(20)";
			//$rlt = db_query($sql);	
			//$sql = "alter table ".$this->tabela." add column crp_data_2_str char(20)";
			//$rlt = db_query($sql);	
			return($cp);			
		}
	
	function updatex()
		{
			
		}
	function structure()
		{
			$sql = "
			create table cr_pagamento
				{
					id_crp serial not null,
					crp_tipo char(3),
					crp_cr char(6),
					crp_nrpag char(7),
					crp_doc_original char(7),
					crp_status char(1),
					crp_descricao text,
					crp_classificacao char(40),
					crp_valor float;
					crp_valor_hora float;
					crp_horas_minuto float,
					crp_beneficiario char(8),
					crp_ordenador_necessidade char(8),
					crp_ordenador_gasto char(8),
					crp_data int,
					crp_hora char(5),
					crp_login char(20),
					crp_data_1 integer,
					crp_data_2 integer
				}
			";
			$rlt = db_query($sql);
		}
	
	function show_botton_create()
		{
			$sx = '
			<input type="button" 
						value="gerar formulario" 
						onclick="newxy2(\'pucpr_formulario.php?dd7='.$this->beneficiario
						.'&dd50='.strzero($this->artigo_id,7).'&dd8='.trim($this->beneficiario_nome)
						.'\',700,600);">
			<BR>';
			return($sx);
		}

	function show_horas($min)
		{
			$hora = (int)($min/(60));
			$min = $min - $hora * 60;
			$h = strzero($hora,2).':'.strzero($min,2);
			return($h);
		}
	function set_dados($oj)
		{
			global $user, $nw, $hd, $dd;
			
			$this->nr_ordem = strzero($dd[0],7);
			$user_login = trim($_SESSION['user_login']);
			$this->nr_log = $user_login;
			$sql = "select * from usuario where us_login = '".$user_login."'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->solicitante = trim($line['us_nome']);
					$this->solicitante_telefone = trim($line['us_endereco']);
					$this->solicitante_email = trim($line['us_email']);
					if (strlen($this->solicitante_nome))
						{
							echo 'Nome do usuário não cadastrado';
							exit;
						}
				} else {
					echo 'Usuário não autorizado';
					exit;
				}
			//nr_log
			$line = $oj->line;
			$id = $line['id_bn'];
			$sql = "select * from bonificacao where id_bn = ".$id;
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->beneficiario_nome = trim($line['bn_professor_nome']);
					$this->beneficiario = trim($line['bn_professor']);
					$vlr = round($line['bn_valor'] * 100)/100;
					$this->beneficiario_valor = number_format($vlr,2);
					$this->beneficio_extenso = extenso($vlr);

					$ncr = $line['bn_cr'];
					$this->ig_centro_resultado = trim($ncr);
					$crs_nome = $this->crs($ncr);
					$this->ig_centro_nome = $crs_nome;
					$vlrh = $this->ig_vr;
					$tempo = $vlr / $vlrh * 60;
					$this->ig_horas = $tempo;
					$des = $line['bn_descricao'];
					$this->bn_descricao = $des;
					
					$cr = new cr;
					$this->ordenador_necessidade = $cr->recupera_ordenador_necessidade($ncr);
					$this->ordenador_necessidade_funcao = $cr->recupera_ordenador_necessidade_funcao;
					$this->ordenador_gasto = $cr->recupera_ordenador_gasto($ncr);
					$this->ordenador_gasto_funcao = $cr->recupera_ordenador_gasto_funcao;
					$this->ig_pa = $line['bn_nome'];
					$this->ig_periodo_de = $line['crp_data_1_str'];
					$this->ig_periodo_ate = $line['crp_data_2_str'];
				}
		}
	function form_solicitacao_pagamento()
		{
			$sx .= '
			<style>
				body { font-family: Arial, Tahoma, Verdana; font-size: 12px; }
				.sz16 { font-size: 17px; }
				.sz14 { font-size: 15px; }
				.sz10 { font-size: 11px; }
				.sz8 { font-size: 9px; }
				.bi { border-bottom: 1px solid #000000; }
				.b1 { border: 2px solid #000000; padding: 2px; }
				.sp { height: 6px; }
			</style>
			';
			$sx .= '<table width="100%" cellpadding="0" cellspacing="0" class="b1" border=0 >';
			$sx .= '<TR>
						<TD width="10%">
						<TD width="10%">
						<TD width="3%">
						<TD width="10%">
						<TD width="17%">
						<TD width="10%">
						<TD width="5%">
						<TD width="5%">
						<TD width="15%">
						<TD width="15%">
					';
			/* TIPO */
			$sx .= '<TR><TD class="sz14" colspan="10" style="padding: 4px; border: 2px solid #000000; background-color: #FFFF99;"><B>SOLICITAÇÃO DE PAGAMENTO</B></TR>';
			$sx .= '<TR><TD class="sz10" colspan="10" style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"><B>TIPO DE PAGAMENTO</B></TR>';
			$sx .= '<TR><TD rowspan=2 colspan=8 align="center" class="sz16 b1" ><B>'.$this->vinculo.'</B>';
			$sx .= '<TD colspan=2 class="sz10 b1" align="center" >USO EXCLUSIVO SETOR DE FINANÇAS';
			$sx .= '<TR>';
			$sx .= '<TD  colspan=1 class="sz10 b1" >Data<BR>&nbsp;';
			$sx .= '<TD  colspan=1 class="sz10 b1" >AP<BR>&nbsp;';
			
			$sx .= '<TR><TD class="sz10" colspan="10" 
							style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
							><B>INFORMAÇÕES GERAIS</B></TR>';
			$sx .= '<TR><TD align="right" class="sz10"><NOBR>Centro de Resultado: 
						<TD align="left" class="sz10" colspan=9><B>'.$this->ig_centro_resultado.' - 
						'.$this->ig_centro_nome.'</B>';
			
			$sx .= '<TR><TD align="right" class="sz10">Empresa: 
						<TD colspan=9 class="sz10"><B>'.$this->ig_empresa.'</B>';
			
			$sx .= '<TR><TD align="right" class="sz10">Filial: 
						<TD colspan=9 class="sz10"><B>'.$this->ig_filial.'</B>';
			
			$sx .= '<TR><TD align="right" class="sz10">Classificação: 
						<TD colspan=9 class="sz10"><B>'.$this->ig_classificacao.'</B>';

			$sx .= '<TR><TD align="right" class="sz10" colspan=2
						><nobr>	Programa de aprendizagem / Módulo: 
						<TD colspan=9 class="sz10"><B>'.$this->ig_pa.'</B>';
			
			$sx .= '<TR><TD align="right" class="sz10" colspan=2
						>Período de Execução da Atividade: ';
			$sx .= '<TD class="sz10" colspan=7
						>de <B>'.$this->ig_periodo_de.'</B> à <B>'.$this->ig_periodo_ate.'</B>';
			$sx .= '<TD class="sz10" colspan=1
						><NOBR>Horas (Qtda.): <B>'.$this->show_horas($this->ig_horas).'</B>';
			$sx .= '<TR><TD align="right" class="sz10" 
							colspan=3 
							>Valor Base (hora/aula ou hora técnica):';
			$sx .= '<TD class="sz10"><B>R$ '.number_format($this->ig_vr,2,',','.').'</B>';
			
			$sx .= '<TR><TD colspan=10 class="sp">';
			
			/* Informações para Pagamento */
			$sx .= '<TR><TD class="sz10" colspan="10" style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"><B>Informações para Pagamento</B></TR>';
			
			$sx .= '<TR><TD colspan=1 align="right" class="sz10"><B>Nome: </B>';
			$sx .= '<TD colspan=7 class="sz10" >&nbsp;<B>'.$this->beneficiario_nome.'</B>';
			$sx .= '<TD class="sz10" align="right"><NOBR>Código funcional: ';
			$sx .= '<TD class="sz10">&nbsp;<B>'.$this->beneficiario.'</B>';

			$sx .= '<TR><TD colspan=1 align="right" class="sz10"><B>Valor a Pagar R$: </B>';
			$sx .= '<TD colspan=9 class="sz10">&nbsp;'.$this->beneficiario_valor;
			$sx .= '('.trim($this->beneficio_extenso).')';
			$sx .= '<TR><TD colspan=10 class="sp">';
			
			/* Descrição dos serviços */
			$sx .= '<TR><TD class="sz10" 
						colspan="10" 
						style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
						><B>Descrição do Serviço / Justificativa:</B></TR>';
			
			$sx .= '<TR><TD colspan=10 height="60" class="sz10">'.$this->bn_descricao;			
			$sx .= '<TR><TD colspan=10 class="sp" >';	
			
			/* Preenchimento Exclusivo D.R.H. */
			$sx .= '<TR><TD class="sz10" 
						colspan="10" 
						style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
						><B>Preenchimento Exclusivo D.R.H.</B></TR>';
			$sx .= '<TR><TD colspan=3 class="sz10" align="right">Incluso na Folha de Pagamento do mês de:';
			$sx .= '<TD class="bi" colspan=7	>&nbsp';	

			$sx .= '<TR><TD colspan=1 class="sz10" align="right">Nome: ';
			$sx .= '<TD class="bi" colspan=7>&nbsp';	

			$sx .= '<TD colspan=1 class="sz10" align="right">Data: ';
			$sx .= '<TD class="bi">&nbsp';	

			$sx .= '<TR><TD colspan=1 class="sz10" align="right">Assinatura: ';
			$sx .= '<TD class="bi" colspan=9>&nbsp';
			
			$sx .= '<TR><TD colspan=10 class="sp">';	

			/* Dados do solicitante */
			$sx .= '<TR><TD class="sz10" 
						colspan="10" 
						style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
						><B>Dados do Solicitante</B></TR>';
			$sx .= '<TR><TD colspan=1 class="sz10" align="right">Solicitante:';
			$sx .= '<TD colspan=9 class="sz10">&nbsp'.$this->solicitante;	

			$sx .= '<TR><TD colspan=1 class="sz10" align="right">Telefone:';
			$sx .= '<TD colspan=2 class="sz10">&nbsp'.$this->solicitante_telefone;	

			$sx .= '<TD colspan=1 class="sz10" align="right">e-mail:';
			$sx .= '<TD colspan=6 class="sz10">&nbsp'.$this->solicitante_email;
			
			$sx .= '<TR><TD colspan=10 class="sp">';	

			/* Ordenador da Necessidade */
			$sx .= '<TR><TD class="sz10" 
						colspan="5" 
						style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
						><B>Ordenador da Necessidade:</B> ';
			$sx .= '<TD class="sz10" 
						colspan="5" 
						style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
						><B>Ordenador de Gasto:</B> ';
												
			$sx .= '<TR valign="top">
						<TD colspan=4 height="40" class="sz10" align="left">Nome: <B>'.$this->ordenador_necessidade.'</B><BR>'.$this->ordenador_necessidade_funcao;
			$sx .= '<TD class="sz10" colspan=1>&nbsp';	

			$sx .= '	<TD colspan=5 height="40" class="sz10" align="left">Nome: <B>'.$this->ordenador_gasto.'</B><BR>'.$this->ordenador_gasto_funcao;
			$sx .= '<TD class="sz10" colspan=5>&nbsp';	

			$sx .= '<TR valign="top">
						<TD colspan=1 height="20" class="sz10" align="right">Assinatura:';
			$sx .= '<TD class="bi" colspan=4>&nbsp';	

			$sx .= '	<TD colspan=1 height="20" class="sz10" align="right">Assinatura:';
			$sx .= '<TD class="bi" colspan=5>&nbsp';	

			$sx .= '<TR valign="top">
						<TD colspan=1 height="20" class="sz10" align="right">Data: ';
			$sx .= '<TD class="sz10" colspan=4>___/___/___';	

			$sx .= '	<TD colspan=1 height="20" class="sz10" align="right">Data: ';
			$sx .= '<TD class="sz10" colspan=4>___/___/___';
			
			$sx .= '<TR><TD colspan=10 class="sp">';	

			/* Deliberação */
			$sx .= '<TR><TD class="sz10" 
						colspan="10" 
						style="padding: 2px; border: 2px solid #000000; background-color: #C0C0C0;"
						><B>Deliberações</B>';
			$sx .= '<TR valign="top">
						<TD colspan=10 height="100" class="sz10 bi" >';

			/* Complementos */
			$sx .= '<TR><TD class="sz8" 
						colspan="2" 
						>Controle: <B>'.strzero($this->nr_ordem,5).'</B>';
			$sx .= '	<TD class="sz8"
						align="center" 
						colspan="2" 
						>Revisão 0.14.09 ';
			$sx .= '	<TD class="sz8"
						align="center" 
						colspan="2" 
						>'.$this->nr_log;						
			$sx .= '	<TD class="sz8"
						align="right" 
						colspan="4" 
						> '.date("d/m/Y H:i");

			$sx .= '</table>';
			return($sx);
		}	
	}
?>
