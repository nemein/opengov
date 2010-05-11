<?php
/*
 * Available $data keys: dataset, organization, license, format, class
 */
    $dataset = $data['dataset'];
    $organization = $data['organization'];
    $license = $data['license'];
    $formats = $data['formats'];
    $open = $data['l10n']->get('yes');
    $class = $data['class'];
?>

<div class="dataset_item &(class);">
  <div class="description">
    <a class="title" href="/data/view/&(dataset.id);">&(dataset.title);</a>
    &(dataset.description);
  </div>
  <div class="org">
    &(organization['title']);
  </div>
  <div class="formats">
    <?php
        //var_dump($formats);
        if (count($formats))
        {
            $list = '';
            foreach ($formats as $format)
            {
                if ($format->url != '')
                {
                    $list .= '<a href="' . $format->url . '">' . $format->title . "</a>, \n";
                }
                else
                {
                    $list .= $format->title . ", \n";
                }
            }
            /* take away the last , */
            echo trim(substr($list, 0, -3));
        }
    ?>
  </div>
  <div class="open">
    &(open);
  </div>
</div>
