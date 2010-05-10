<h2><?php echo $data['l10n']->get('dataset_list');?></h2>

<div class="dataset_list_info">
  <?php echo $data['l10n']->get('dataset_list_info');?>
</div>

<h2><?php echo $data['l10n']->get('dataset_latest_additions');?></h2>

<?php midcom_show_style('dataset_item_header'); ?>

<div class="dataset_list">
  <div class="header">
    <div class="description"><?php echo $data['l10n']->get('description'); ?></div>
    <div class="org"><?php echo $data['l10n']->get('organization'); ?></div>
    <div class="formats"><?php echo $data['l10n']->get('formats'); ?></div>
    <div class="open"><?php echo $data['l10n']->get('open'); ?></div>
  </div>
