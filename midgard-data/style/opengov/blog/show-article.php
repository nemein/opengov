<?php
    // Available request keys: article, datamanager, edit_url, delete_url, create_urls
    $view = $data['view_article'];
    $title = $_MIDCOM->i18n->get_string('blog_title', 'fi.opengov.datacatalog');
?>

<h1>&(title);</h1>

&(view['content']:h);
