<?php
function cab() {
    echo '<div class="container-fluid">';
    echo '<div class="row">';
    echo '<div class="div col-md-11" style="border-bottom: 1px solid #0000bb;">
                    <span style="font-size: 300%">BRAPCI</span>
                    <br>version ' . BRPCI_VERSION . '
              </div>';
    echo '<div class="col-md-1 text-right">
                    <img src="' . get_site_url() . BRPCI_DIR . 'img/logo-250x250.png" class="img-responsive img-fluid">
              </div>';
    echo '</div>';
    echo '</div>';
}

function bpci_admin_status($arg) {
    cab();

    echo '<div class="container-fluid">';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<h4>Status do Plugin</h4>';
    $comando = 'python --version';
    $rs = shell_exec($comando);
    echo 'Shell CMD (result): ' . $rs;

    echo '<h4>Arquivos de Busca</h4>';
    echo '<p>Diretório dos arquivos:<b>' . BRAPCI_FILE_SEARCH_DIR . '</b>';

    echo '<h4>Brapci</h4>';
    echo '<h5>[brapci_search]<h5>';
    echo '<p>Insere o formulário de busca no post</p>';
    echo '<h5>[brapci_result]</h5>';

    echo '</div>';
    echo '</div>';
    echo '</div>';

}

/************ search **********/
function pagination($id, $tot, $t = '') {
    $limit = BRAPCI_SEARCH_LIMIT;
    $sx .= '<nav aria-label="Page navigation example">
            <ul class="pagination">';
    $p = $tot/$limit;
    for ($r = 0; $r <= $p; $r++) {
        $ac = '';
        if ($r == ($id)) {
            $ac = 'active';
        }
        $sx .= '<li class="page-item ' . $ac . '"><a class="page-link" href="?q=' . ($r + 1) . '&dd1='.$t.'">' . ($r + 1) . '</a></li>';
    }
    $sx .= '</ul></nav>';
    $sx .= 'total ' . $tot;
    //$sx .= get_site_url();
    return ($sx);
}

function brapci_search_result() {
    $dir = BRAPCI_FILE_SEARCH_DIR;
    $limit = BRAPCI_SEARCH_LIMIT;  
    $q = $_GET['q'];
    switch($q)
        {
            case 'view':
                $ar = $_GET['cd'];  
                $sx = '<h1>View</h1>';
                return($sx);
                break;
        }
    
    $pg = 0;
    $sx = '<a name="result"></a>';
    $sx .= '<div class="container-fluid">';
    $sx .= '    <div clas="row">';
    $sx .= '        <div clas="col-md-12">';
    $sx .= '$pag';
    $sx .= '        <h3>Resultado</h3>';

    $ok = 0;
    if (isset($_POST['dd1'])) {
        $dd1 = $_POST['dd1'];
        $dd1 = AscII($dd1);
        $ok = 1;
        $offset = 0;
    } else {
        $ddd = $_GET['q'];
        if (strlen($ddd) > 0) {
            $offset = round($_GET['q']-1)*$limit;
            $pg = (round($_GET['q'])-1);
            
            $dd1 = $_SESSION['termo'];
            if (strlen($dd1) > 0)
                {
                    $ok = 1;
                }
        } else {
            $sx .= 'Falta paremetros ';
        }
    }
    $sx .= '        </div>';
    $sx .= '    </div>';
    $sx .= '</div>';
    $dd1 = strtolower($dd1);
    
    if ($ok == 1) {        
        $_SESSION['offset'] = $offset;
        $_SESSION['limit'] = $limit;
        $_SESSION['termo'] = $dd1;
        
        $comando = escapeshellcmd($dir . 'python/a.py ' . ($offset+1) . ' ' . $limit . ' ' . $dd1);
        $rs = shell_exec($comando);
        //echo '<pre>'.$rs.'</pre>';
        //echo '<pre>'.$comando.'</pre>';
        $rs = troca($rs, chr(13), ';');
        $rs = troca($rs, chr(10), ';');

        $ln = splitx(';', $rs);
        $js = '';
        $total = 0;

        for ($r = 0; $r < count($ln); $r++) {
            $lns = $ln[$r];
            $cmd = substr($lns, 0, strpos($lns, ']') + 1);
            switch($cmd) {
                case '[total]' :
                    $total = round(substr($lns, 7, 10));
                    break;
                case '[work]' :
                    $doc = substr($lns, 7, 10);
                    $nfile = $dir . '_search/' . substr($doc, 0, 7) . '/' . $doc . '.htm';
                    if (file($nfile)) {
                        $sx .= '<div class="container-fluid">'.CR;
                        $sx .= '    <div class="row">'.CR;
                        $sx .= '        <div class="col-md-12">'.CR;
                        $ff = fopen($nfile, 'r');
                        $fe = fread($ff, filesize($nfile));
                        fclose($ff);
                        /***************** url *************/
                        $fe = troca($fe,'$url/','?q=view&cd=');
                        $sx .= $fe;
                        $sx .= '        </div>'.CR;
                        $sx .= '    </div>'.CR;
                        $sx .= '</div>'.CR;
                        $sx .= '<script>'.CR;
                        $sx .= 'jQuery("#rr' . $doc . '").click(function() {
                                      jQuery("#rs' . $doc . '").toggle("slow");
                                    });'.CR;
                                  //
                        $sx .= '</script>'.CR;
                    } else {
                        $sx .= 'File Not Found ' . $nfile;
                    }
                    break;
            }
        }
    }
    if ($total > 0) {
        /* pagination */
        $sx = troca($sx, '$pag', pagination($pg, $total, $dd1));
    }
    $sx .= '
    <script>
    ' . $js . '
    </script>
    ';
    return ($sx);
}

