<?php
 /**
  * Avaliador Limbo
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2012 - sisDOC.com.br
  * @access public
  * @version v0.12.08
  * @package Classe
  * @subpackage UC0007 - Avaliador Limbo
 */

class avaliador_limbo
	{
		var $id;
		var $link;
		var $nome;
		var $codigo;
		var $area;
		var $line;
		var $data;
		var $status;
		var $email;
				
		var $tabela = 'pareceristas_limbo';
		
		function exporta_para_banco()
			{
				require('_class_pareceristas.php');
				$par = new parecerista;
				$sx .= $par->parecerista_inport($this->nome,$this->link,$this->instituicao,$this->area,$this->email);
				$this->alterar_status('A',$this->codigo);
				return($sx);
			}
		function alterar_status($sta,$codigo)
			{
				$sql = "update ".$this->tabela." set 
					plb_status = '$sta' 
					where (plb_codigo = '$codigo') or (id_plb = ".round($id).')';
				$rlt = db_query($sql);
				
			}
			
		function acoes_executa()
			{
				$acao = '0001';
				if (($acao == '0001') and ($this->status='@'))
					{
						$sx .= '<BR><BR>Ação executado<BR><BR>';						
						$sx .= $this->exporta_para_banco();
					}
				return($sx);
			}
		
		function mostra_acoes()
			{
				global $dd,$acao;
				$sx .= '<fieldset><legend>ações</legend>';
				$sx .= '<table width="100%" class="lt0">';
				$sx .= '<TR><TD align="center">';
				$sx .= '<form method="get" action="'.page().'">';
				$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';~
				$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
				$sta = $this->status;
				$b1 = 'convite aceito';
				if ($sta == '@')
					{
						$sx .= '<input type="submit" name="acao" value="'.$b1.'">';
					}
				$sx .= '</form>';
				$sx .= '</table>';
				$sx .= '</fieldset>';
				
				if ($acao == $b1) { $sx .= $this->acoes_executa(); }
				return($sx);
			}
		function mostra_dados()
			{
				global $date;
				$sx .= '<fieldset><legend>dados do avaliador</legend>';
				$sx .= '<table width="100%" class="lt0">';
				$sx .= '<TR><TD>nome do convidado';
				$sx .= '<TR><TD class="lt2" colspan=2><B>'.$this->nome;

				$sx .= '<TR><TD>Área';
				$sx .= '<TR><TD class="lt2"><B>'.$this->area;

				$sx .= '<TR><TD>Link do lattes	<TD>Data do convite';
				$sx .= '<TR><TD class="lt2"><B>'.$this->link;
				$sx .= '<TD>'.$date->stod($this->data);

				$sx .= '</table>';
				$sx .= '</fieldset>';
				
				/*
				 * acaoes
				 */
				$sx .= $this->mostra_acoes();
				
				echo $sx;
				return($sx);
			}
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_plb','id',False,true));
				array_push($cp,array('$S7','plb_codigo',msg('codigo'),False,False));
				array_push($cp,array('$S80','plb_nome',msg('nome'),true,true));
				array_push($cp,array('$S100','plb_email',msg('email'),False,true));
				array_push($cp,array('$S15','plb_area',msg('area_cnpq'),true,true));
				array_push($cp,array('$S100','plb_link',msg('link_lattes'),true,true));
				array_push($cp,array('$S1','plb_status',msg('status'),true,true));
				array_push($cp,array('$D8','plb_data',msg('data_cadastro'),true,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_plb','plb_nome','plb_codigo','plb_status');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('status'));
				$masc = array('','','','','','','');
				return(1);				
			}		
		
		function le($cod='')
			{
				if (strlen($cod) > 0) { $this->codigo = $cod; }
				$sql = "select * from ".$this->tabela." where (plb_codigo = '".$this->codigo."') or (id_plb = ".round($this->codigo).") ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->id = $line['id_plb'];
						$this->link = $line['plb_link'];
						$this->nome = $line['plb_nome'];
						$this->codigo = $line['plb_codigo'];
						$this->area = $line['plb_area'];
						$this->line = $line;
						$this->data = $line['plb_data'];
						$this->status = $line['plb_status'];
					}
				return(1);
			}


		function save()
			{
				$data = date("Ymd");
				$link = $this->link;
				$nome = $this->nome;
				$codigo = $this->codigo;
				$area = $this->area;
				$id = $this->id;
				
				if (strlen($id) ==0)
					{ $sql = "insert into ".$this->tabela. "
							(plb_link,plb_data,plb_status,plb_codigo,plb_nome,plb_area)
							values
							('$link',$data,'@','','$nome','$area') 
							";
							$rlt = db_query($sql);
					} else {
						$sql .= "update ".$this->tabela." set plb_status = '@', plb_area = '".$this->area."' where id_plb = ".$id;
						$rlt = db_query($sql);
					}
				$this->updatex();
				return(1);
			}
		
		function listar()
			{
				global $tab_max;
				$sql = "select * from ".$this->tabela." 
						order by plb_status, plb_nome ";
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1">';
				$sx .= '<TR><TH>Nome<TH>Área<TH>Data<TH>Codigo<TH>Status';
				while ($line = db_read($rlt))
					{
						$link = '<A HREF="pareceristas_limbo_detalhe.php?dd0='.$line['id_plb'].'&dd90='.checkpost($line['id_plb']).'">';
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= $link;
						$sx .= trim($line['plb_nome']);
						$sx .= '<TD>';
						$sx .= trim($line['plb_area']);
						$sx .= '<TD>';
						$sx .= stodbr($line['plb_data']);
						$sx .= '<TD>';
						$sx .= trim($line['plb_codigo']);
						$sx .= '<TD align="center">';
						if (trim($line['plb_status']) == '@')
							{ $sx .= '[sem retorno]'; }
						if (trim($line['plb_status']) == 'A')
							{ $sx .= '[retorno aceito]'; }
												}
				$sx .= '</table>';
				echo $sx;
			}
		function mensagem()
			{
				$nome = $this->nome;
				$link = $this->link;
				
				$sqlc = "select * from ".$this->tabela." 
					where (plb_link = '$link') and (plb_nome='$nome') ";
				$rlt = db_query($sqlc);
				
				if (!($line = db_read($rlt)))
					{
						$this->save();
						$rlt = db_query($sqlc);
						$line = db_read($rlt);
					}
					
				$this->codigo = $line['plb_codigo'];
				$this->le();
				return(1); 
			}
		function structure()
			{
				$sql = "DROP TABLE pareceristas_limbo";
				$rlt = db_query($sql);

				$sql = "CREATE TABLE pareceristas_limbo (
					id_plb SERIAL NOT NULL ,
					plb_link CHAR( 40 ) NOT NULL ,
					plb_data INT NOT NULL ,
					plb_area CHAR( 15 ),
					plb_nome CHAR( 80 ),
					plb_status CHAR( 1 ),
					plb_email CHAR( 100 ),
					plb_codigo CHAR( 7 )
					)";
				$rlt = db_query($sql);
				return(1);
			}
			
		function updatex()
			{
				global $base;
				$c = 'plb';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }	
				$rlt = db_query($sql);
			}				
	}
?>
