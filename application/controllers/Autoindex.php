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
 
 /* Acknowledge 
  * STOPWORD LIST BY http://www.ranks.nl/stopwords
  */
class autoindex extends CI_controller {

	function __construct() {
		global $db_public;

		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('xml_dom');
		/* $this -> lang -> load("app", "portuguese"); */
		$this -> load -> library('session');
		$db_public = 'brapc607_public.';
		date_default_timezone_set('America/Sao_Paulo');
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}

	function cab() {
		$data = array();
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$this -> load -> view("header/cab_admin", $data);
		$this -> security();
	}

	function index() {
		$this -> cab();
		$data = array();	
		$this -> load -> model('indexing/autoindexs');
		
				$data['content'] = '<ul>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/sql/').'" class="link lt1">Stopword inport</a></li>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/test01/').'" class="link lt1">Teste01</a></li>';
				$data['content'] .= '</ul>';		
		
		$this -> load -> view("content", $data);
	}

	function test01($offset='0',$limit='100')
		{
			
		$this -> cab();
		$data = array();	
		$this -> load -> model('indexing/autoindexs');

		$rand = date("s");
		$rand = 12;
		$wh = " (round(id_ar / $rand)*$rand = id_ar) ";
		$wh = " 1=1 ";
		
		$sql = "select * from brapci_article 
						where $wh
						order by id_ar 
						limit $limit offset $offset ";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		
		$sx = '';
		$sx .= '<a href="'.base_url('index.php/autoindex/test01/'.($offset+$limit).'/'.$limit).'">NEXT</a>';
		$sx .= '<table class="tabela0 lt2" width="100%">';
		for ($r=0;$r < count($rlt);$r++)
			{
				$line = $rlt[$r];
				$title = trim($line['ar_titulo_1']);
				$idiomas = $this->autoindexs->language_detect($title);			
				$sx .= '<tr>';
				
				$sx .= '<td align="center">';
				$sx .= $line['id_ar'];
				$sx .= '</td>';	

				$sx .= '<td>';
				$sx .= $idiomas;
				$sx .= '</td>';	
				
				$sx .= '<td>'.$line['ar_idioma_1'].'</td>';
				
				$dif = '';
				if (($idiomas != $line['ar_idioma_1']) and ($idiomas != '-')) { $dif = '<font color="red">Diferente</font>'; }
				
				$sx .= '<td>';	
				$sx .= $dif;		
				$sx .= '</td>';
				$sx .= '<td class="borderb1">';
				$sx .= $title;
				$sx .= '</td>';
				

				$sx .= '</tr>';				
			}		
		$sx .= '</table>';
		$data['content'] = $sx;
		
		$this -> load -> view("content", $data);			
		}
		
	function sql($action='') {
		$this -> cab();
		$data = array();	
		$this -> load -> model('indexing/autoindexs');
		
		switch ($action)
			{
			case 'stopword_br':
				$data['content'] = $this->autoindexs->insert_stopword('__documentos/_stopword_portuguese.txt','pt_BR');
				break;
			case 'stopword_en':
				$data['content'] = $this->autoindexs->insert_stopword('__documentos/_stopword_english.txt','en');
				break;
			case 'stopword_es':
				$data['content'] = $this->autoindexs->insert_stopword('__documentos/_stopword_spanish.txt','es');
				break;
			case 'stopword_fr':
				$data['content'] = $this->autoindexs->insert_stopword('__documentos/_stopword_french.txt','fr');
				break;
			case 'stopword_it':
				$data['content'] = $this->autoindexs->insert_stopword('__documentos/_stopword_italian.txt','it');
				break;
			default:
				$data['content'] = '<ul>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/sql/stopword_br').'" class="link lt1">Stopword in Portuguese</a></li>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/sql/stopword_en').'" class="link lt1">Stopword in English</a></li>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/sql/stopword_es').'" class="link lt1">Stopword in Spanish</a></li>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/sql/stopword_fr').'" class="link lt1">Stopword in French</a></li>';
				$data['content'] .= '<li><a href="'.base_url('index.php/autoindex/sql/stopword_it').'" class="link lt1">Stopword in Italian</a></li>';				
				$data['content'] .= '</ul>';				
			}
		
		
		$this -> load -> view("content", $data);
	}
}
?>
