<?php
class agencia_fomento
	{
		var $nome;
		var $sigla;
		var $contato;
		var $endereco;
		var $cidade;
		var $codigo;
		
		var $tabela = "agencia_de_fomento";
		
		function cp()
			{
				//$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN agf_imagem char(100)";
				//$rlt = db_query($sql);
				$cp = array();
				array_push($cp,array('$H8','id_agf','id',False,true));
				array_push($cp,array('$S7','agf_codigo','id',False,true));
				array_push($cp,array('$S100','agf_nome',msg('agencia_nome'),False,true));
				array_push($cp,array('$S20','agf_sigla',msg('agencia_sigla'),False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO&2:Grupo','agf_ativo',msg('ativo'),False,true));
				array_push($cp,array('$H8','agf_cidade','',False,true));
				array_push($cp,array('$H8','agf_estado','',False,true));
				array_push($cp,array('$S100','agf_imagem','Link da Imagem (ex:http://www.pucpr.br/logo.png)',False,true));
				array_push($cp,array('$H8','agf_pais','',False,true));
				array_push($cp,array('$[1-99]','agf_ordem','Ordem de mostrar',True,true));
				return($cp);
			}
		
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_agf','agf_nome','agf_sigla','agf_ativo','agf_ordem');
				$cdm = array('cod',msg('agencia'),msg('sigla'),msg('ativo'),msg('ordem'));
				$masc = array('','','','SN');
				return(1);				
			}
		function le($id='',$codigo='')
			{
				if (strlen($id) > 0) { $this->id = $id; }
				$sql = "select * from ".$this->tabela." where id_agf = ".round($id);
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
				{
					$this->nome = trim($line['agf_nome']);
					$this->codigo = trim($line['agf_codigo']);
					$this->sigla = trim($line['agf_sigla']);
					$this->codigo = trim($line['agf_codigo']);
				}
				return(true);
			}
		function mostra_dados()
			{
				$sx = '<fieldset><legend>'.msg('agencia_fomento').'</legend>';
				$sx .= '<Table width="100%" class="lt0">';
				$sx .= '<TR><TD>'.msg('agencia_nome');
				$sx .= '<TR><TD class="lt2"><B>'.$this->nome;
				if (strlen($this->sigla) > 0) { $sx .= ' ('.$this->sigla.')'; }
				$sx .= '</table>';
				$SX .= '</fieldset>';
				return($sx);
			}			
		function structure()
			{
				$sql = "CREATE TABLE agencia_de_fomento (
					id_agf SERIAL NOT NULL ,
					agf_codigo CHAR( 5 ) NOT NULL ,
					agf_nome CHAR( 100 ) NOT NULL ,
					agf_sigla CHAR( 20 ) NOT NULL ,
					agf_ativo INT NOT NULL ,
					agf_cidade CHAR( 5 ) NOT NULL ,
					agf_estado CHAR( 5 ) NOT NULL ,
					agf_pais CHAR( 5 ) NOT NULL ,
					agf_descricao TEXT
					)";
			$rlt = db_query($sql);
			}
		function updatex()
			{
				global $base;
				$c = 'agf';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}						
	}
