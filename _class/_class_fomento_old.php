<?
class fomento
	{
	var $id;
	var $agencia;
	var $edital;
	var $chamada;
	var $processo;
	var $vigencia_ini;
	var $vigencia_fim;
	var $posicao;
	var $valor_ano;
	var $valor_custeio;
	var $valor_capital;
	
	var $tabela = 'fomento';
	
	function mail_marketing()
		{
			$sx = '<table border=0 width=600 style="font-family: Arial, Tahoma; font-size: 13px;">';
			$sx .= '<TR><TD width=25% ><TD width=25% ><TD width=25% ><TD width=25% >';
			$sx .= '<TR><TD colspan=4>';
			$sx .= '<img src='.http.'img/logo_email_observatorio.png >';
			
			$sx .= '<TR><TD colspan=3 ><font style="font-size: 20px; color: #000000; " >';
			$sx .= 'UNESCO/Japan Young Researchers´ Fellowships Programme (UNESCO/Keizo Obuchi Research Fellowships Programme)';
			$sx .= '<TD width=25% >';
			$sx .= '<img src='.http.'fomento/img/logo_.jpg width=150 align=right >';
			$sx .= '<TR><TD colspan=4><B>OBJETIVO</B>';
			$sx .= '<BR>';
			$sx .= 'O programa UNESCO/ Keizo Obuchi Research Fellowships da Japanese Funds-in-Trust para a capacitação de recursos humanos tem a finalidade de desenvolver capacitações e atividades de pesquisa nas seguintes áreas:
					<BR><BR>Meio ambiente (com ênfase especial em Ciências das Águas);
					<BR>-Diálogo intercultural;
					<BR>-Tecnologias da Informação e Comunicação;
					<BR>-Resolução pacífica de conflitos
					
					<BR><font color="red">ATENÇÃO</font>
					<BR>A proposta deve estar em inglês ou francês.
					
					<BR><BR><a href="http://www.unesco.org/new/en/fellowships/programmes/unescokeizo-obuchi-japan-young-researchers-fellowships-programme-unescokeizo-obuchi-research-fellowships-programme/">Mais informações..</A>
					';
						
			$sx .= '<TR><TD><TD><TD bgcolor=#000000 colspan=2 style="padding: 10px;">';
			$sx .= '<font color=#FFFFFF style="font-size: 16px;" >';
			$sx .= 'Limite para submissão: <B>30 de agosto de 2013</B>';
			//$sx .= '<BR>Envio da documentação: <B>26/07/2013</B>';
			//$sx .= '<BR>Resultado: <B>26/07/2013</B>';
			
		
			$sx .= '<TR><TD colspan=4>';
			$sx .= 'TAGS: Pós-graduação. Mestrado. Doutorado.';	
			$sx .= '</table>';
			
			$email = 'monitoramento@sisdoc.com.br';
			enviaremail($email,'','Edital',$sx);
			$email = 'ana.kawajiri@grupomarista.org.br';
			enviaremail($email,'','Edital',$sx);
			$email = 'cip@pucpr.br';
			enviaremail($email,'','Edital',$sx);
			
			echo 'Enviado para '.$email;
			return($sx); 
		}
	
	function cp()
		{
				$cp = array();
				//$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN fm_inf_complementar text";
				//$rlt = db_query($sql);
				array_push($cp,array('$H8','id_fm','id',False,true));
				array_push($cp,array('$H5','fm_codigo','',False,true));
				array_push($cp,array('$S200','fm_nome',msg('chamada_nome'),True,true));
				array_push($cp,array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo = 1 order by agf_nome','fm_orgao',msg('financiador'),True,true));
				array_push($cp,array('$S20','fm_chamada',msg('chamada_nr'),True,true));
				array_push($cp,array('$[2008-'.date("Y").']','fm_chamada_ano',msg('chamada_ano'),True,true));
				array_push($cp,array('$S100','fm_site',msg('site'),True,true));
				array_push($cp,array('$T60:4','fm_descricao',msg('descricao'),True,true));
				
				array_push($cp,array('$D8','fm_dt_abertura',msg('ed_abertura'),True,true));
				array_push($cp,array('$D8','fm_dt_encerramento',msg('ed_encerramento'),True,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','fm_ativo',msg('ativo'),True,true));
				
				array_push($cp,array('$H8','fm_alvo',msg('ed_publico_alvo'),False,true));
				array_push($cp,array('${','',msg('ed_publico_alvo'),False,true));
				array_push($cp,array('$C1','fm_alvo_grad',msg('ed_publico_graduacao'),False,true));
				array_push($cp,array('$C1','fm_alvo_pos',msg('ed_publico_pos'),False,true));
				array_push($cp,array('$C1','fm_alvo_prof',msg('ed_publico_professores'),False,true));
				array_push($cp,array('$C1','fm_alvo_pesq',msg('ed_publico_pesquisadores'),False,true));
				array_push($cp,array('$C1','fm_alvo_todos',msg('ed_publico_todos'),False,true));
				array_push($cp,array('${','',msg('dados_adicionais'),False,true));
				array_push($cp,array('$Q pais_nome:pais_codigo:select * from ajax_pais order by pais_nome','fm_pais',msg('pais_origem'),False,true));
				array_push($cp,array('$S50','fm_proficiencia',msg('proficiencia'),False,true));
				array_push($cp,array('$T60:6','fm_prof_info',msg('prof_info'),False,true));
				array_push($cp,array('$D8','fm_prof_data',msg('prof_data_limite'),False,true));
				array_push($cp,array('$S50','fm_representante',msg('representante_pais'),False,true));
				array_push($cp,array('$T60:6','fm_inf_complementar',msg('info_complementar'),False,true));
				
				array_push($cp,array('$}','',msg('dados_adicionais'),False,true));
				array_push($cp,array('$}','',msg('ed_publico_alvo'),False,true));
				return($cp);			
			
		}
	function lista_inscritos($dd1='',$ddi=19000101,$ddf=29990101,$fmt=1)
		{
			$tabela = $this->tabela;
			$sql = "select * from $tabela 
					inner join csf_inscricoes on icsf_edital = fm_codigo
					left join pibic_aluno on icsf_estudante = pa_cracha
				where icsf_ativo = 1 and (icsf_data >= $ddi and icsf_data <= $ddf)";
			if (strlen($dd1) > 0) { $sql .= " and fm_codigo = '$dd1' "; }
			$sql .= " order by fm_descricao	";
			
			$rlt = db_query($sql);
			$ed = 'x';
			$sx = '<table width="100%" class="lt1">';
			$sh = '<TR><TH>Estudante<TH>Curso<TH>Cracha<TH>Dt.Inscrição<TH>ano<TH>Exame';
			if ($fmt==1) { $sx .= $sh; }
			if ($fmt==2) { $sx .= '<TR><TD>'; } /* Somente e-mail */
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
					$ln = $line;
					if ($fmt==1) { 
						$edital = $line['fm_codigo'];
						if ($edital != $ed)
							{
								$sx .= '<TR><TD colspan=6 class="lt1" align="left"><B>';
								$sx .= trim($line['fm_nome']).' - ';
								$sx .= trim($line['fm_descricao']).'</B>';
								$ed = $edital;
							}
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD align="left">'.trim($line['pa_nome']);
						$sx .= '<TD align="left">'.trim($line['pa_curso']);
						$sx .= '<TD>'.trim($line['icsf_estudante']);
						$sx .= '<TD>'.stodbr($line['icsf_data']);
						$sx .= '<TD>'.trim($line['fm_chamada']).'/'.trim($line['fm_chamada_ano']);
						$ss = trim($line['icsf_idioma_test']);
						if (strpos($ss,'(') > 0) { $ss = substr($ss,0,strpos($ss,'(')); }
						$sx .= '<TD>'.$ss;
					}
					if ($fmt==2) {
						$email = trim($line['pa_email']);
						if (strlen($email) > 0) { $sx .= $email.'; '; }
						$email = trim($line['pa_email_1']);
						if (strlen($email) > 0) { $sx .= $email.'; '; }
						echo '.';
					}
					
				}

			if ($fmt==1) {
				$sx .= '<TR><TD colspan=6><B>total de '.$tot.' inscrito(s).';
			}
			$sx .= '</table>';
			
			return($sx);
			
		}

	function edital_open()
		{
			$sql = "select * from ".$this->tabela." 
					left join agencia_de_fomento on agf_codigo = fm_orgao
					where fm_dt_encerramento >= ".date("Ymd");
			$rlt = db_query($sql);
			$sx = '<table width="96%" class="lt1" cellpadding=0 cellspacing=1>';
			while ($line = db_read($rlt))
			{
				$sx .= '<TR><TD align="left">';
				$sx .= trim($line['fm_nome']);
				$sx .= ' (Chamada ';
				$sx .= trim($line['fm_chamada']);
				$sx .= '/';
				$sx .= trim($line['fm_chamada_ano']);
				$sx .= ')';
				$sx .= ', ';
				$sx .= ' prazo até '.stodbr($line['fm_dt_encerramento']);
				$sx .= '<TR><TD bgcolor="#00000" height=1 >';
			}
			$sx .= '</table>';
			return($sx);
		}

	function form_edital_open($var='dd1')
		{
			$dda = trim($_GET[$var]).trim($_POST[$var]);
			
			$sql = "select * from ".$this->tabela." 
					left join agencia_de_fomento on agf_codigo = fm_orgao
					where fm_dt_encerramento >= ".date("Ymd");
			$rlt = db_query($sql);
	
			$sx = '';
			while ($line = db_read($rlt))
			{
				$checked = '';
				if ($dda == $line['fm_codigo']) { $checked = 'CHECKED'; }
				$sx .= '<input name="'.$var.'" type="radio" value="'.$line['fm_codigo'].'" '.$checked.'>';
				$sx .= trim($line['fm_nome']);
				$sx .= ' (Chamada ';
				$sx .= trim($line['fm_chamada']);
				$sx .= '/';
				$sx .= trim($line['fm_chamada_ano']);
				$sx .= ')';
				$sx .= ', ';
				$sx .= ' prazo até '.stodbr($line['fm_dt_encerramento']);
				$sx .= '<BR>';
			}			
			return($sx);
			
		}
		

	function le($id)
		{
			$sql = "select * from ".$this->tabela." 
					left join agencia_de_fomento on agf_codigo = fm_orgao
					where id_fm = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
			{
				$this->id = $line['id_fm'];
				$this->fm_codigo = $line['fm_codigo'];
				$this->fm_nome = $line['fm_nome'];
				$this->fm_orgao = $line['fm_orgao'];
				$this->fm_orgao_nome = $line['agf_nome'];
				$this->fm_chamada = $line['fm_chamada'];
				$this->fm_chamada_ano = $line['fm_chamada_ano'];
				$this->fm_site = $line['fm_site'];
				$this->fm_descricao = $line['fm_descricao'];
				$this->fm_dt_abertura = $line['fm_dt_abertura'];
				$this->fm_dt_encerramento = $line['fm_dt_encerramento'];
				$this->fm_ativo = $line['fm_ativo'];
				$this->fm_alvo = $line['fm_alvo'];
				$this->fm_alvo_grad = $line['fm_alvo_grad'];
				$this->fm_alvo_pos = $line['fm_alvo_pos'];
				$this->fm_alvo_prof = $line['fm_alvo_prof'];
				$this->fm_alvo_pesq = $line['fm_alvo_pesq'];
				$this->fm_alvo_todos = $line['fm_alvo_todos'];
			}
		}
	function mostra_dados()
		{
			$sx .= '<fieldset><legend>'.msg('edital_fld').'</legend>';
			$sx .= '<table width="100%">';

			$sx .= '<TR class="lt0"><TD colspan=2>'.msg('edital_agencia');
			$sx .= '<TD width="5%">ID:'.$this->fm_codigo;
			$sx .= '<TR class="lt2"><TD colspan=2>'.$this->fm_orgao_nome;

			$sx .= '<TR class="lt0"><TD colspan=2>'.msg('edital_nome');
			$sx .= '<TR class="lt2"><TD colspan=2>'.$this->fm_nome.' '.$this->fm_chamada.'/'.$this->fm_chamada_ano;

			$sx .= '<TR class="lt0"><TD>'.msg('edital_abertura');
			$sx .= '<TD>'.msg('edital_encerramento');

			$sx .= '<TR class="lt2">';
			$sx .= '<TD width="50%">'.stodbr($this->fm_dt_abertura);
			$sx .= '<TD width="50%">'.stodbr($this->fm_dt_encerramento).' <B>';
			if ($this->fm_dt_encerramento < date("Ymd")) { $sta = '<font color=red >'.msg('fm_closed').'</font>'; }
			if ($this->fm_dt_encerramento == date("Ymd")) { $sta = '<font color=orange >'.msg('fm_today').'</font>'; }
			if ($this->fm_dt_encerramento > date("Ymd")) { $sta = '<font color=green >'.msg('fm_open').'</font>'; }
			$sx .= $sta;

			/* Site do evento */
			$site = trim($this->fm_site);
			if (strlen($site) > 0) { $site = '<A HREF="'.$site.'" target="new">'.$site.'</A>'; }
			$sx .= '<TR class="lt0"><TD colspan=2>'.msg('edital_site');
			$sx .= '<TR class="lt2"><TD colspan=2>'.$site.'&nbsp;';

			$sx .= '</table>';
			$sx .= '</fieldset>';
			return($sx);
		}

	function row()
		{
			global $cdf,$cdm,$masc;
			$cdf = array('id_fm','fm_nome','fm_chamada','fm_chamada_ano');
			$cdm = array('cod',msg('nome'),msg('edital'),msg('ano'));
			$masc = array('','','','','','','');
			return(1);				
		}
		function updatex()
			{
				global $base;
				$c = 'fm';
				$c1 = 'id_' . $c;
				$c2 = $c . '_codigo';
				$c3 = 7;
				$sql = "update " . $this -> tabela . " set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base == 'pgsql') { $sql = "update " . $this -> tabela . "	 set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' "; }
				$rlt = db_query($sql);
			}
					
	function structure()
		{
			$sql = "DROP TABLE fomento";
			$rlt = db_query($sql);
			
			$sql = "CREATE TABLE fomento
				(
				id_fm serial NOT NULL,
				fm_codigo char(7),
				fm_orgao char(10),
				fm_chamada char(10),
				fm_chamada_ano char(4),
				fm_nome char (200),
				fm_descricao text,
				fm_dt_abertura int,
				fm_dt_encerramento int,
				fm_site char (100),
				fm_ativo int,
				fm_alvo char(21),
				fm_alvo_grad char(1),
				fm_alvo_pos char(1),
				fm_alvo_prof char(1),
				fm_alvo_pesq char(1),
				fm_alvo_todos char(1)
				)
				";
			$rlt = db_query($sql);
		}	
	}
?>
