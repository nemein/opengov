<?php
/*
 * Available $data keys: dataset, organization, license, format
 */
    $dataset = $data['dataset'];
    $organization = $data['organization'];
    $license = $data['license'];
    $formats = $data['formats'];
    $tags = $data['tags'];
    $open = $data['l10n']->get('yes');
?>

<h2>&(dataset.title);</h2>

<div class="dataset_item_detail">
  <div class="description">
    &(dataset.description);
  </div>
  <div class="org">
    <label><?php echo $data['l10n']->get('organization'); ?></label>
    &(organization['title']);
  </div>
  <div class="formats">
    <label><?php echo $data['l10n']->get('formats'); ?></label>
    <?php
        if (count($formats))
        {
            $list = '';
            foreach ($formats as $format)
            {
                if ($format->url != '')
                {
                    $list .= '<a href="' . $format->url . '">' . $format->title . "</a><br/>\n";
                }
                else
                {
                    $list .= $format->title . "<br/>\n";
                }
            }
            echo $list;
        }
    ?>
  </div>
  <div class="license">
    <label><?php echo $data['l10n']->get('license'); ?></label>
    <span class="&(license['type']);">&(license['title']);</span>
  </div>
  <div class="url">
    <label><?php echo $data['l10n']->get('additional_info'); ?></label>
    <a href="&(dataset.url);">&(dataset.url);</a>
  </div>
<?php
    if (   isset($tags)
        && count($tags))
    {
?>
  <div class="tags">
    <label><?php echo $data['l10n']->get('tags'); ?></label>
    <ul>
<?php
        foreach ($tags as $tag => $value)
        {
?>
            <li><a href="/data/topic/&(tag);">&(tag);</a></li>
<?php
        }
?>
    </ul>
  </div>
<?php
    }
    if (array_key_exists('comments_url', $data))
    {
?>
  <div class="separator"></div>
<?php
        $_MIDCOM->dynamic_load($data['comments_url']);
    }
?>

</div>
