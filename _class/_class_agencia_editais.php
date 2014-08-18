<?php
class agencia_editais {
	var $id;
	var $codigo;
	var $agencia;
	var $agencia_nome;
	var $agencia_sigla;
	var $docente;
	
	var $edital_nome;
	var $edital_nr;
	var $edital_ano;
	var $convenio;
	var $codigo_int;
	var $descricao;
	
	var $Link;
	
	var $tabela = 'agencia_editais';

	function cp() {
		$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN age_link char(100) ";
		//$rlt = db_query($sql);
		
		$cp = array();
		array_push($cp, array('$H8', 'id_age', 'id', False, true));
		array_push($cp, array('$H8', 'age_codigo', '', False, true));
		array_push($cp, array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo=1 order by agf_nome', 'age_agencia', msg('agencia_nome'), true, true));
		array_push($cp, array('$S100', 'age_nome', msg('edital_nome'), true, true));
		array_push($cp, array('$S20', 'age_edital_nr', msg('edital_numero'), False, true));
		array_push($cp, array('$[2000-' . date("Y") . ']', 'age_ano', msg('edital_ano'), False, true));

		array_push($cp, array('$S20', 'age_convenio', msg('nr_convênio'), False, true));
		array_push($cp, array('$S20', 'age_cod_interno', msg('cod_interno'), False, true));
		array_push($cp, array('$T60:6', 'age_descricao', msg('descricao'), False, true));
		array_push($cp, array('$D8', 'age_data_ini', msg('start_date'), False, true));
		array_push($cp, array('$D8', 'age_data_fim', msg('deadline'), False, true));
		array_push($cp, array('$D8', 'age_data_pro', msg('prorrogado'), False, true));
		array_push($cp, array('$N8', 'age_faixa', msg('Faixa_vlr_ate'), False, true));
		array_push($cp, array('$H8', 'age_sigla','', False, true));
		
		array_push($cp, array('$S100', 'age_link', msg('edital_link'), False, true));
		
		array_push($cp,array('$O 1:SIM&0:NÃO','age_ativo',msg('ativo'),true,true));
		return ($cp);
	}

	function row() {
		global $cdf, $cdm, $masc;
		$cdf = array('id_age', 'age_nome', 'age_edital_nr', 'age_ano','age_convenio', 'age_ativo', 'age_codigo');
		$cdm = array('cod', msg('edital'), msg('edital'), msg('ano'),msg('convencio'), msg('ativo'), msg('codigo'));
		$masc = array('', '', '','','','','','','','SN');
		return (1);
	}
	
	function dados_exportar()
		{
			$sql = "select * from ".$this->tabela."_captacao ";
			$sql .= " inner join docentes on agc_professor = pp_cracha ";
			$sql .= " inner join ".$this->tabela." on agc_edital_codigo = age_codigo ";
			$sql .= " inner join agencia_de_fomento on age_agencia = agf_codigo ";
//			$sql .= " where agc_edital_codigo = '".$this->codigo."' ";
			$rlt = db_query($sql);
			$sx .= '<fieldset><legend>'.msg('editais_fomento').'</legend>';
			$sx .= '<table width="100%" cellpadding=4 cellspacinf=0 class="lt1">';
			$sx .= '<TR bgcolor="#C0C0C0" align="center">';
			$sx .= '<TH>Inicial';
			$sx .= '<TH>Final';
			$sx .= '<TH align="center">duração';
			
			$sx .= '<TH>Capital';
			$sx .= '<TH>Custeio';
			$sx .= '<TH>Bolsas';
			$sx .= '<TH>Total';
			
			$sx .= '<TH>Capital';
			$sx .= '<TH>Custeio';
			$sx .= '<TH>Bolsas';
			$sx .= '<TH>Total';

			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD>'.$line['pp_cracha'];
					$sx .= '<TD>'.$line['pp_tipo'];
					$sx .= '<TD>'.$line['agc_posicao'];
					$sx .= '<TD>'.trim($line['age_nome']).' Edital '.trim($line['age_edital_nr']).'/'.$line['age_ano'];
					$sx .= '<TD>'.$line['agc_ativo'];
					$sx .= '<TD>'.$line['age_ano'];
					$sx .= '<TD>'.$line['agc_vigencia_ini_ano'];
					$sx .= '<TD>'.$line['agc_vigencia_ini_mes'];
					$sx .= '<TD>'.$line['agc_vigencia_ini_ano'];
					$sx .= '<TD>'.$line['agc_vigencia_fim_mes'];
					$sx .= '<TD>'.$line['agc_vigencia_fim_ano'];
					$sx .= '<TD>'.round($line['agc_valor_total']);
					$sx .= '<TD>'.round($line['agc_valor_capital']);
					$sx .= '<TD>'.round($line['agc_valor_custeio']);
					$sx .= '<TD>'.round($line['agc_valor_bolsa']);
					$sx .= '<TD>'.round($line['agc_valor_outros']);
					$sx .= '<TD>'.$line['agc_convenio'];
					$sx .= '<TD>'.round($line['agc_valor_instituicao']);
					$sx .= '<TD>'.$line['agc_proponente'];
					
					
					$ln = $line;	
				}
				print_r($ln);
			$sx .= '</table>';
			$sx .= '</fieldset>';
			return($sx);			
		}
	
