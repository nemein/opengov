<?php
    // Available request keys: article, datamanager, edit_url, delete_url, create_urls
    $view = $data['view_article'];
?>

<h1>&(view['title']:h);</h1>

&(view['content']:h);

<div class="separator"></div>

<?php
    global $bloginfo;

    /* the index-item style will set $number_of_comments */
    $_MIDCOM->dynamic_load('midcom-substyle-dl_frontpage/blog/latest/1', array('cache_module_content_caching_strategy' => 'public'));

    $url = $bloginfo['url'];
    $comments = $bloginfo['comments'];

    if (   isset($bloginfo['url'])
        && isset($bloginfo['comments']))
    {
?>

<div class="discuss">
    <a href="&(url);"><?php echo sprintf($_MIDCOM->i18n->get_string('discuss %s comment', 'fi.opengov.datacatalog'), $comments); ?></a>
</div>

<?php
    }
?>