function AscII($sq) {
    $sq = utf8_decode($sq);
    $sq = iconv("ISO-8859-1", "ASCII//TRANSLIT", $sq);
    $sq = troca($sq, "'", '');
    $sq = troca($sq, "`", '');
    $sq = troca($sq, "´", '');
    $sq = troca($sq, ".", ' ');
    $sq = troca($sq, ";", ' ');
    $sq = troca($sq, ",", ' ');
    $sq = troca($sq, '"', ' ');
    $sq = troca($sq, '?', ' ');
    $sq = troca($sq, '!', ' ');
    $sq = troca($sq, "~", '');
    $sq = troca($sq, "^", '');
    $sq = troca($sq, "/", ' ');
    $sq = troca($sq, '\\', ' ');
    $sq = troca($sq, ",", ' ');
    $sq = troca($sq, ")", ' ');
    $sq = troca($sq, "(", ' ');
    $sq = troca($sq, "  ", ' ');
    $sq = troca($sq, "  ", ' ');
    $sq = troca($sq, "  ", ' ');
    $sq = troca($sq, "£", '');
    return ($sq);
}

function brapci_search_form() {
    $chk = array('', '', '', '', '', '', '');
    $dd3 = $_POST['dd3'];
    $dd1 = $_POST['dd1'].$_GET['dd1'];
    if (strlen($dd3) > 0) {
        $chk[$dd3] = 'checked';
    } else {
        $chk[0] = 'checked';
    }
    $sx = ' <form method="post" id="bpci_search" action="?q=&dd1=">
                <div class="container-fluid">
                   <div class="row">
                       <div class="col-md-12">
                            informe o(s) termo(s) de busca
                       </div>
                       <div class="col-md-12">
                            <div class="input-group">
                              <input type="text" id="dd1" name="dd1" value="' . $dd1 . '" class="form-control" placeholder="Busca por..." style="border-color: #333333;">
                              <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="sbm">Pesquisar!</button>
                              </span>
                            </div>
                            <input type="radio" name="dd3" value=0 ' . $chk[0] . '>Todos os campos&nbsp;&nbsp;
                            <!---   
                            <input type="radio" name="dd3" value=1 ' . $chk[1] . '>Autor&nbsp;&nbsp;
                            <input type="radio" name="dd3" value=2 ' . $chk[2] . '>Título&nbsp;&nbsp;
                            <input type="radio" name="dd3" value=3 ' . $chk[3] . '>Palavras-chave&nbsp;&nbsp;
                            <input type="radio" name="dd3" value=4 ' . $chk[4] . '>Resumo&nbsp;&nbsp;
                            <input type="radio" name="dd3" value=5 ' . $chk[5] . '>Referências&nbsp;&nbsp;
                            ---->
                        </div>
                        </div>
                    </div>
                </form>';

    $sx .= '
            <script>
                jQuery("#sbm").click(function() {
                   id = jQuery("#dd1").val();
                   jQuery("#bpci_search").submit();
                });
            </script>';

    return ($sx);
}

function brpci_admin_templat($arg) {
    cab();
    echo $arg;

    $comando = escapeshellcmd('e:\lixo\py\a.py 0 25 "ciencia da informacao" bufrem');
    $rs = shell_exec($comando);
    echo '<pre>' . $rs . '</pre>';

}

function python_version() {

}

function splitx($in, $string) {
    $string .= $in;
    $vr = array();
    while (strpos(' ' . $string, $in)) {
        $vp = strpos($string, $in);
        $v4 = trim(substr($string, 0, $vp));
        $string = trim(substr($string, $vp + 1, strlen($string)));
        if (strlen($v4) > 0) { array_push($vr, $v4);
        }
    }
    return ($vr);
}
?>
