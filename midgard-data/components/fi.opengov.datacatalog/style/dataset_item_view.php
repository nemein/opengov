<?php
/*
 * Available $data keys: dataset, organization, license, format, class
 */
    $dataset = $data['dataset'];
    $organization = $data['organization'];
    //$license = $data['license'];
    //$formats = $data['formats'];
    $open = $data['l10n']->get('yes');
    $class = $data['class'];
?>

<div class="dataset_item &(class);">
  <div class="description">
    <a class="title" href="/data/&(dataset.id);">&(dataset.title);</a>
    &(dataset.description);
  </div>
  <div class="org">
    &(organization['title']);
  </div>
  <div class="format">
    <?php
        foreach ($formats as $format)
        {
            $list = '<a href="">' . "</a>, \n";
        }
        /* take away the last , */
        echo substr($list, 0, -1);
    ?>
  </div>
  <div class="open">
    &(open);
  </div>
</div>
