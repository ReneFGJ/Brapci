<?php
/*
 Plugin name: Brapci - Wordpress Module
 Plugin url: http://www.brapci.inf.br
 Description: Sistema de Recuperação de Informação Brapci
 Version 0.17.11.19
 Author: Rene Faustino Gabriel Junior
 Author Uri: http://www.brapci.inf.br
 License: GPLv2 or Later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: wporg
 Domain Path: /languages
 */

/* definitions */
define("BRPCI_VERSION", "v0.17.11.18");
define("BRPCI_PLUGIN", "Brapci-SRI");
define("BRPCI_DIR", "/wp-content/plugins/Brapci/");
define("BRPCI_BOOTSTRAP_VERSION", "v3.3.7");

define("BRAPCI_FILE_SEARCH_DIR","d:/projeto/brapci/");
define("BRAPCI_SEARCH_LIMIT","10");
define("CR",chr(10).chr(13));
include "functions.php";

// CSS
wp_enqueue_style(BRPCI_PLUGIN, get_site_url() . BRPCI_DIR . ('css/style.css'), array(), BRPCI_VERSION, 'all');
wp_enqueue_style("bootstrap", get_site_url() . BRPCI_DIR . ('css/bootstrap.css'), array(), BRPCI_BOOTSTRAP_VERSION, 'all');
wp_enqueue_script("bootstrap", get_site_url() . BRPCI_DIR . ('js/bootstrap.js'), array(), BRPCI_BOOTSTRAP_VERSION, 'all');
wp_enqueue_style("brapci_css", get_site_url() . BRPCI_DIR . ('css/brapci_style.css'), array(), "v0.17.11.26", 'all');
/***************** REGISTRAR ACOES */
add_action('admin_menu', 'brapci_admin_menu');

/***************** REGISTRAR SHORTCODE */
add_shortcode('brapci_search', 'brapci_search_form');
add_shortcode('brapci_result', 'brapci_search_result');

add_action('init', 'myStartSession', 1);
function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

/**********************************************************************************/
/* Register a custom menu page ****************************************************/
/**********************************************************************************/
function brapci_admin_menu() {
    add_menu_page(__('Custom Menu Title', 'textdomain'), 'Brapci', 'manage_options', 'brpci_mymenu', 'bpci_admin_home', plugins_url('Brapci/img/icone_menu_r.png'), 6);

    add_submenu_page('brpci_mymenu', 'Configurações', 'Configurações', 'manage_options', 'bpci_admin_status', 'bpci_admin_status', '1');
    add_submenu_page('brpci_mymenu', 'Resultados', 'Resultados', 'manage_options', 'brpci_admin_templat', 'brpci_admin_templat', '1');
    //add_submenu_page('brpci_mymenu', 'Minhas buscas', 'Minhas Buscas', 'manage_options', 'brpci_admin_templat', 'brpci_admin_templat', '1');
    

    global $menu, $submenu;

}



/************************************************************************ AREA DO USUÁRIO *********/
    $img = get_site_url() . DMP_DIR . '/img/icone_menu_r.png';
    $page = 'index.php/brapci';
    //array_push($menu,array('Gestão de dados de Pesquisa','read',$page,$page,'menu-top menu-top-first menu-icon-dashboard','menu-dashboard','http://localhost/projeto/RDP-Brasil/wp-content/plugins/DMP-Wordpress/img/icone_menu_r.png'));  
    //add_submenu_page($page, 'DMP Templates', 'DMP Templates', 'manage_options', 'dmp_admin_templat', 'dmp_admin_templat', '1');
    //add_submenu_page($page, 'Knowledge ', 'Knowledge Area', 'manage_options', 'dmp_admin_group_members', 'dmp_admin_knowledge', '2');
   


?>