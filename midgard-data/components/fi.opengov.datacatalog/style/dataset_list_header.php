<?php
/** 
 * Available data keys: handler_id, tags, filter
 */
?>

<h2><?php echo $data['l10n']->get('dataset_list');?></h2>

<?php
    switch ($data['handler_id'])
    {
        case 'view':
?>

<div class="dataset_list_info">
  <?php echo $data['l10n']->get('dataset_list_info');?>
</div>

<h2><?php echo $data['l10n']->get('dataset_latest_additions');?></h2>

<?php
?>
<?php
        break;
        case 'topic':
?>

<div class="dataset_list_info">
  <?php echo sprintf($data['l10n']->get('dataset_list_tags_info %s'), $data['tags']); ?>
</div>

<?php
        break;
        case 'open':
        case 'closed':
?>

<div class="dataset_list_info">
    <?php echo sprintf($data['l10n']->get('dataset_filtered_list %s'), $data['handler_id']); ?></h2>
</div>


<?php
        break;
    }
?>

<div class="dataset_list">
  <div class="header">
    <div class="description"><?php echo $data['l10n']->get('description'); ?></div>
    <div class="org"><?php echo $data['l10n']->get('organization'); ?></div>
    <div class="formats"><?php echo $data['l10n']->get('formats'); ?></div>
    <div class="open"><?php echo $data['l10n']->get('open'); ?></div>
  </div>
