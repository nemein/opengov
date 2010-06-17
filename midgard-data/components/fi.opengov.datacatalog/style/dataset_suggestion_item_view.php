<?php
/*
 * Available $data keys: suggestion, class
 */
    $suggestion = $data['suggestion'];
    $class = $data['class'];
?>

<div class="dataset_suggestion_item &(class);">
  <div class="description">
    <a class="title" href="/data/suggestion/view/&(suggestion.guid);">&(suggestion.title);</a>
    &(suggestion.description);
  </div>
  <div class="org">
    &(suggestion.organization);
  </div>
  <div class="url">
    <a href="&(suggestion.url);">&(suggestion.url);</a>
  </div>
</div>
