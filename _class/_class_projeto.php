<?
class projeto
	{
		var $id;
		var $titulo;
		var $resumo;
		var $objetivo;
		var $ano;
		var $keywords;
		var $pesquisador;
		var $area;
		var $area_grande;
		var $p_inicio;
		var $p_fim;
		var $status;
		var $financiador;
		var $linha;
		var $grupo;
		
	/* mostra resumo dos grupos */
	function resumo_mostra()
		{
		global $tab_max;
		$gps = $this->resumo();
		$sx = '<table width="'.$tab_max.'" cellpadding=3 cellspacing=0 border=1>';
		$sx .= '<TR align="center">';
		$sx .= '<TD width="25%"><FONT CLASS="lt0">ativos</FONT><BR>'.($gps[1]).'</TD>';
		$sx .= '<TD width="25%"><FONT CLASS="lt0">em cadastramento</FONT><BR>'.$gps[0].'</TD>';
		$sx .= '<TD width="25%"	><FONT CLASS="lt0">encerrados</FONT><BR>'.$gps[2].'</TD>';
		$sx .= '<TD width="25%"><FONT CLASS="lt0">cancelados</FONT><BR>'.$gps[3].'</TD>';
		$sx .= '</TR></table>';
		return($sx);
		}
		
		
	function resumo()
		{
		$sql = "select count(*) as total, p_status from projetos group by p_status ";
		$rlt = db_query($sql);
		$rst = array(0,0,0,0);
		/* 0 - em cadastro (@)
		   1 - ativo (A)
		   2 - encerrado (E)
		   3 - cancelado (X)
		*/
		while ($cline = db_read($rlt))
			{
			$sta = $cline['p_status'];
			$tot = $cline['total'];
			
			if ($sta == 'A') { $rst[1] = $rst[1] + $tot; }
			if ($sta == '@') { $rst[0] = $rst[0] + $tot; }
			if ($sta == 'E') { $rst[2] = $rst[2] + $tot; }
			if ($sta == 'X') { $rst[3] = $rst[3] + $tot; }
			}
			return($rst);
		}
		
	function ano()
		{
		$ano = $this->p_inicio;
		}


	function top()
		{
		$img = 'tag_'.$this->area_grande.'.png';
		$sx = '<DIV id="cab_header" style="background-image: url(img/'.$img.');">';
		$sx .= $this->area;
		$sx .= '</DIV>';
		return($sx);
		}
		
	/**
	 * Recupera dados do registro
	 */
	function le($id = '')
		{
		$limit = 1;
		$sql = "select ".limit($limit,'i')." * from projetos ";
		if (strlen($id) > 0)
			{ $sql .= "where ((id_p = ".$id.") or (p_codigo = '".$id."'))"; }
		$sql .= limit($limit,'f');
		$rlt = db_query($sql);
		
		if ($line = db_read($rlt))
			{
			$this->id = $line['p_codigo'];
			$this->titulo = UpperCase($line['p_titulo']);
			$this->resumo = $line['p_resumo'];
			$this->objetivo = $line['p_objetivo'];
			$this->ano = $line['p_ano'];
			$this->p_inicio  = $line['p_inicio'];
			$this->p_fim  = $line['p_fim'];
			$this->pesquisador  = $line['p_integrantes'];
			$this->financiador  = $line['p_financiador'];
			$this->linha   = $line['p_linha'];
			$this->grupo   = $line['p_grupo'];
			
			$this->keywords = $line['p_palavra_chave'];

			$area = trim($line['p_area_texto']);
			if (strpos($area,';') > 0) 
				{ $area = substr($area,0,strpos($area,';')); }
			$this->area = $area;
			
			$area = trim($line['p_area_texto']);
			if (strpos($area,';') > 0) 
				{ $area = substr($area,strpos($area,';')+1,strlen($area)); }
			if ($area == 'Ciências Sociais Aplica') { $area_g = 'SA'; }
			if ($area == 'Ciências Biológicas') { $area_g = 'CV'; }
			if ($area == 'Ciências Exatas e da Terra') { $area_g = 'CE'; }
			if ($area == 'Ciências Humanas') { $area_g = 'CH'; }
			if ($area == 'Engenharias') { $area_g = 'CE'; }
			
			$this->area_grande = $area_g;
			}
		return(true);
		}
		
	function grupos()
		{
		return('');
		}

	function grupos_mostra()
		{
		$sql = "SELECT * FROM projetos_docentes ";
		$sql .= " left join docentes on pd_docente = pp_cracha ";
		$sql .= "WHERE pd_projeto = '".$this->id."' ";
		$rlt = db_query($sql);
		return('');
		}

	function pesquisadores_mostra()
		{
//		$this->pesquisadores();
//		$sx = '';
//		$ps = $this->pesquisador;
//		for ($r=0;$r < count($ps);$r++)
//			{
//			$ln = $ps[$r];
//			$sx .= nbr_autor($ln['pp_nome'],1).'<BR>';
//			}
		/* Integrantes */
		$sx = '<I><h3>pesquisadores</h3></I><BR>';
		$sx .= $this->pesquisador;
		return($sx);
		}

	function pesquisadores()
		{
		$cp = 'pp_nome,pd_status,pp_cracha,pp_escolaridade,pp_titulacao,pp_ss,';
		$cp .= 'pp_centro,pp_email,pp_email_1';
		$sql = "SELECT $cp FROM projetos_docentes ";
		$sql .= " left join docentes on pd_docente = pp_cracha ";
		$sql .= "WHERE pd_projeto = '".$this->id."' ";
		$rlt = db_query($sql);
		$this->pesquisador = array();
		
		while ($line = db_read($rlt))
			{ array_push($this->pesquisador,$line); }
		return(true);
		}
	}
	
?>