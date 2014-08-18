<?php
class parecer_model
	{
		var $form;
		var $tabela = 'parecer_model';
		var $tabela_q = 'parecer_model_question';
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_pm','id',False,true));
				array_push($cp,array('$H5','pm_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S100','pm_nome',msg('nome'),true,true));
				array_push($cp,array('$H8','pm_descricao','',false,true));
				array_push($cp,array('$S1','pm_journal_id',msg('own'),False,true));
				array_push($cp,array('$T80:6','pm_instrucoes',msg('instrucoes_avaliador'),False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','pm_ativo',msg('ativo'),true,true));
	
				return($cp);
			}
		function cp_subfield()
			{
				$cp = array();
				array_push($cp,array('$H8','id_sf','id',False,true));
				array_push($cp,array('$H5','sf_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S100','sf_nome',msg('nome'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','sf_ativo',msg('ativo'),true,true));
	
				return($cp);
			}					
		function cp_question()
			{
				global $tabela;
				$cp = array();
				array_push($cp,array('$H8','id_pq','id',False,true));
				array_push($cp,array('$H5','pq_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S3','pq_tipo',msg('tipo'),true,true));
				array_push($cp,array('$Q sf_nome:sf_codigo:select * from '.$this->tabela.'_subfields where sf_ativo = 1 order by sf_nome ','pq_area',msg('area_tema'),true,true));
				array_push($cp,array('$T50:4','pq_pergunta',msg('question'),true,true));
				array_push($cp,array('$T50:4','pq_resposta_1',msg('answer_1'),False,true));
				array_push($cp,array('$T50:4','pq_resposta_2',msg('answer_2'),False,true));
				array_push($cp,array('$T50:4','pq_resposta_3',msg('answer_3'),False,true));
				array_push($cp,array('$T50:4','pq_resposta_4',msg('answer_4'),False,true));
				array_push($cp,array('$T50:4','pq_resposta_5',msg('answer_5'),False,true));
				array_push($cp,array('$T50:4','pq_resposta_6',msg('answer_6'),False,true));				
				array_push($cp,array('$S1','pq_own',msg('own'),true,true));
				array_push($cp,array('$T80:6','pq_instrucoes',msg('instrucoes_avaliador'),False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','pq_ativo',msg('ativo'),true,true));
				array_push($cp,array('$O 0:NÃO&1:SIM','pq_screen_only',msg('editor_only'),true,true));
				return($cp);
			}			

		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pm','pm_nome','pm_codigo');
				$cdm = array('cod',msg('nome'),msg('codigo'));
				$masc = array('','','','','','','');
				return(1);				
			}
		
		function row_subfield()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_sf','sf_nome','sf_codigo');
				$cdm = array('cod',msg('nome'),msg('codigo'));
				$masc = array('','','','','','','');
				return(1);				
			}
		function row_question()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pq','pq_pergunta','pq_codigo');
				$cdm = array('cod',msg('question'),msg('codigo'));
				$masc = array('','','','','','','');
				return(1);				
			}			
				
		function mostra_formulario_questao($ln,$rx)
			{
				$tipo = trim($ln['pq_tipo']);
				if ($tipo == 'T')
					{
						$sx .= '<BR><B>'.$rx.') ';
						$sx .= trim($ln['pq_pergunta']);
						$sx .= '</B><BR>';						
						$sx .= '<textarea cols=50 rows=4>';
						$sx .= '</textarea>';
						$sx .= '<BR>';
					}
				if ($tipo == 'C')
					{
						$sx .= '<BR><B>'.$rx.') ';
						$sx .= trim($ln['pq_pergunta']);
						$sx .= '</B>';
						
						$pq = array();
							array_push($pq,$ln['pq_resposta_1']);
							array_push($pq,$ln['pq_resposta_2']);
							array_push($pq,$ln['pq_resposta_3']);
							array_push($pq,$ln['pq_resposta_4']);
							array_push($pq,$ln['pq_resposta_5']);
							array_push($pq,$ln['pq_resposta_6']);
								
						for ($ra = 0;$ra < count($pq);$ra++)
							{
								$pqa = trim($pq[$ra]);
								if (strlen($pqa) > 0)
								{
									$sx .= '<BR><input type="radio" name="dd'.$rx.'" value="1"> ';
									$sx .= $pqa;
								}					
							}							
						
					}
					/* Select Box */
				if ($tipo == 'S')
					{
						$sx .= '<BR><B>'.$rx.') ';
						$sx .= trim($ln['pq_pergunta']);
						$sx .= '</B><BR>';
						
						$pq = array();
							array_push($pq,$ln['pq_resposta_1']);
							array_push($pq,$ln['pq_resposta_2']);
							array_push($pq,$ln['pq_resposta_3']);
							array_push($pq,$ln['pq_resposta_4']);
							array_push($pq,$ln['pq_resposta_5']);
							array_push($pq,$ln['pq_resposta_6']);
						$sx .= '<select name="dd'.$rx.'">';	
						$sx .= '<option value="">::: '.msg('select_option').' :::	</option>';
						for ($ra = 0;$ra < count($pq);$ra++)
							{
								$pqa = trim($pq[$ra]);
								if (strlen($pqa) > 0)
								{
									$sx .= '<option value="'.$pqa.'">';
									$sx .= $pqa;
									$sx .= '</option>';
								}					
							}							
						$sx .= '</select>';
					}					
				return($sx);	
			}
		function mostra_formulario()
			{
				global $edit_mode;
				$sx .= '<div id="content_form">';
				$sx .= '<Fieldset><legend>'.msg('form_avaliation').'</legend>';
				
				$sql = "select * from ".$this->tabela.'_questions ';
				$rlt = db_query($sql);
				
				$rx = 10;
				while ($line = db_read($rlt))
					{
						$sx .= $this->mostra_formulario_questao($line,$rx);
						$sx .= '<BR>';
						$rx++;
					}
				
				$sx .= $this->form;
				$sx .= '<A href="javascript:newxy2(\'parecer_model_popup.php\',600,500);">';
				$sx .= 'post';
				$sx .= '</A>';
				$sx .= '</fieldset>';
				$sx .= '</div>';
				return($sx);
			}
		function structure()
			{			
				return(1);
				$sql = "CREATE TABLE ".$this->tabela."_subfields (
					id_sf SERIAL NOT NULL ,
					sf_codigo CHAR( 5 ) NOT NULL ,
					sf_nome CHAR( 80 ) NOT NULL ,
					sf_ativo INTEGER NOT NULL
					)";
				$rlt = db_query($sql);
								
				$sql = "CREATE TABLE ".$this->tabela." (
					id_pm SERIAL NOT NULL ,
					pm_codigo CHAR( 5 ) NOT NULL ,
					pm_nome CHAR( 80 ) NOT NULL ,
					pm_journal_id CHAR( 7 ) NOT NULL ,
					pm_descricao CHAR( 80 ) NOT NULL ,
					pm_instrucoes TEXT NOT NULL ,
					pm_ativo INTEGER
					)";
				$rlt = db_query($sql);

				$sql = "CREATE TABLE ".$this->tabela."_questions (
					id_pq SERIAL NOT NULL ,
					pq_codigo CHAR( 5 ) NOT NULL ,
					pq_pergunta CHAR( 250 ) NOT NULL ,
					pq_tipo CHAR( 3 ) NOT NULL ,
					pq_area CHAR( 5 ) NOT NULL ,
					pq_resposta_1 CHAR( 250 ) NOT NULL ,
					pq_resposta_2 CHAR( 250 ) NOT NULL ,
					pq_resposta_3 CHAR( 250 ) NOT NULL ,
					pq_resposta_4 CHAR( 250 ) NOT NULL ,
					pq_resposta_5 CHAR( 250 ) NOT NULL ,
					pq_resposta_6 CHAR( 250 ) NOT NULL ,
					pq_instrucoes TEXT NOT NULL ,
					pq_screen_only INTEGER NOT NULL,
					pq_own char (1) NOT NULL,
					pq_ativo INTEGER NOT NULL
					)";
				$rlt = db_query($sql);
				
				$sql = "CREATE TABLE ".$this->tabela."_form (
					id_pf SERIAL NOT NULL ,
					pf_form CHAR( 5 ) NOT NULL ,
					pf_question char(5) NOT NULL ,
					pf_form_ordem INTEGER NOT NULL ,
					pf_ativo INTEGER NOT NULL
					)";
				$rlt = db_query($sql);
				
			return(1);
			}
		function updatex()
			{
					global $base;
				$c = 'pm';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}	
		function updatex2()
			{
				global $base;
				$c = 'pq';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela."_questions set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela."_questions set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}	
		function updatex3()
			{
				global $base;
				$c = 'sf';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela."_subfields set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela."_subfields set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}							
	}
	
