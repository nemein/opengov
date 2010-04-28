<?php
/*
// Generate dynamic navigation
$_MIDCOM->componentloader->load('fi.protie.navigation');
$navi = new fi_protie_navigation();
$navi->list_leaves = false;
$navi->follow_selected = true;
$navi->url_name_to_class = true;
$navi->list_levels = 1;
$navi->draw();
*/
?>
<div class="topmenu robots-nocontent" role="navigation" id="nav">
                        <ul>
                            <li class="blog">
                                <a href="/fi/" class="selected">Blog
                                </a>
                            </li>
                            <li class="data">
                                <a href="/fi/data/">Data
                                </a>
                            </li>
                            <li class="community">
                                <a href="http://opengov.fi/page/">Mallisivu
                                </a>
                            </li>
                        </ul>
                        <div class="search" role="search">
                            <form action="/search/" id="cse-search-box">
                                <!-- Please enter info about your own search provider here -->
                                <div class="searchform">
                                    <input name="cof" value="FORID:9" type="hidden">
                                    <input name="ie" value="UTF-8" type="hidden">
                                    <label for="q" class="outside">Etsi
                                    </label>
                                    <input style="background: \url(&quot;http://www.google.com/cse/intl/en/images/google_custom_search_watermark.gif&quot;)\ no-repeat scroll left center rgb(255, 255, 255);" accesskey="4" name="q" id="q" size="30" class="query" type="text">
                                    <input name="sa" value="Hae" type="submit">
                                </div>
                                <input value="opengov.fi/" name="siteurl" type="hidden">
                            </form>
<script type="text/javascript" src="/style/js/brand.js"></script>
                        </div>
                    </div>
                    <p class="breadcrumb robots-nocontent">
                        <span>Olet tässä:
                        </span>
                        <a href="http://opengov.fi/">Etusivu
                        </a> 
                    </p>