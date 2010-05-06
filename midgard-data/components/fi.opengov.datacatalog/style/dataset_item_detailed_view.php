<?php
/*
 * Available $data keys: dataset, organization, license, format
 */
    $dataset = $data['dataset'];
    $organization = $data['organization'];
    $license = $data['license'];
    $formats = $data['formats'];
    $open = $data['l10n']->get('yes');
?>

<h2>&(dataset.title);</h2>

<div class="dataset_item_detail">
  <div class="description">
    &(dataset.description);
  </div>
  <div class="org">
    <label><?php echo $data['l10n']->get('dataset_organization'); ?></label>
    &(organization['title']);
  </div>
  <div class="formats">
    <label><?php echo $data['l10n']->get('dataset_formats'); ?></label>
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
    <label><?php echo $data['l10n']->get('dataset_license'); ?></label>
    <span class="&(license['type']);">&(license['title']);</span>
  </div>
  <div class="url">
    <label><?php echo $data['l10n']->get('dataset_additional_info'); ?></label>
    <a href="&(dataset.url);">&(dataset.url);</a>
  </div>
</div>
