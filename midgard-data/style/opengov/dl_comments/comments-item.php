<?php
// Available request data: comments, objectguid, comment, display_datamanager
//$data =& $_MIDCOM->get_custom_context_data('request_data');
$view = $data['display_datamanager']->get_content_html();
$created = $data['comment']->metadata->published;
$published = $view['author'] . ', ' . strftime('%Y-%m-%d', $created);
$url = $data['comment']->get_parameter('fi.opengov.datacatalog', 'comment_url');
$rating = '';

if ($data['comment']->rating > 0)
{
    $rating = ', ' . sprintf('rated %s', $data['comment']->rating);
}
?>

<div class="net_nehmer_comments_comment">
    <div class="published">&(published);</div>
    
    <div class="content">
        &(view['content']:h);
<?php
    if (isset($url))
    {
?>
    <div class="url">
        <?php echo $_MIDCOM->i18n->get_string('url', 'fi.opengov.datacatalog'); ?>:
        <a href="&(url);">&(url);</a>
    </div>
<?php
    }
?>
    </div>
    <div class="net_nehmer_comments_comment_toolbar">
        <?php echo $data['comment_toolbar']->render(); ?>
    </div>
</div>
