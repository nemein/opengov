<?php
    $free = fi_opengov_datacatalog_dataset_dba::get_number_of_datasets('free');
    $non_free = fi_opengov_datacatalog_dataset_dba::get_number_of_datasets('non-free');
    $all = $free + $non_free;
    $percent = round($free / $all) * 100;
?>

<div id="sidebar">
    <a name="sidebar_target"></a>
    <div class="datastatus">
        <div class="datapercent">
            &(percent);<span class="percent">%</span>
        </div>
        <div class="statustext">
            Tämänhetkinen tilanne: &(all); dataseteistä vain 
            <a href="http://opengov.fi/data/open/"> &(free); ovat avoimia</a>.
        </div>
        <div class="clear"></div>
    </div>
    <h2>Myös sinä voit auttaa!</h2>
    <p>
        Tiedätkö dataseteistä joita katalogissa ei ole? 
        <a href="http://opengov.fi/data/suggest/">Ehdota datasettiä</a> ja koitamme lisätä sen niin pian kuin mahdollista 
    </p>
    <p>
        <a href="http://opengov.fi/data/suggest/">
            <img src="/style/img/btn_suggest_fi.png" alt="Ehdota \datasettiä">
        </a>
    </p>
    <h2>Työskenteletkö valtion virastossa tai organisaatiossa?</h2>
    <p>
        Lue lisää 
        <a href="http://www.w3.org/DesignIssues/GovData.html">valtion tietojen julkaisemisesta verkossa.</a>
    </p>
    <div class="comments latest">
        <h2>Uusimmat kommentit</h2>
        <?php
            /* the index-item style will set $number_of_comments */
            $_MIDCOM->dynamic_load('midcom-substyle-comments/blog/comments/latest/10', array('cache_module_content_caching_strategy' => 'public'));
        ?>
    </div>
</div>
