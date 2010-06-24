<?php
/*
 * Available $data keys: suggestion
 */
    $suggestion = $data['suggestion'];
?>

<h2>&(suggestion.title);</h2>

<div class="dataset_item_detail">
  <div class="org">
    <label><?php echo ucfirst($data['l10n']->get('organization')); ?></label>
    &(suggestion.organization);
  </div>
  <div class="description">
    &(suggestion.description);
  </div>
  <div class="url">
    <label><?php echo $data['l10n']->get('additional_info'); ?></label>
    <a href="&(suggestion.url);">&(suggestion.url);</a>
  </div>
  <div class="submitter">
    <label><?php echo $data['l10n']->get('submitter'); ?></label>
    &(suggestion.submitter);
  </div>
  <div class="email">
    <label><?php echo $data['l10n']->get('email'); ?></label>
    &(suggestion.email);
  </div>
</div>
