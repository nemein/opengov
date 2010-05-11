<?php
    // Generate dynamic navigation
    $_MIDCOM->componentloader->load('fi.protie.navigation');
    $navi = new fi_protie_navigation();
    $navi->list_leaves = false;
    $navi->follow_selected = true;
    $navi->url_name_to_class = true;
    $navi->list_levels = 1;

    $search = $_MIDCOM->i18n->get_string('search', 'fi.opengov.datacatalog');
?>

<div class="topmenu robots-nocontent" role="navigation" id="nav">
<?php
    $navi->draw();
?>
    <div class="search" role="search">
        <form action="/search/" id="cse-search-box">
            <!-- Please enter info about your own search provider here -->
            <div class="searchform">
                <input name="cof" value="FORID:9" type="hidden">
                <input name="ie" value="UTF-8" type="hidden">
                <label for="q" class="outside">&(search);</label>
                <input style="background: \url(&quot;http://www.google.com/cse/intl/en/images/google_custom_search_watermark.gif&quot;)\ no-repeat scroll left center rgb(255, 255, 255);" accesskey="4" name="q" id="q" size="30" class="query" type="text">
                <input name="sa" value="&(search);" type="submit">
            </div>
            <input value="opengov.fi/" name="siteurl" type="hidden">
        </form>
        <script type="text/javascript" src="/style/js/brand.js"></script>
    </div>
</div>

<(breadcrumb)>

