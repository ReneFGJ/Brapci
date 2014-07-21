<?php
 /**
  * Curriculo Lattes
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2012 - sisDOC.com.br
  * @access public
  * @version v0.12.08
  * @package Classe
  * @subpackage UC0001 - Curriculo Lattes
 */
class lattes
	{
		var $link;
		var $pesquisador;
		var $update;
		var $lattes_html;
		var $tabela = "lattes_cache";
		
/**
 * salva conteúdo do lattes
 */		
		function save()
			{
				$link = $this->link;
				$nome = $this->pesquisador;
				$data = date("Ymd");
				$lattes = $this->lattes_html;
				$sql = "select * from ".$this->tabela." where 
					lattes_update = $data and lattes_link = '$link'
				";
				$rlt = db_query($sql);
				
				if (!($line = db_read($rlt)))
					{
						$sql = "update ".$this->tabela." set lattes_ativo = 0 where lattes_link = '$link' and lattes_ativo = 1 ";
						$rlt = db_query($sql);
				
						$sql = "insert into ".$this->tabela." 
							(lattes_link,lattes_nome,lattes_content,lattes_update,lattes_ativo)
							values
							('$link','$nome','$lattes',$data,1)";
						$rlt = db_query($sql);
					}
				return(1);
			}
/**
 * recupera_campo link para modelo reduzido
 * @param string $sr conteúdo do lattes
 * @return int|string (erro,string);
 */		
 		function lattes_short($sr)
			{
				$sr = $this->retira_html($sr);
				$pos = strpos($sr,'http://lattes.cnpq.br/');
				if ($pos > 0)
					{
						$sn = sonumero(substr($sr,$pos,40));
						$sn = 'http://lattes.cnpq.br/'.$sn;
						return(array(0,$sn));
					} else {
						return(array('1','Nome curto não localizado no conteúdo'));
					}
			}
	

/**
 * retira marcações html
 * @param string $sr conteúdo html
 * @return string (erro,string)
 */
		function retira_html($sx)
			{
					/* retira tags HTML */
					$sx=strip_tags($sx);
					$sx = charConv($sx);
					$sx = troca($sx,"'",'´');										
					return($sx);
			}
			

/**
 * Inportar o link do lattes do professor
 * @var link; link do CNPq
 * @example http://buscatextual.cnpq.br/buscatextual/visualizacv.do?id=B243062
 * @example http://lattes.cnpq.br/1526528881898399 
 * @method string inport() borp(string $link)
 */		
		function inport($link='')
			{
				if (strlen($link) > 0) { $this->link = $link; }
				/* inporta dados do link */
				$sr = $this->inport_link($this->link);
				
				/* retorna se houve erros */
				if ($sr[0]!=0)
					{ return($sr); exit; }
				$lattes = $sr[1];
				
				/* convert o link para o modelo simplificado */
				$sl = $this->lattes_short($sr[1]);
				if ($sr[0]!=0)
					{ return($sl); exit; }
				$this->link = $sl[1];
							
				/* Recupera nome do pesquisador */
				$lattes = $this->retira_html($lattes);
				$this->pesquisador = $this->recupera_campo($lattes,'name');
				
				$lattes = troca($lattes,'('.$this->pesquisador,'');
				$lattes = substr($lattes,strpos($lattes,$this->pesquisador),strlen($lattes));
				$this->lattes_html = $lattes;
				
												
				/* salva conteúdo na base de dados */
				echo '====>'.$this->pesquisador;
				$this->save();
			}
			
/** 
 * Recupera informações do arquivo
 * @param string|string conteúdo | campo a ser recuperado
 * @return string conteúdo do campo
 */
			function recupera_campo($sr,$field)
				{
					/* Nome do pesquisador */
					if ($field =='name')
						{
							$si = 'culos Lattes (';
							$sf = ')';
							$pos = strpos($sr,$si);
							if ($pos > 0)
								{
									$nome = substr($sr,$pos+strlen($si),200);
									$nome = substr($nome,0,strpos($nome,$sf));
								}
							return($nome);
						}
					return('void');
				}
			
/**
 * realiza leitura do arquivo do lattes
 * @param string $link endereço http do lattes
 * @return int|string (erro,string);
 */
		function inport_link($link)
			{
				$erro = 0;
				$erros = array(	
						'0'=>'Consulta realizada com sucesso',
						'1'=>'Link inválido',
						'2'=>'Erro de acesso ao link',
						'3'=>'Link em branco');
						
				/* não executa se estiver em branco */
				if (strlen($link) > 0)
					{
					/* valida link */
					$ok = 0;
					$sl = 'http://buscatextual.cnpq.br/buscatextual/visualizacv.do';
					if (substr($link,0,strlen($sl)) == $sl) { $ok = 1; }
					$sl = 'http://lattes.cnpq.br/';
					if (substr($link,0,strlen($sl)) == $sl) { $ok = 1; }
					
					/* retorna se ok não for validado com erro 01 */
					if ($ok==0) { return(array(1,$erros['1'].' ['.$link.']')); }

					/* realiza leitura */
					$fl=fopen(trim($link),"R");
					if ($fl)
						{
						$sr='';
						while(!feof($fl)) { $sr.= fgets($fl,4096); }
						fclose($fl); 
						}
					/* retorna erro 0 e conteúdo */
					return(array(0,$sr));
					} else {
						return(array(1,$erros['3']));
					}
			}
		function structure()
			{
				$sql = "CREATE TABLE lattes_cache (
						id_lattes serial NOT NULL ,
						lattes_link CHAR( 100 ),
						lattes_nome CHAR( 100 ),
						lattes_content TEXT,
						lattes_update INT ,
						lattes_ativo INT
						)";
				$rlt = db_query($sql);
			}
	}
?>