	function mostra_demandas()
		{
			$sql = "select * from ".$this->tabela."_captacao ";
			$sql .= " inner join docentes on agc_professor = pp_cracha ";
			$sql .= " where agc_edital_codigo = '".$this->codigo."' ";
			$rlt = db_query($sql);
			$sx .= '<fieldset><legend>'.msg('editais_fomento').'</legend>';
			$sx .= '<table width="100%" cellpadding=4 cellspacinf=0 class="lt1">';
			$sx .= '<TR bgcolor="#C0C0C0" align="center"><TH rowspan=2>Pesquisador';
			$sx .= '<TH colspan=3>Vigência';
			
			$sx .= '<TH rowspan=2 align="center">Tipo';
			
			$sx .= '<TH colspan=4>Valor proposto';
			$sx .= '<TH colspan=4>Valor aprovado';
			
			$sx .= '<TH rowspan=2 align="center"><I>Status</I>';
			
			$sx .= '<TR bgcolor="#C0C0C0" align="center">';
			$sx .= '<TH>Inicial';
			$sx .= '<TH>Final';
			$sx .= '<TH align="center">duração';
			
			$sx .= '<TH>Capital';
			$sx .= '<TH>Custeio';
			$sx .= '<TH>Bolsas';
			$sx .= '<TH>Total';
			
			$sx .= '<TH>Capital';
			$sx .= '<TH>Custeio';
			$sx .= '<TH>Bolsas';
			$sx .= '<TH>Total';

			while ($line = db_read($rlt))
				{
					$sx .= $this->mostra_edital_professor($line,'1');		
				}
			$sx .= '</table>';
			$sx .= '</fieldset>';
			return($sx);
			}

	function mostra_edital_professor_2($line,$tipo)
		{
			$status = $this->status();
			$tempo = $this->tempo();
			
			$cor = '#202020';
			$fcor = '<font color="'.$cor.'">';
			
					$link = '<A HREF="javascript:newxy2(\'agencia_editais_fomentos_popup.php?dd0='.$line['id_agc'].'&dd90='.checkpost($line['id_agc']).'\',700,600);" >';
					if ($line['agc_ativo']==11) { $link .= 'validar</A>'; }
					if ($line['agc_ativo']==1) { $link .= 'editar</A>'; }
					if ($line['agc_ativo']==9) { $link .= 'reativar</A>'; }

					$pos = $line['agc_posicao'];
					$vini = strzero($line['agc_vigencia_ini_mes'],2).'/'.$line['agc_vigencia_ini_ano'];
					$vfim = strzero($line['agc_vigencia_fim_mes'],2).'/'.$line['agc_vigencia_fim_ano'];
					$vtempo = ($line['agc_vigencia_fim_ano'] - $line['agc_vigencia_ini_ano'])*12;
					$vmes = $line['agc_vigencia_fim_mes'] - $line['agc_vigencia_ini_mes'];
					if ($vmes < 1) { $vmes = $vmes+12; }
					$vtempo = $vtempo + $vmes +1;
					$mtempo = $tempo[$vtempo];
					if (strlen($mtempo)==0) { $mtempo = $vtempo. ' meses'; }
					
					if ($pos=='C') { $pos='Coordenador'; } else { $pos = 'Colaborador'; }
					$sx .= '<TR>';
					if ($tipo=='1')
						{
						$sx .= '<TD>'.$fcor.trim($line['pp_nome']);
						$sx .= ' ('.$line['agc_professor'].')';
						}
					if ($tipo=='2')
						{
							$sx .= '<TD>'.$fcor;
							$sx .= trim($line['agf_sigla']);
							$sx .= '<TD>'.$fcor;
							$sx .= trim($line['age_nome']);
						}
					$sx .= '<TD align="center">'.$fcor.$vini;
					$sx .= '<TD align="center">'.$fcor.$mtempo;
					
					$sx .= '<TD>'.trim($pos);
										
					$sx .= '<TD align="right"><B>'.number_format($line['agc_valor_total'],2);
					
					$sx .= '<TD align="center"><nobr>';
					$sx .= $status[$line['agc_ativo']];
					
					$sx .= '<TD align="center">'.$link;	
					return($sx);			
		}


