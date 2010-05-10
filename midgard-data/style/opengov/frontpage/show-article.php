<?php
    // Available request keys: article, datamanager, edit_url, delete_url, create_urls
    $view = $data['view_article'];
?>

<h1>&(view['title']:h);</h1>

&(view['content']:h);

<?php
    $lang = $_MIDCOM->i18n->get_current_language();
    $_MIDCOM->dynamic_load('midcom-substyle-frontpage/blog/latest/2', array('cache_module_content_caching_strategy' => 'public'));
?>
