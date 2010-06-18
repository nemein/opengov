<?php
// Available request keys: article, datamanager, edit_url, delete_url, create_urls
$view = $data['view_article'];
$publish_time = $data['article']->metadata->published;
$published = strftime('%Y-%m-%d', $publish_time);
$permalink = $_MIDCOM->permalinks->create_permalink($data['article']->guid);
$prefix = $_MIDCOM->get_context_data(MIDCOM_CONTEXT_ANCHORPREFIX);
$dataset = new fi_opengov_datacatalog_dataset_dba($data['article']->get_parameter('net.nehmer.blog', 'dataset'));
$dataset_permalink = $_MIDCOM->permalinks->create_permalink($dataset->guid);
?>

<div class="hentry">
    <h1 class="headline">&(view['title']:h);</h1>

    <p class="published">&(published);</p>
    
    <?php
        if (   isset($dataset_permalink)
            && is_object($dataset))
        {
    ?>
    <p class="dataset"><?php echo $_MIDCOM->i18n->get_string('related_dataset', 'fi.opengov.datacatalog'); ?>: <a href="&(dataset_permalink);">&(dataset.title);</a></p>
    <?php
        }
    ?>

    <p class="excerpt">&(view['abstract']:h);</p>

    <div class="content">
        <?php if (array_key_exists('image', $view) && $view['image']) { ?>
            <div style="float: right; padding: 5px;">&(view['image']:h);</div>
        <?php } ?>

        &(view["content"]:h);
    </div>

    <div class="separator"></div>

    <p class="permalink" style="display: none;"><a href="&(permalink);" rel="bookmark" rev="canonical"><?php $data['l10n_midcom']->show('permalink'); ?></a></p>
    
    <?php
    $without_pipes = str_replace('|', '', $data['article']->extra3);
    if (! empty($without_pipes))
    {
        echo "<h2>{$data['l10n']->get('related stories')}</h2>\n";
        echo "<ul class=\"related\">\n";
        $relateds = explode('|', $data['article']->extra3);
        foreach ($relateds as $related)
        {
            if (empty($related))
            {
                continue;
            }

            $article = new midcom_db_article($related);
            if (   $article
                && $article->guid)
            {
                echo "<li><a href=\"" . $_MIDCOM->permalinks->create_permalink($article->guid) . "\">{$article->title}</a></li>\n";
            }
        }
        echo "</ul>\n";
    }
    
    if (array_key_exists('comments_url', $data))
    {
        $_MIDCOM->dynamic_load('midcom-substyle-dl_comments/' . $data['comments_url']);
    }
    ?>
    <p><a href="&(prefix);"><?php $data['l10n_midcom']->show('back'); ?></a></p>
</div>