	function mostra_edital_professor($line,$tipo)
		{
			$status = $this->status();
			$tempo = $this->tempo();
					$link = '<A HREF="javascript:newxy2(\'agencia_editais_fomentos_popup.php?dd0='.$line['id_agc'].'&dd90='.checkpost($line['id_agc']).'\',700,600);" >editar</A>';
					$pos = $line['agc_posicao'];
					$vini = strzero($line['agc_vigencia_ini_mes'],2).'/'.$line['agc_vigencia_ini_ano'];
					$vfim = strzero($line['agc_vigencia_fim_mes'],2).'/'.$line['agc_vigencia_fim_ano'];
					$vtempo = ($line['agc_vigencia_fim_ano'] - $line['agc_vigencia_ini_ano'])*12;
					$vmes = $line['agc_vigencia_fim_mes'] - $line['agc_vigencia_ini_mes'];
					if ($vmes < 1) { $vmes = $vmes+12; }
					$vtempo = $vtempo + $vmes +1;
					$mtempo = $tempo[$vtempo];
					if (strlen($mtempo)==0) { $mtempo = $vtempo. ' meses'; }
					
					if ($pos=='C') { $pos='Coordenador'; } else { $pos = 'Colaborador'; }
					$sx .= '<TR>';
					if ($tipo=='1')
						{
						$sx .= '<TD>'.trim($line['pp_nome']);
						$sx .= ' ('.$line['agc_professor'].')';
						}
					if ($tipo=='2')
						{
							$sx .= '<TD>';
							$sx .= trim($line['agf_sigla']);
							$sx .= '<TD>';
							$sx .= trim($line['age_nome']);
						}
					
					$sx .= '<TD align="center">'.$vini;
					$sx .= '<TD align="center">'.$vfim;
					$sx .= '<TD align="center">'.$mtempo;
					
					$sx .= '<TD>'.trim($pos);
										
					$sx .= '<TD align="right">'.number_format($line['agc_valor_capital_pp'],2);
					$sx .= '<TD align="right">'.number_format($line['agc_valor_custeio_pp'],2);
					$sx .= '<TD align="right">'.number_format($line['agc_valor_bolsa_pp'],2);
					$sx .= '<TD align="right">'.number_format($line['agc_valor_total_pp'],2);
					
					$sx .= '<TD align="right">'.number_format($line['agc_valor_capital'],2);
					$sx .= '<TD align="right">'.number_format($line['agc_valor_custeio'],2);
					$sx .= '<TD align="right">'.number_format($line['agc_valor_bolsa'],2);
					$sx .= '<TD align="right"><B>'.number_format($line['agc_valor_total'],2);
					
					$sx .= '<TD align="center">';
					$sx .= $status[$line['agc_ativo']].' ('.$line['agc_ativo'].')';
					
					$sx .= '<TD align="center">'.$link;	
					return($sx);			
		}

	function tempo()
		{
			$tp = array(1=>'um mês',2=>'dois meses',3=>'três meses',4=>'quatro meses',5=>'cinco meses',6=>'seis meses',
				12=>'um ano', 18=>'um ano e meio', 24=>'dois anos', 36=>'três anos', 48=>'quatro anos');
				
			return($tp);
		}
	function status()
		{
			$ax = array(1=>'Validado',11=>'Validação',0=>'Não aprovado',2=>'Submetido',3=>'Em homologação',9=>'Cancelado');
			return($ax);
		}

