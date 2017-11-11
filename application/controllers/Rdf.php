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
class rdf extends CI_controller {
	var $page_load_type = 'J';
	var $jid = '';

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

	function index($type='',$id='') {
		set_time_limit(6000*10);
		header('Content-type: text/plain');
		$this->load->model('rdfs');
		$this->load->model('articles');
		
		$sx = $this->rdfs->rdf_namespace_brapci();
		
		$sx .= $this->rdfs->rdf_methodology();
			
		/******************************************************************* ARTIGOS **********/
		$wha = '';
		$sql = "select ar_codigo from brapci_article 
						WHERE ar_status <> 'X'
						AND at_metodo_1 LIKE '0%' 
					limit 5000
				";
		$sql = "select ar_codigo from brapci_article 
						WHERE ar_status <> 'X'
					limit 500
				";
		$rlt5 = $this->db->query($sql);
		$rlt5 = $rlt5->result_array();
		for ($rq=0;$rq < count($rlt5);$rq++)
			{
				$xline = $rlt5[$rq];
				$id = $xline['ar_codigo'];
				//echo '#1-'.$rq.'# '.date("H:i:s").$id.cr();
				$data = $this->articles->le($id);
				$sx .= $this->rdfs->rdf_article($data);
				
				if (isset($data['authors']))
				{
					$wh = '(';
				for ($r=0;$r < count($data['authors']);$r++)
					{
						$line = $data['authors'][$r];
						if (strlen($wha) > 0) { $wha .= ' OR '; }
						$wha .= " autor_alias = '".$line['autor_alias']."' ";
					}
				$wh .= ')';
				} else {
					$wh = '';
				}
			}		
		if (strlen($wha) > 1) { $wha = '('.$wha.') AND ' ;}
		/******************************************************************* AUTHOR **********/
		$sql = "select * from brapci_autor WHERE ".$wha." (autor_alias = autor_codigo) order by autor_alias";

		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		for ($r=0;$r < count($rlt);$r++)
			{
				$line = $rlt[$r];
				$sx .= $this->rdfs->rdf_author($line);
			}		
		
		$data['content'] = $sx;
		echo $sx;
	}
	
	function author($id='') {
		header('Content-type: text/plain');
		$this->load->model('rdfs');
		$sx = $this->rdfs->rdf_namespace_brapci();
		
		/* Autores teste */
		$sql = "select * from brapci_autor where autor_codigo = '$id' limit 1";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		for ($r=0;$r < count($rlt);$r++)
			{
				$line = $rlt[$r];
				$sx .= $this->rdfs->rdf_author($line);
			}
		
		$data['content'] = $sx;
		echo $sx;
	}	

}
?>
