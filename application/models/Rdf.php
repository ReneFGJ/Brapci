<?php
class rdf extends CI_model
    {
        function link_issue($data)
            {
                $id = $data['id_ed'];
                $link = base_url('index.php/m/i/'.$id.'#'.trim($data['jnl_nome']));
                return($link);
            } 
    }
?>
