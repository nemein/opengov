<?php
    $free = fi_opengov_datacatalog_dataset_dba::get_number_of_datasets('free');
    $non_free = fi_opengov_datacatalog_dataset_dba::get_number_of_datasets('non-free');
    $all = $free + $non_free;
    $percent = round($free / $all * 100);
?>

<div id="sidebar">
    <a name="sidebar_target"></a>
    <div class="datastatus">
        <div class="datapercent">
            &(percent);<span class="percent">%</span>
        </div>
        <div class="statustext">
            Tämänhetkinen tilanne: &(all); dataseteistä vain 
            <a href="/data"> &(free); ovat avoimia</a>.
        </div>
        <div class="clear"></div>
    </div>
    <h2>Myös sinä voit auttaa!</h2>
    <p>
        Tiedätkö dataseteistä joita katalogissa ei ole? 
        <a href="/data/suggest/">Ehdota datasettiä</a> ja koitamme lisätä sen niin pian kuin mahdollista 
    </p>
    <p>
        <a href="/data/suggest/">
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
            /* load the last 10 comments submitted to blogs */
            $_MIDCOM->dynamic_load('midcom-substyle-dl_comments/data/comments/all', array('cache_module_content_caching_strategy' => 'public'));
        ?>
    </div>
</div>
