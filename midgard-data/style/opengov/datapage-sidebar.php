<div id="sidebar">
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
        Lue lisää <a href="http://www.w3.org/DesignIssues/GovData.html">valtion tietojen julkaisemisesta verkossa.</a>
    </p>

    <?php
        $_MIDCOM->dynamic_load('midcom-substyle-/data/tagcloud/', array('cache_module_content_caching_strategy' => 'public'));
    ?>

    <div class="separator"></div>

    <p>
        <a href="/data/open">Näytä avoimet datasetit</a> (missä avointa lisenssiä on käytetty ja data on saatavilla avoimessa formaatissa).
    </p>

    <div class="separator"></div>

    <p>
        <a href="/data">Kaikki datasetit</a> yhdellä sivulla.
    </p>
</div>
