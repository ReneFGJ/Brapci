<?php
// This file is part of the Brapci Software.
//
// Copyright 2017, UFRGS. All rights reserved. You can redistribute it and/or modify
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
class laboratory extends CI_Controller {
    var $tabela_journals = 'brapci_journal';
    var $tabela_editions = 'brapci_edition';
    function __construct() {
        global $db_public;

        parent::__construct();
        $this -> lang -> load("app", "portuguese");
        $this -> load -> library('form_validation');
        $this -> load -> database();
        $this -> load -> helper('form');
        $this -> load -> helper('form_sisdoc');
        $this -> load -> helper('url');
        $this -> load -> helper('xml');
        $this -> load -> library('session');
        $this -> load -> library('Pdfparser');
        $this -> load -> library('tcpdf');
        $db_public = 'brapci_public.';
        date_default_timezone_set('America/Sao_Paulo');
    }

    function cab() {
        global $db_public;
        $db_public = 'brapci_public.';

        $this -> load -> model('Search');
        $this -> load -> model('users');
        $data = array();
        $data['title_page'] = 'Brapci :: Laboratory';
        $data['title'] = 'Brapci :: Laboratory';
        $this -> load -> view("header/cab", $data);
        $data['title'] = '';
        //$this -> users -> security();
    }

    function index() {
        $this -> cab();

        $parser = new Parser;
        $pdf = $parser -> parseFile('mypdf.pdf');
        $text = $pdf -> getText();
        echo $text;
        //all text from mypdf.pdf
    }

}
?>