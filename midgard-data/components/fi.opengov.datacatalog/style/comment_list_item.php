<?php
/* Available request data: comment, comment_url, object */
$created = $data['comment']->metadata->published;
$published = $data['comment']->author . ', ' . strftime('%Y-%m-%d', $created);
$title = $data['object']->title;
$permalink = $data['object_permalink'];
?>
<div class="commented">
    <?php echo $data['comment']->author . ' ' . $data['l10n']->get('commented_on'); ?>
    <a href="&(permalink);">&(title);</a>
</div>

