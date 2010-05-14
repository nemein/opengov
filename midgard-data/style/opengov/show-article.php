<?php
    // Available request keys: article, datamanager, edit_url, delete_url, create_urls
    $view = $data['view_article'];
?>

<h1>&(view['title']:h);</h1>

<div class="yui-u first">
    <div id="content">
    &(view['content']:h);
    </div>
</div>
