<?php
// Available request keys: datamanager, article, view_url, article_counter
$view = $data['datamanager']->get_content_html();
$view_counter = $data['article_counter'];
$article_count = $data['article_count'];
//var_dump($data['article']);
//die;
$qb = net_nehmer_comments_comment::new_query_builder();
$qb->add_constraint('objectguid', '=', $data['article']->guid);
$res = $qb->execute();
global $number_of_comments;
$number_of_comments = count($res);

unset($res);

$class_str = '';
if (function_exists('$_MIDCOM->metadata->get_object_classes()'))
{
    $class_str = $_MIDCOM->metadata->get_object_classes($data['article']);
}

if($view_counter == 0)
{
    $class_str = ' first';
}
elseif($view_counter == ($article_count-1))
{
    $class_str = ' last';
}

$published = "<abbr title=\"" . strftime('%Y-%m-%d', $data['article']->metadata->published) . "\">" . strftime('%Y-%m-%d', $data['article']->metadata->published) . "</abbr>";

if (   array_key_exists('comments_enable', $data)
    && array_key_exists('local_view_url', $data))
{
    $published .= " <a href=\"{$data['local_view_url']}#net_nehmer_comments_{$data['article']->guid}\">" . sprintf($data['l10n']->get('%s comments'), net_nehmer_comments_comment::count_by_objectguid($data['article']->guid)) . "</a>.";
}
?>

<div class="hentry counter_&(view_counter); &(class_str);" style="clear: left;">
    <h3 class="entry-title"><a href="&(data['view_url']);" rel="bookmark">&(view['title']:h);</a></h3>
    <p class="published">
        &(published:h);
            <?php
            if ($data['linked'])
            {
                echo $data['l10n']->get('to') ." <a href=\"{$data['node'][MIDCOM_NAV_FULLURL]}\">{$data['node'][MIDCOM_NAV_NAME]}</a>\n";
            }
            ?>
    </p>
    <?php if (array_key_exists('image', $view) && $view['image']) { ?>
        <div style="float: left; padding: 5px;">&(view['image']:h);</div>
    <?php 
    } 
    
    if (isset($view['abstract']))
    {
        ?>
        <p class="entry-summary">&(view['abstract']:h);</p>
        <?php
    }
    
    if ($data['index_fulltext'])
    {
        ?>
        <div class="entry-content">&(view['content']:h);</div>
        <?php
    }
    ?>
</div>
