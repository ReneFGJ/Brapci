<?
class grupos
	{
	var $id_sg;
	var $sg_descricao;
	var $sg_participantes;
	var $sg_centro;
	var $sg_outros;
	var $sg_internacional;
	var $sg_criterio_inclusao;
	var $sg_criterio_exclusao;
	var $sg_riscos;
	var $sg_intervencao;
	var $sg_protocolo;
	var $sg_ativo;
	var $sg_data;
	var $sg_grupo;
	
	var $tabela = 'cep_submit_grupos';
	function cp()
		{ }
	function gravar()
		{
			$sql = "insert into ".$this-tabela;
			$sql .= " ($sg_descricao,$sg_participantes,$sg_centro";
			$sql .= "$sg_outros,$sg_internacional,$sg_criterio_inclusao,";
			$sql .= "$sg_criterio_inclusao,$sg_riscos,$sg_intervencao,";
			$sql .= "$sg_protocolo,$sg_ativo,$sg_data,";
			$sql .= "$sg_grupo";
			$sql .= ")";
			$sql .= " values ";
			$sql .= "('";
			$sql .= "'".$this->sg_descricao."','".$this->sg_participantes."','".$this->sg_centro."',";
			$sql .= "'".$this->sg_outros."','".$this->sg_internacional."','".$this->sg_criterio_inclusao."',";
			$sql .= "'".$this->sg_criterio_inclusao."','".$this->sg_riscos."','".$this->sg_intervencao."',";
			$sql .= "'".$this->sg_protocolo."','".$this->sg_ativo."','".$this->sg_data."',";
			$sql .= "'".$this->sg_grupo."'";
			$sql .= ")";
		}
	function form()
			{
					global $tab_max,$dd;
					$sg .= '<TR><TD class="lt1">';
					$sg .= '<fieldset>';
					$sg .= '<legend>';
					$sg .= msg('grupo').': <B><I>';
					$sg .= trim($line['sg_descricao']);
					$sg .= '</B></I>';
					$sg .= '</legend>';
					$sg .= '<form id="grpn">';
					$sg .= '<table width="100%" id="grpn" border=1>';
					$sg .= '<TR class="lt0" align="left">';
					$sg .= '<TD colspan=4>'.msg('grupo_name'); 
					$sg .= '<TR class="lt0" align="left">';
					$sg .= '<TD colspan=4><input type="text" size=80 maxsize=80 value="'.$dd[4].'" id="gr4" name="gr4">';

					$sg .= '<TR class="lt0" align="center">';
					$sg .= '<TD>'.msg('suj_total'); 
					$sg .= '<TD>'.msg('suj_centro'); 
					$sg .= '<TD>'.msg('suj_fora'); 
					$sg .= '<TD>'.msg('suj_internacional'); 
					$sg .= '<TR class="lt2" align="center">';
					$sg .= '<TD>-x-';
					$sg .= '<TD><input type="text" size=5 maxsize=6 value="'.$dd[1].'" id="gr1" name="gr1">';
					$sg .= '<TD><input type="text" size=5 maxsize=6 value="'.$dd[2].'" id="gr2" name="gr2">';
					$sg .= '<TD><input type="text" size=5 maxsize=6 value="'.$dd[3].'" id="gr3" name="gr3">';
					
					/* INCLUSAO */
					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('crit_incl');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>';
					$sg .= '<textarea cols=80 rows=5 id="gr6" name="gr6">';
					$sg .= $dd[6];
					$sg .= '</textarea>';

					/* EXCLUSAO */
					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('crit_excl');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>';
					$sg .= '<textarea cols=80 rows=5 id="gr7" name="gr7">';
					$sg .= $dd[7];			
					$sg .= '</textarea>';

					/* INTERVENCAO */
					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('intervencao');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>';
					$sg .= '<textarea cols=80 rows=5 id="gr8" name="gr8">';
					$sg .= $dd[8];					
					$sg .= '</textarea>';

					/* RISCO */
					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('risk');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>';
					$sg .= '<textarea cols=80 rows=5 id="gr9" name="gr9">';
					$sg .= $dd[9];					
					$sg .= '</textarea>';
					$sg .= '</table>';			
					$sg .= '</fieldset><BR>';
					$sg .= '</form>';
//					$sg .= '</table>';
					
					
			return($sg);
		}
		
	function grupos_mostra()
		{
			$sql = "select count(*) as grupos, sum(sg_participantes) as sg_participantes, ";
			$sql .= " sum(sg_centro) as sg_centro, ";
			$sql .= " sum(sg_outros) as sg_outros, ";
			$sql .= " sum(sg_internacional) as sg_internacional ";
			$sql .= " from ".$this->tabela;
			$sql .= " where sg_protocolo = '".$this->sg_protocolo."' ";
			$sql .= " and sg_ativo = 1 ";
			$sql .= " group by sg_protocolo ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
					$sg .= '<TR><TD class="lt1">';
					$sg .= '<table width="100%">';
					$sg .= '<TR class="lt0" align="center">';
					$sg .= '<TD>'.msg('suj_grupos'); 
					$sg .= '<TD>'.msg('suj_total'); 
					$sg .= '<TD>'.msg('suj_centro'); 
					$sg .= '<TD>'.msg('suj_fora'); 
					$sg .= '<TD>'.msg('suj_internacional'); 
					$sg .= '<TR class="lt5" align="center">';
					$sg .= '<TD width="20%"><B>'.$line['grupos']; 
					$sg .= '<TD width="20%"><B>'.$line['sg_participantes']; 
					$sg .= '<TD width="20%"><B>'.$line['sg_centro'];
					$sg .= '<TD width="20%"><B>'.$line['sg_outros']; 
					$sg .= '<TD width="20%"><B>'.$line['sg_internacional']; 
			$sg .= '</table>';			
						
			/* Detalhes dos grupos */
			$sql = "select * from ".$this->tabela;
			$sql .= " where sg_protocolo = '".$this->sg_protocolo."' ";
			$sql .= " and sg_ativo = 1 ";
			$sql .= " order by sg_data ";
			$rlt = db_query($sql);
			$sg .= '<TR><TD class="lt1">';
						
			while ($line = db_read($rlt))
				{
					global $tab_max;
					$sg .= '<A name="grp">';
					$sg .= '<fieldset>';
					$sg .= '<legend>';
					$sg .= msg('grupo').': <B><I>';
					$sg .= trim($line['sg_descricao']);
					$sg .= '</B></I>';
					$sg .= '</legend>';
					$sg .= '<table width="'.$tab_max.'">';
					$sg .= '<TR class="lt0" align="center">';
					$sg .= '<TD>'.msg('suj_total'); 
					$sg .= '<TD>'.msg('suj_centro'); 
					$sg .= '<TD>'.msg('suj_fora'); 
					$sg .= '<TD>'.msg('suj_internacional'); 
					$sg .= '<TR class="lt2" align="center">';
					$sg .= '<TD width="25%"><B>'.$line['sg_participantes']; 
					$sg .= '<TD width="25%"><B>'.$line['sg_centro'];
					$sg .= '<TD width="25%"><B>'.$line['sg_outros']; 
					$sg .= '<TD width="25%"><B>'.$line['sg_internacional']; 

					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('crit_incl');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>'.mst($line['sg_criterio_inclusao']);

					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('crit_excl');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>'.mst($line['sg_criterio_exclusao']);

					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('intervencao');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>'.mst($line['sg_intervencao']);

					$sg .= '<TR class="lt0">';
					$sg .= '<TD colspan=4>'.msg('risk');
					$sg .= '<TR class="lt1">';
					$sg .= '<TD colspan=4>'.mst($line['sg_riscos']);
					$sg .= '</table>';
					
					$sg .= '</fieldset><BR>';					
				}
				$sg .= '<div id="grupos">';
				$sg .= '</div>';

				$sg .= '<input type="button" id="grp_save" value="salvar grupo" style="display:none;">';
				$sg .= '<input type="button" id="grp_cancel" value="cancelar inserção" style="display:none;">';
				

				/* Novo Grupo */
				$sg .= '<a href="#grp" id="newgrp">Adicionar Grupo</a>'.chr(13);
				$sg .= '<p id="resultado"></p>'.chr(13);
				/* Fim do formulario */
			return($sg);
		}
	function updatex()
		{ }
	}
?>
