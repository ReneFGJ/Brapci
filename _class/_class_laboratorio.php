<?php
class laboratorio
	{
	var $tabela = "laboratorio";
	var $tabela_equipamento = "equipamento";
	
	function mostra_equipamento()
		{
			$line = $this->line_eq;
			$sta = $this->status();
			
			$sx .= '<fieldset><legend>Equipamento</legend>';
			
			$sx .= '<table width="100%">';
			$sx .= '<TR><TD width="25%"><TD width="25%"><TD width="50%">';
			$sx .= '<TR><TD colspan=3><h3>'.$line['eq_nome'].'</h3>';
			$sx .= '<TR><TD class="lt0">Marca';
			$sx .= '<TD class="lt0">Modelo';
			$sx .= '<td class="lt0">Série';
			
			$sx .= '<TR>';
			$sx .= '<TD><B>'.$line['eq_marca'].'</B>';
			$sx .= '<TD><B>'.$line['eq_modelo'].'</B>';
			$sx .= '<TD><B>'.$line['eq_seri'].'</B>';
			
			$sx .= '<TR>';
			$sx .= '<TD colspan=1 class="lt0">Situação';
			$sx .= '<TD class="lt0">Data Instalação/Previsão';
			$sx .= '<TD class="lt0">Descrição';
			
			$status = $line['eq_status'];
			$sx .= '<TR>';
			$sx .= '<TD class="lt2"><font color="blue"><B>'.$sta[$status].'</B></font>';
			$sx .= '<TD class="lt2">'.stodbr($line['eq_data_instalacao']);
			$sx .= '<TD class="lt2">'.$line['eq_descricao'];

			$sx .= '</table>';
			$sx .= '</fieldset>';
			
			return($sx);
		}
	
	function lista_equipamentos($prj)
		{
			$sql = "select * from ".$this->tabela_equipamento." where eq_local = '".strzero($prj,5)."' ";
			$rlt = db_query($sql);
			
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH>Descrição<TH>Marca<TH>Modelo<TH>Série<TH>Status';
			$sx .= '<TH width="5%">ação';
			$sta = $this->status();
			$id = 0;
			while ($line = db_read($rlt))
				{
						
					$id++;
					$link = '<A HREF="lab_equipamento_ver.php?dd0='.$line['id_eq'].'">';
					$linke = '<A HREF="lab_equipamento_ed.php?dd0='.$line['id_eq'].'">editar</A>';
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $link.$line['eq_nome'].'</A>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['eq_marca'];
					$sx .= '<TD class="tabela01">';
					$sx .= $line['eq_modelo'];
					$sx .= '<TD class="tabela01">';
					$sx .= $line['eq_seri'];
					$sx .= '<TD class="tabela01">';
					$sa = trim($line['eq_status']);
					$sx .= '<NOBR>'.$sta[$sa].' ('.$sa.')';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $linke;
				}
			if ($id==0)
				{
					$sx .= '<TR><TD colspan=5>Sem equipamento registrado';
				}
			$sx .= '</table>';
			return($sx);
		}

	function botao_novo()
		{
			$sx = '<form action="lab_ed.php">';
			$sx .= '<input type="submit" value="cadastrar novo >>>">';
			$sx .= '</form>';
			return($sx);
		}
	
	function botao_novo_eq()
		{
			global $dd;
			$sx = '<form action="lab_ed.php">';
			$sx .= '<input type="submit" value="cadastrar novo >>>">';
			$sx .= '</form>';
			return($sx);
		}

	function le_equipamento($id)
		{
			$sql = "select * from ".$this->tabela_equipamento." 
					where id_eq = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line_eq = $line;
					$this->laboratorio = $line['eq_local'];
				}			
		}
	function le($id)
		{
			$sql = "select * from ".$this->tabela." 
					left join centro on lab_escola = centro_codigo
					where id_lab = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
				}
		}
	function mostra()
		{
			$sta = $this->status();
			
			$line = $this->line;
			$sx .= '<fieldset><legend>Laboratório</legend>';
			$sx .= '<table width="100%">';
			$sx .= '<TR><TD class="lt0">Nome do laboratório';
			$sx .= '    <TD class="lt0">Descrição';
			$sx .= '<TR><TD class="lt2"><B>'.$line['lab_nome'].'</B>';
			$sx .= '    <TD class="lt2">'.$line['centro_nome'];
			
			$sx .= '<TR><TD class="lt0">Status';
			$sx .= '<TR><TD class="lt2">'.$sta[$line['lab_status']];
			$sx .= '</table>';
			$sx .= '</fieldset>';
			
			return($sx);
			
		}
	function lista_laboratorios($escola='')
		{
			$sql = "select * from ".$this->tabela."
						left join centro on lab_escola = centro_codigo 
						where lab_status <> 'X' 
						order by centro_nome, lab_nome
						";
			$rlt = db_query($sql);
			
			$sx = '<table width="100%">';
			$sx .= '<TR><TH>Escola<TH>Laboratório<TH>Ação';
			while ($line = db_read($rlt))
				{
					$link = '<a href="lab_ed.php?dd0='.$line['id_lab'].'">editar</A>';
					$linkv = '<a href="lab_equipamento.php?dd0='.$line['id_lab'].'">';
					$sx .= '<TR>';
					$sx .= '<TD width="20%">'.$line['centro_nome'].'</A>';
					$sx .= '<TD width="75%">'.$linkv.$line['lab_nome'].'</A>';
					$sx .= '<TD width="5%">'.$link;
				}
			$sx .= '</table>';
			return($sx);
		}
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_lab','',False,True));
			array_push($cp,array('$S80','lab_nome','Nome do laboratório',True,True));
			array_push($cp,array('$S8','lab_cr','CR',False,True));
			array_push($cp,array('$H8','lab_codigo','CR',False,True));
			array_push($cp,array('$S80','lab_apoio','Salas de apoio',False,True));
			array_push($cp,array('$T80:5','lab_descricao','Descrição',False,True));
			$sta = $this->status();
			$op = $this->op;
			array_push($cp,array('$O : &'.$op,'lab_status','',True,True));
			array_push($cp,array('$Q centro_nome:centro_codigo:select * from centro','lab_escola','',True,True));
			return($cp);
		}
	function status()
		{
			$op = '&A:Planejamento de compra';
			$op .= '&B:Ordem de compra emitida';
			$op .= '&C:Comprado';
			$op .= '&D:Em transporte/logistica';
			$op .= '&E:Aguardando instalação';
			$op .= '&I:Instalado';
			$op .= '&F:Com defeito';			
			$op .= '&Y:Desativado (Baixado)';
			$op .= '&X:Cancelado';			
			$status = array('A'=>'Planejamento de compra',
							'B'=>'Ordem de compra emitida',
							'C'=>'Comprado',
							'D'=>'Em transporte/logistica',
							'E'=>'Aguardando instalação',
							'I'=>'Instalado',
							'F'=>'Com defeito',
							'Y'=>'Desativado (Baixado)',
							'X'=>'Cancelado'
							);
			$this->op = $op;							
			return($status);
		}
	function cp_equipamento()
		{
			$cp = array();
			array_push($cp,array('$H8','id_eq','',False,True));
			array_push($cp,array('$H8','eq_codigo','',False,True));
			
			array_push($cp,array('${','','Sobre o equipamento',False,True));
			array_push($cp,array('$S40','eq_nome','Nome do equipamento',True,True));
			array_push($cp,array('$S40','eq_marca','Marca',False,True));
			array_push($cp,array('$S40','eq_modelo','Modelo',False,True));
			array_push($cp,array('$S40','eq_seri','N. série',False,True));
			array_push($cp,array('$S40','eq_patrimonio','N. patrimonio',False,True));
			array_push($cp,array('$Q lab_nome:lab_codigo:select * from '.$this->tabela.' where lab_status <> \'X\'','eq_local','Local de instalação',True,True));
			array_push($cp,array('$}','','',False,True));
			
			array_push($cp,array('$T80:4','eq_descricao','Descrição do equipamento',True,True));
			
			
			array_push($cp,array('${','','Sobre a aquisição',False,True));
			array_push($cp,array('$S7','eq_projeto','Projeto da Arquisição (protocolo)',False,True));
			array_push($cp,array('$S60','eq_fomento','Fomento',False,True));
			array_push($cp,array('$I8','eq_valor','Valor do equipamento',False,True));
			array_push($cp,array('$D8','eq_data_instalacao','Data instalação/Prev.Instalação',False,True));
			//array_push($cp,array('$D8','eq_data_baixa','Data baixa',True,True));
			array_push($cp,array('$}','','',False,True));

			//array_push($cp,array('$H8','eq_status','Situação',False,True));
			$sta = $this->status();
			$op = $this->op;
			
			array_push($cp,array('$O : '.$op,'eq_status','Situação:',True,True));
			
			return($cp);			
		}

	function updatex()
			{
				global $base;
				$c = 'lab';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
	function updatex_eq()
			{
				global $base;
				$c = 'eq';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela_equipamento." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela_equipamento." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
				
	function structure()
		{
			$sql = "create table laboratorio 
					(
						id_lab serial not null,
						lab_codigo char(5),
						lab_nome char(80),
						lab_cr char(20),
						lab_apoio char(80),
						lab_descricao text,
						lab_status char(1),
						lab_escola char(5),
						lab_rh text
					)
			";
			//$rlt = db_query($sql);
			
			$sql = "create table equipamento 
					(
						id_eq serial not null,
						eq_codigo char(7),
						eq_marca char(40),
						eq_modelo char(40),
						eq_seri char(40),
						eq_patrimonio char(20),
						eq_status char(1),
						
						eq_local char(40),
						eq_projeto char(7),
						
						eq_fomento char(60),
						eq_laboratorio char(5),
						eq_descricao text,
						eq_data_instalacao char(8),
						eq_data_baixa char(8),
						
						eq_valor float,
						eq_ativo integer						
					)
			";
			//$rlt = db_query($sql);
		}	
	}
?>

