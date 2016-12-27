<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h3><?php echo $jnl_nome; ?></h3>
			<a href="<?php echo base_url('index.php//admin/issue_view/' . round($ar_edition) . '/' . checkpost_link(round($ar_edition))); ?>">
			<span class="middle">v. <?php echo $ed_vol . ', n.' . $ed_nr . ', ' . $ed_ano . $pages; ?></span>
			</a>
		</div>
		<div class="col-md-4">
			<br>
			BDOI: <?php echo $ar_bdoi; ?><BR>
			DOI: <?php echo $ar_doi; ?>
		</div>
	</div>
</br>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Descrição</a></li>
    <li role="presentation"><a href="#refer" aria-controls="refer" role="tab" data-toggle="tab">Referências</a></li>
    <li role="presentation"><a href="#marc" aria-controls="marc" role="tab" data-toggle="tab">MARC21</a></li>
    <li role="presentation"><a href="#rdf" aria-controls="rdf" role="tab" data-toggle="tab">RDF</a></li>
    <?php
	if (isset($tab_pdf) and strlen($tab_pdf) > 10) {
		echo '<li role="presentation" class="hidden-xs"><a href="#pdf" aria-controls="pdf" role="tab" data-toggle="tab">PDF</a></li>';
	}
    ?>
    
    <?php

	if (isset($_SESSION['email']) AND (strlen($_SESSION['email']) > 5)) {
		echo '<li role="presentation">
				<a href="#export" aria-controls="export" role="tab" data-toggle="tab">e-mail</a>
			</li>';
	}
    ?>    
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home"><?php echo $tab_descript; ?>
    	<br><br>
    	<div class="row">
    		<div class="col-md-12">
    		Como citar este artigo
    		<br>
    		<?php echo $reference; ?>
    		</div>
    	</div>
    	<br><br>    	
    </div>
    <div role="tabpanel" class="tab-pane" id="refer"><?php echo $tab_refer; ?></div>
    <div role="tabpanel" class="tab-pane" id="marc"><?php echo $tab_marc21; ?></div>
    <?php
	if (isset($tab_pdf) and (strlen($tab_pdf) > 10)) { 		
		?>    
    <div role="tabpanel" class="tab-pane hidden-xs" id="pdf">
    	<?php echo $tab_pdf; ?>
    	<div style="float: right;"><a href="<?php echo $link_pdf; ?>" id="download" class="link" target="new<?php echo $ar_codigo; ?>">download</a>&nbsp;</div>
    </div>
    <?php } ?>

    <div role="tabpanel" class="tab-pane hidden-xs" id="rdf">
    	<?php echo $tab_rdf; ?>
    	<div style="float: right;"><a href="<?php echo $link_rdf; ?>" id="download" class="link" target="new<?php echo $ar_codigo; ?>">download</a>&nbsp;</div>
    </div>

    <?php
	if (isset($_SESSION['email']) AND (strlen($_SESSION['email']) > 5)) {		
	?>    
   <div role="tabpanel" class="tab-pane" id="export">
   		<div class="btn btn-primary" onclick="newxy('<?php echo base_url('index.php/article/email/' . $ar_codigo);?>',300,300);">
    		enviar por e-mail para <?php echo $_SESSION['email']; ?>
    	</div>
    </div>
    <?php } ?>    
</div>
</div>