	function mostra_edital_email($id='')
		{
			global $site;
			$site = '../';
			$this->le($id);
			$font0 = '<font style="font-family: Verdana, Arial, Tahoma, Serif; font-size:10px;">';		
			$font1 = '<font style="font-family: Verdana, Arial, Tahoma, Serif; font-size:12px;">';
			$font2 = '<font style="font-family: Verdana, Arial, Tahoma, Serif; font-size:14px;">';
			$sx .= '<fieldset><legend>'.msg('editais_fomento').'</legend>';
			$sx .= '<table width="100%" cellpadding=0 cellspacing=0 border=0 >';
			$sx .= '<TR>';
			$sx .= '<TD width=10%>&nbsp;';
			$sx .= '<TD width=10%>&nbsp;';
			$sx .= '<TD width=10%>&nbsp;';
			$sx .= '<TD width=70%>&nbsp;';
			
			$sx .= '<TR>';
			$sx .= '<TD>'.'<NOBR>'.$font0.msg('agencia_nome');
			$sx .= '<TD colspan=3 >'.$font0.msg('edital_nome').' / '.msg('edital_nr');
			
			$sx .= '<TR class="lt2" valign="top">';
			$sx .= '<TD><img src="http://www2.pucpr.br/reol/img/logo_'.LowerCaseSql(trim($this->agencia_sigla)).'.png">';
			$sx .= '<TD colspan=3><B>'.$font2.'<I>'.$this->agencia_nome.'</I>';
			$sx .= '<BR>'.$this->edital_nome;
			$sx .= '<BR><B>'.$font1.'<NOBR>'.'Edital Nr.: '.trim($this->edital_nr).'/'.$this->edital_ano;
			
			$sx .= '<TR class=lt0 >';
			$sx .= '<TD colspan=2>'.'<NOBR>'.$font0.msg('period_inscricao');
			$sx .= '<TD>'.'<NOBR>'.$font0.msg('Faixa_vlr_ate');
			$sx .= '<TD>&nbsp;';
			$sx .= '<TR valign="top">';
			$sx .= '<TD colspan=2><NOBR><B>'.$font1.'<NOBR>'.stodbr($this->dt_abertura).'</B>';
			$sx .= ' até <font color="red"><B>'.$font1.stodbr($this->dt_encerra).'</font>';
			$sx .= '<TD><B>'.'<NOBR>'.$font1.number_format($this->vlr_limite,2,',','.');				
			$sx .= '<TD>&nbsp;';

			$sx .= '<TR>';
			$sx .= '<TD colspan=4>'.'<NOBR>'.$font0.msg('edital_link');
			$sx .= '<TR class="lt1">';
			$sx .= '<TD colspan=4>'.$font1.'<A HREF="'.$this->link.'" target="new">'.'<NOBR>'.$this->link.'</A>';
			$sx .= '<BR>&nbsp;';

			$sx .= '<TR>';
			$sx .= '<TD colspan=4>'.$font0.msg('edital_descricao');
			$sx .= '<TR>';
			$sx .= '<TD colspan=4>'.$font1.$this->descricao;
			
			$sx .= '</table>';
			$sx .= '</fieldset>';
			return($sx);
		}
	
	
	function mostra_edital_detalhe($id='')
		{
			$this->le($id);			
			$sx .= '<fieldset><legend>'.msg('editais_fomento').'</legend>';
			$sx .= '<table width="100%" cellpadding=0 cellspacing=0>';
			$sx .= '<TR class="lt0">';
			$sx .= '<TD colspan=2>'.msg('agencia_nome');
			$sx .= '<TD>'.msg("agencia_sigla");
			
			$sx .= '<TR class="lt2"><TD colspan=2><B>'.$this->agencia_nome;
			$sx .= '<TD><B>'.$this->agencia_sigla;


			$sx .= '<TR class="lt0">';
			$sx .= '<TD>'.msg('edital_nome');
			$sx .= '<TR class="lt2">';
			$sx .= '<TD colspan=3><B>'.$this->edital_nome;

			
			$sx .= '<TR class="lt0">';
			$sx .= '<TD>'.msg('edital_nr');
			$sx .= '<TD>'.msg('convenio_nr');
			$sx .= '<TD>'.msg('codigo_interno');
			
			$sx .= '<TR class="lt2">';
			$sx .= '<TD><B>'.$this->edital_nr.'/'.$this->edital_ano;
			$sx .= '<TD><B>'.$this->convenio;
			$sx .= '<TD><B>'.$this->codigo_int;
			
			$sx .= '<TR class="lt0">';
			$sx .= '<TD>'.msg('start_date');
			$sx .= '<TD>'.msg('deadline');
			$sx .= '<TD>'.msg('prorrogado');
			$sx .= '<TD>'.msg('Faixa_vlr_ate');
			
			$sx .= '<TR class="lt2">';
			$sx .= '<TD><B>'.stodbr($this->dt_abertura);
			$sx .= '<TD><B>'.stodbr($this->dt_encerra);
			$sx .= '<TD><B>'.stodbr($this->dt_prorrogacao);
			$sx .= '<TD><B>'.number_format($this->vlr_limite,2,',','.');				
			
			$sx .= '<TR class="lt0">';
			$sx .= '<TD>'.msg('edital_descricao');
			$sx .= '<TR class="lt1">';
			$sx .= '<TD colspan=3>'.$this->descricao;
			
			$sx .= '</table>';
			$sx .= '</fieldset>';
			return($sx);
		}

