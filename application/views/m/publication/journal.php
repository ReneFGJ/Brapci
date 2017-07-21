<div class="container">
    <div clas="row tmarg5">
        <div class="col-md-1"><img src="<?php echo $logo; ?>" class="img-responsive"></div>
        <div class="col-md-9">Dados</br>
               <span class="big"><b><?php echo $jnl_nome; ?></b></span>
         </div>
        <div class="col-md-2">ISSN:</br>
            <span class="big"><b>
                <?php echo $jnl_issn_impresso; ?></br>
                <?php echo $jnl_issn_eletronico; ?>
            </b></span>
        </div>
    </div>
    </br></br>
    <div clas="row tmarg5">
        <div class="col-md-2 col-md-offset-1"><?php echo msg('publication_started');?></br>
               <span class="big"><?php echo $desde . ' ('.$anos .' '.msg('years').')'; ?></span>
         </div>
        <div class="col-md-2"><?php echo msg('situation');?></br>
               <span class="big"><?php echo msg('journal_status_'.$jnl_status); ?></span>
         </div>
        <div class="col-md-2"><?php echo msg('periodicidade');?></br>
               <span class="big"><?php echo $jnl_periodicidade; ?></span>
         </div>         
    </div>  
    </br></br>
    <div clas="row">
        <div class="col-md-11 col-md-offset-1"><?php echo msg('url');?></br>
               <span class="big"><?php echo $jnl_url; ?></span>
         </div>         
    </div>  
</div>
