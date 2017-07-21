<?php
// This file is part of the Brapci Software.
//
// Copyright 2017, UFPR / UFRGS. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR / UFRGS, which
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
 * @date: 2017-07-15
 */

class m extends CI_controller {
    function __construct() {
        global $db_public;

        $db_public = 'brapci_publico.';
        parent::__construct();
        $this -> lang -> load("brapci", "portuguese");
        $this -> load -> library('form_validation');
        $this -> load -> database();
        $this -> load -> helper('form');
        $this -> load -> helper('form_sisdoc');
        $this -> load -> helper('url');
        $this -> load -> library('session');
        
        $this->load->model('rdf');

        date_default_timezone_set('America/Sao_Paulo');
    }

    function cab($data = array()) {
        $this -> load -> model('search');
        //$data['selected'] = $this->Search->selected();
        $data['selected'] = '0';
        $this -> load -> view('m/header/header');     
        $this -> load -> view('m/header/analytics.google.php');
        $this -> load -> view('m/header/topmenu', $data);        
    }
    
    function foot() {
        $this->load->view('m/header/foot');
    }
       

    function index() {
        $this->load->model('editions');
                      
        $this -> cab();
        
        $this -> load -> view('m/search/search');
        
        $data['dt1'] = $this->load->view('brapci/jumbo',null,true);
        $data['dt2'] = $this->editions->last_editions(10);
        $this->load->view('m/cms/home',$data);
        
        $this->foot();
    }
    
    function i($id='')
        {
            $id = sonumero($id);
            if (strlen($id) == 0)
                {
                    redirect(base_url('index.php/m'));
                }
            /* module */
            $this->load->model('editions');
            $this->load->model('journals');
            
            /* header */
            $this -> cab();
            $data = $this->editions->le($id);
            $data_jnl = $this->journals->le($data['id_jnl']);
            
            $this->load->view('m/publication/journal',$data_jnl);
            
        }

}
?>