	function le($id)
		{
			global $messa;
			if (strlen($id) > 0) { $this->id = $id; }
			$sql = "select * from ".$this->tabela." ";
			$sql .= " inner join agencia_de_fomento on age_agencia = agf_codigo ";
			$sql .= " where id_age = ".round($this->id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->codigo = $line['age_codigo'];
					$this->agencia = $line['age_agencia'];
					$this->agencia_nome = $line['agf_nome'];
					$this->agencia_sigla = $line['agf_sigla'];
					
					$this->edital_nome = $line['age_nome'];
					$this->edital_nr = $line['age_edital_nr'];
					$this->edital_ano = $line['age_ano'];
					$this->convenio = $line['age_convenio'];
					$this->codigo_int = $line['[age_cod_interno'];
					$this->descricao = $line['age_descricao'];
					
					$this->dt_abertura = $line['age_data_ini'];
					$this->dt_encerra = $line['age_data_fim'];
					$this->dt_prorrogacao = $line['age_data_pro'];
					$this->vlr_limite = $line['age_faixa'];
					
					$this->link = $line['age_link'];
				}
			return(True);
			
		}
	function mostra_editais()
		{
			global $tab_max;
			$sql = "select * from ".$this->tabela." ";
			$sql .= " inner join (select sum(agc_valor_capital) as valor_captial, 
							sum(agc_valor_custeio) as valor_custeio,
							sum(agc_valor_bolsa) as valor_bolsas, 
							sum(agc_valor_outros) as valor_outros, 
							agc_edital_codigo
							from ".$this->tabela."_captacao
							group by agc_edital_codigo ) as tabela on agc_edital_codigo = age_codigo ";
			//$sql .=  " where age_agencia = '".$this->agencia."' ";
			$sql .=  " order by age_ano desc, age_edital_nr desc ";
			$rlt = db_query($sql);
			
