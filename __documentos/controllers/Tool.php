<?php
// This file is part of the Brapci Software.
//
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software.
//
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */

class tool extends CI_Controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapci_publico.';
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
	}

	function index() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> view("header/cab");

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	
	function matrix() {
		/* Model */
		$this -> load -> model('Search');
		$this->load->model('tools');
		$this -> load -> view("header/cab");
		
		$tela = $this->tools->file_update();
		
		
		/* le arquivo se existe */
		$tx = $this->tools->readfile();
		if (strlen($tx) > 0)
			{
				$tela .= '<h3>conteúdo</h3>';
				$tx = $this->tools->tratar($tx);
				$authors = $this->tools->export_cols($tx,1);
				$tela .= '<pre>'.$authors.'</pre>';
				
			}
		/* Monta tela */	
		$data['content'] = $tela;
		$this->load->view('content',$data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}	
	function change01() {
		/* Model */
		$this -> load -> model('Search');
		$this->load->model('tools');

		$data['link'] = base_url('index.php/tool/change01');
		$tela = $this->tools->file_update($data);	
		
		/* le arquivo se existe */
		$tx = $this->tools->readfile();

		if (strlen($tx) > 0)
			{
				$tela .= '<h3>conteúdo</h3>';
				$tx = $this->tools->tratar_xls($tx);
				$this->tools->download_file($tx);
				return('');				
			}
		/* Monta tela */	
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this->load->view('content',$data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	function change02() {
		/* Model */
		$this -> load -> model('Search');
		$this->load->model('tools');

		$data['link'] = base_url('index.php/tool/change02');
		$tela = $this->tools->file_update($data);	
		
		/* le arquivo se existe */
		$tx = $this->tools->readfile();

		if (strlen($tx) > 0)
			{
				$tx = $this->tools->gerar_nomes_xls($tx);
				$this->tools->download_file($tx);
				return('');				
			}
		/* Monta tela */	
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this->load->view('content',$data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	function change03() {
		/* Model */
		$this -> load -> model('Search');
		$this->load->model('tools');

		$data['link'] = base_url('index.php/tool/change03');
		$tela = $this->tools->file_update($data);	
		
		/* le arquivo se existe */
		$tx = $this->tools->readfile();

		if (strlen($tx) > 0)
			{
				$tx = $this->tools->gerar_matriz_xls($tx);
				$this->tools->download_file($tx);
				return('');				
			}
		/* Monta tela */	
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this->load->view('content',$data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	function change04() {
		$this -> load -> model('Search');
		/* Model */
		$this->load->model('tools');

		$data['link'] = base_url('index.php/tool/change03');
		$tela = $this->tools->file_update($data);	
		
		/* le arquivo se existe */
		$tx = $this->tools->readfile();

		if (strlen($tx) > 0)
			{
				$tx = $this->tools->gerar_matriz_pajek($tx);
				$this->tools->download_file($tx,'.net');
				return('');				
			}
		/* Monta tela */	
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this->load->view('content',$data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}			
}
?>
