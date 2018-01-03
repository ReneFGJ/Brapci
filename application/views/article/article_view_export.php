<hr>
<div class="css_ar_id">
    <?php echo $ISSN;?>
</div>
<div class="css_ar_title"><?php echo $ar_titulo_1; ?></div>
<div class="css_ar_title_alt"><?php echo $ar_titulo_2; ?></div>
<div class="css_ar_author"><?php echo $Author_Analytic;?></div>

<div class="css_ar_journal"><?php echo $Journal_Title; ?></div>
<div class="css_ar_doi"><?php echo $ar_doi; ?></div>
<div class="css_ar_issue">
    <?php
    $jn = '<b>'.$Journal_Title.'</b>';
    if (strlen($ar_local) > 0)
        {
            $jn .= ', '.$ar_local;
        }
    if (strlen($Volume_ID) > 0) {
        $jn .= ', '.$Volume_ID;
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
</div>

<div class="css_ar_abstract">
    <?php echo $ar_resumo_1; ?>
    <br><?php echo '<b>' . msg('keyword_' . $Idioma) . '</b>'; ?>: <?php echo $ar_keyword_1; ?>.
</div>

<div class="css_ar_abstract">
    <?php echo $ar_resumo_2; ?>
    <br><?php echo '<b>' . msg('keyword_' . $Idioma) . '</b>'; ?>: <?php echo $ar_keyword_2; ?>.
</div>

<div class="cass_legend">
    Como citar este artigo:<br>
    <?php echo $Author_Analytic;?>. 
    <?php echo $ar_titulo_1;?>.
    <?php echo $jn;?>
</div>