			$sx .= '<table class="lt1" width="'.$tab_max.'">';
			$sx .= '<TR><TH>Edital<TH>Convenio<TH>Cod.Interno<TH>Modalidade<TH>Capital<TH>Custeio<TH>Bolsas<TH>Outros';
			$t1 = 0;
			$t2 = 0;
			$t3 = 0;
			$t4 = 0;
			while ($line = db_read($rlt))
				{
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>';
					$sx .= trim($line['age_edital_nr']).'/'.trim($line['age_ano']);
					$sx .= '<TD>';
					$sx .= trim($line['age_convenio']);
					$sx .= '<TD>';
					$sx .= $line['age_cod_interno'];
					$sx .= '<TD>';
					$sx .= trim($line['age_nome']);
					$sx .= '<TD align="right">';
					$sx .= number_format($line['valor_captial'],2);
					$sx .= '<TD align="right">';
					$sx .= number_format($line['valor_custeio'],2);
					$sx .= '<TD align="right">';
					$sx .= number_format($line['valor_bolsas'],2);
					$sx .= '<TD align="right">';
					$sx .= number_format($line['valor_outros'],2);
					$t1 = $t1 + $line['valor_captial'];
					$t2 = $t2 + $line['valor_custeio'];
					$t3 = $t3 + $line['valor_bolsas'];
					$t4 = $t4 + $line['valor_outros'];
				}
			$sx .= '<TR><TD colspan=4>';
			$sx .= '<TD align="right">'.number_format($t1,2);
			$sx .= '<TD align="right">'.number_format($t2,2);
			$sx .= '<TD align="right">'.number_format($t3,2);
			$sx .= '<TD align="right">'.number_format($t4,2);
			$sx .= '</table>';
			return($sx);
		}

	function structure() {
		$sql = "CREATE TABLE " . $this -> tabela . " (
					id_age SERIAL NOT NULL ,
					age_codigo CHAR( 7 ) NOT NULL ,
					age_agencia char ( 5 ),
					age_nome CHAR( 100 ) NOT NULL ,
					age_sigla CHAR( 20 ) NOT NULL ,
					age_valor_total float,
					age_ativo INT NOT NULL ,
					age_edital_nr CHAR( 20 ) ,
					age_ano CHAR( 5 ) ,
					age_convenio char ( 20 ),
					age_cod_interno CHAR( 15 ) NOT NULL ,
					age_data_ini integer,
					age_data_fim integer,
					age_data_pro integer,
					age_faixa float,
					age_link char(100),
					age_descricao TEXT
					);";
		$rlt = db_query($sql);

		$sql = "CREATE TABLE " . $this -> tabela . "_captacao
					(
					id_agc SERIAL NOT NULL ,
					agc_codigo CHAR( 7 ) NOT NULL ,
					agc_edital_codigo char ( 7 ),
					agc_professor CHAR( 8 ) NOT NULL ,
					agc_posicao CHAR( 1 ) NOT NULL ,
					agc_valor_total float,
					agc_valor_capital float,
					agc_valor_custeio float,
					agc_valor_bolsa float,
					agc_valor_outros float,
					agc_ativo INT NOT NULL ,
					agc_vigencia_mes integer,
					agc_vigencia_ini_ano integer,
					agc_vigencia_ini_mes integer,
					agc_vigencia_fim_ano integer,
					agc_vigencia_fim_mes integer,
					agc_convenio char ( 20 ),
					agc_cod_interno CHAR( 15 ) NOT NULL ,
					agc_descricao TEXT
					)";
		$rlt = db_query($sql);
	}
	
	function mostra_docente_captacao($tp='')
		{
			$sql = "select * from ".$this->tabela."_captacao ";
			$sql .= " inner join ".$this->tabela." on agc_edital_codigo = age_codigo ";
			$sql .= " inner join agencia_de_fomento on agf_codigo = age_agencia ";
			$sql .= " where agc_professor = '".$this->docente."' ";
			$sql .= " order by agc_vigencia_ini_ano desc ";
			$rlt = db_query($sql);
			$sx .= '<fieldset><legend>Captação</legend>';
			$sx .= '<table width="100%" cellpadding=3 cellspacing=0 class="lt1" border=1>';
			if ($tp=='row')
				{ $sx .= '<TH width=5%>Ag./Emp.<TH width="60%">Tipo edital<TH width="5%">Edital<TH>Vigência<TH>Duração<TH>Participação<TH><I>Status<TH>ação'; } 
				else 
				{ $sx .= '<TH>Orgão fomento/Empresa<TH>Tipo edital<TH>Edital<TH>Vigência<TH>Até<TH>Valor total'; }
			$tot = 0;
			$tot1 = 0;
			$xano = 1900;
			while ($line = db_read($rlt))
			{
				$tot1++;
				$tot = $tot + $line['agc_valor_total'];
				$pos = $line['agc_posicao'];
				$sta = $line['agc_ativo'];
				$ano = $line['agc_vigencia_ini_ano'];
				
				if ($xano != $ano)
					{
						$sx .= '<TR><TD class="lt3" colspan=8><B>'.$ano;
						$xano = $ano;
					}
									
				if ($tp=='row')
					{
						$sx .= $this->mostra_edital_professor_2($line,'2');							
					} else {
						$sx .= $this->mostra_edital_professor($line,'2');
					}
			}

			$sx .= '<TR><TD align=right colspan=5><B>Total de projetos '.$tot1;
			$sx .= '<TD align="right"><B>'.number_format($tot,2);
			$sx .= '</table>';
			$sx .= '</fieldset>';
			return($sx);
		}

	function cp_captacao_pesquisador()
		{
			$cp = array();
			array_push($cp,array('$H8','id_agc','',False,True));
			array_push($cp,array('$H8','agc_codigo','',False,True));
//			array_push($cp,array('$Q age_descricao:age_codigo:select age_nome || \'-\' || age_edital_nr || \'-\' || age_ano as age_descricao, age_codigo from '.$this->tabela.' where age_ativo = 1 order by age_ano desc, age_edital_nr desc','agc_edital_codigo','Edital',True,True));
			array_push($cp,array('$O : &C:Coordenador&O:Colaborador','agc_posicao','Posição',True,True));
			
			array_push($cp,array('${','','Valor Aprovado',False,True));
			array_push($cp,array('$N8','agc_valor_total','Valor Total',True,True));
			array_push($cp,array('$N8','agc_valor_capital','Valor Capital',True,True));
			array_push($cp,array('$N8','agc_valor_custeio','Valor Custeio',True,True));
			array_push($cp,array('$N8','agc_valor_bolsa','Valor Bolsa',True,True));
			array_push($cp,array('$N8','agc_valor_outros','Valor Outros',True,True));
			array_push($cp,array('$Q inst_nome:inst_codigo:select inst_codigo, substring(trim(inst_nome) from 1 for 50) || chr(47) || trim(inst_abreviatura) as inst_nome from instituicao where inst_abreviatura <> \'\' order by inst_nome','agc_proponente','Instituição proponente',True,True));
			array_push($cp,array('$N8','agc_valor_instituicao','Valor aplicado na Instituição (PUCPR)',False,True));		
			array_push($cp,array('$}','','Valor Aprovado',False,True));
			
//			array_push($cp,array('$O 1:Aprovado e validado&11:Para validação&0:NÂO&2:Submetido&3:Em homologação&8:Não aprovado&9:Cancelado','agc_ativo','Ativo',False,True));
			array_push($cp,array('${','','Vigência',False,True));
			array_push($cp,array('$HV','agc_ativo','1',False,True));
			array_push($cp,array('$[1-12]','agc_vigencia_ini_mes','Mês (Inicial)',False,True));
			array_push($cp,array('$[2000-'.(date("Y")+20).']','agc_vigencia_ini_ano','Ano (Inicial)',False,True));
			array_push($cp,array('$[1-12]','agc_vigencia_fim_mes','Mês (Final)',False,True));
			array_push($cp,array('$[2000-'.(date("Y")+20).']','agc_vigencia_fim_ano','Ano (Final)',False,True));
			array_push($cp,array('$}','','Vigência',False,True));
			
			array_push($cp,array('${','','Nº convênio/processo',False,True));
			array_push($cp,array('$S20','agc_convenio','Nº convênio/processo',False,True));
			array_push($cp,array('$C1','agc_convenio_na','Não se aplica',False,True));
			array_push($cp,array('$}','','Nº convênio/processo',False,True));
			
			array_push($cp,array('$T60:5','agc_descricao','Descrição do projeto/captação',False,True));
			array_push($cp,array('$H8','agc_cod_interno','',False,True));
			
			array_push($cp,array('${','','Aplicação do valores captados',False,True));
			array_push($cp,array('$M','',msg('fom_aplicacao'),False,True));
			array_push($cp,array('$C8','agc_graduacao','na Graduação',False,True));
			array_push($cp,array('$C8','agc_stricto','na Pós-Graduação (Stricto sensu)',False,True));
			array_push($cp,array('$C8','agc_lato','na Pós-Graduação (Lato sensu)',False,True));
			array_push($cp,array('$}','','',False,True));
			return($cp);
			
		}
	function cp_captacao()
		{
			$sql = "ALTER TABLE ".$this->tabela.'_captacao ADD COLUMN agc_valor_instituicao float ';
			$sql = "ALTER TABLE ".$this->tabela.'_captacao ADD COLUMN agc_lato char(1) ';
			//$sql = "ALTER TABLE ".$this->tabela.'_captacao ADD COLUMN agc_graduacao int8 ';
			//$rlt = db_query($sql);
			
			$cp = array();
			array_push($cp,array('$H8','id_agc','',False,True));
			array_push($cp,array('$H8','agc_codigo','',False,True));
			array_push($cp,array('$Q age_descricao:age_codigo:select age_nome || \'-\' || age_edital_nr || \'-\' || age_ano as age_descricao, age_codigo from '.$this->tabela.' where age_ativo = 1 order by age_ano desc, age_edital_nr desc','agc_edital_codigo','Edital',True,True));
			array_push($cp,array('$S8','agc_professor','Cod.Professor',True,True));
			array_push($cp,array('$O : &C:Coordenador&O:Colaborador','agc_posicao','Posição',True,True));
			
			array_push($cp,array('${','','Valor Proposto',False,True));
			array_push($cp,array('$N8','agc_valor_total_pp','Valor Total',True,True));
			array_push($cp,array('$N8','agc_valor_capital_pp','Valor Capital',True,True));
			array_push($cp,array('$N8','agc_valor_custeio_pp','Valor Custeio',True,True));
			array_push($cp,array('$N8','agc_valor_bolsa_pp','Valor Bolsa',True,True));
			array_push($cp,array('$N8','agc_valor_outros_pp','Valor Outros',True,True));
			array_push($cp,array('$}','','Valor Proposto',False,True));
			
			array_push($cp,array('${','','Valor Aprovado',False,True));
			array_push($cp,array('$N8','agc_valor_total','Valor Total',True,True));
			array_push($cp,array('$N8','agc_valor_capital','Valor Capital',True,True));
			array_push($cp,array('$N8','agc_valor_custeio','Valor Custeio',True,True));
			array_push($cp,array('$N8','agc_valor_bolsa','Valor Bolsa',True,True));
			array_push($cp,array('$N8','agc_valor_outros','Valor Outros',True,True));
			array_push($cp,array('$Q inst_nome:inst_codigo:select * from instituicao order by inst_nome','agc_proponente','Instituição proponente',True,True));
			array_push($cp,array('$N8','agc_valor_instituicao','Valor aplicado na Instituição (PUCPR)',False,True));		
			array_push($cp,array('$}','','Valor Aprovado',False,True));
			
			array_push($cp,array('$O 1:Aprovado e validado&11:Para validação&0:NÂO&2:Submetido&3:Em homologação&8:Não aprovado&9:Cancelado','agc_ativo','Ativo',False,True));
			array_push($cp,array('$[1-12]','agc_vigencia_ini_mes','Mês (Inicial)',False,True));
			array_push($cp,array('$[2000-'.(date("Y")+20).']','agc_vigencia_ini_ano','Ano (Inicial)',False,True));
			array_push($cp,array('$[1-12]','agc_vigencia_fim_mes','Mês (Final)',False,True));
			array_push($cp,array('$[2000-'.(date("Y")+20).']','agc_vigencia_fim_ano','Ano (Final)',False,True));

			array_push($cp,array('${','','Nº convênio/processo',False,True));
			array_push($cp,array('$S20','agc_convenio','Nº convênio/processo',False,True));
			array_push($cp,array('$C1','agc_convenio_na','Não se aplica',False,True));
			array_push($cp,array('$}','','Nº convênio/processo',False,True));
			
			array_push($cp,array('$T60:5','agc_descricao','Descrição',False,True));
			array_push($cp,array('$H8','agc_cod_interno','',False,True));
			
			array_push($cp,array('$D8','agc_data_assinatura','Data validação do Gestor',False,True));
			array_push($cp,array('$D8','agc_data_envio','Data no núcleo',False,True));
			array_push($cp,array('$D8','agc_data_resultado','Data prevista da aprovação',False,True));
			array_push($cp,array('$C8','agc_graduacao','Graduação',False,True));
			array_push($cp,array('$C8','agc_stricto','Pós-Graduação (Stricto sensu)',False,True));
			array_push($cp,array('$C8','agc_lato','Pós-Graduação (Lato sensu)',False,True));
			return($cp);
		}

	function row_demanda() {
		global $cdf, $cdm, $masc;
		$cdf = array('id_agc', 'agc_edital_codigo', 'agc_descricao', 'agc_vigencia_ini_mes','agc_valor_total', 'agc_professor', 'agc_codigo');
		$cdm = array('cod', msg('edital'), msg('edital'), msg('ano'),msg('convencio'), msg('ativo'), msg('codigo'));
		$masc = array('', '', '','','','','','','','SN');
		return (1);
	}
	
	function updatex() {
		global $base;
		$c = 'age';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update " . $this -> tabela . " set $c2 = lpad($c1,$c3,0) where $c2='' ";
		if ($base == 'pgsql') { $sql = "update " . $this -> tabela . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' ";
		}
		$rlt = db_query($sql);

		$c = 'agc';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update " . $this -> tabela . "_captacao set $c2 = lpad($c1,$c3,0) where $c2='' ";
		if ($base == 'pgsql') { $sql = "update " . $this -> tabela . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' ";
		}
		//$rlt = db_query($sql);
	}

}
