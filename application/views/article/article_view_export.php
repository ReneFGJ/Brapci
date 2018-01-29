<?php
    $jn = '<b>' . $Journal_Title . '</b>';
    if (strlen($ar_local) > 0) {
        $jn .= ', ' . $ar_local;
    }
    if (strlen($Volume_ID) > 0) {
        $jn .= ', ' . $Volume_ID;
    }
    if (strlen($Issue_ID) > 0) {
        $jn .= ', ' . $Issue_ID;
    }
    if (strlen($Pages) > 0) {
        $jn .= ', ' . $Pages;
    }
    if (strlen($ar_ano) > 0) {
        $jn .= ', ' . $ar_ano;
    }
    if (strlen($Pages . $ar_ano . $Issue_ID . $Volume_ID) > 0) {
        $jn .= '.';
    }
?>    
<br>
    <div class="row">
        <div class="col-md-2 css_ar_type">
            <?php echo $ar_secao; ?>
        </div>
        <?php if (strlen($ISSN) > 0) { ?>    
            <div class="col-md-10 css_ar_issn text-right">
                <?php echo 'ISSN: ' . $ISSN; ?>
            </div>
        <?php } ?>        
    </div>
    
    <div class="row">
        <div class="col-md-12 css_ar_journal text-center">
            <?php echo $Journal_Title.', '.$jn; ?>
        </div>
    </div>    

<?php 
    // echo $ar_doi; 
?>
<div class="css_ar_separate"></div>

<div class="css_ar_title"><?php echo $ar_titulo_1; ?></div>
<div class="css_ar_title_alt"><?php echo $ar_titulo_2; ?></div>
<div class="css_ar_author"><?php echo $Author_Analytic; ?></div>


<div class="css_ar_issue">
    <?php

    ?>
</div>

<div class="css_ar_abstract">
    <?php echo $ar_resumo_1; ?>
    <div class="css_ar_keyword">
        <?php echo '<b>' . msg('keyword_' . $Idioma) . '</b>'; ?>: <?php echo $ar_keyword_1; ?>.
    </div>
</div>

<div class="css_ar_abstract">
    <?php echo $ar_resumo_2; ?>
    <br><?php echo '<b>' . msg('keyword_' . $Idioma) . '</b>'; ?>: <?php echo $ar_keyword_2; ?>.
</div>

<div class="css_legend">
    <span class="css_legend_title">Como citar este artigo:</span><br>
    <?php echo $Author_Analytic; ?>. 
    <?php echo $ar_titulo_1; ?>.
    <?php echo $jn; ?>
</div>
