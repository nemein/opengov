<?php
/** 
 * Available data keys: handler_id, tags, filter
 */
?>

<h2><?php echo $data['l10n']->get('dataset_list');?></h2>

<?php
    switch ($data['handler_id'])
    {
        case 'topic':
?>

<div class="dataset_list_info">
<?php
            echo sprintf($data['l10n']->get('dataset_list_tags_info %s'), $data['tags']);
?>
</div>

<?php
            break;
        case 'open':
        case 'closed':
?>

<div class="dataset_list_info">
<?php
            echo sprintf($data['l10n']->get('dataset_filtered_list %s'), $data['handler_id']);
?>
</div>

<?php
            break;
        default:
?>

<div class="dataset_list_info">
<?php
            echo $data['l10n']->get('dataset_list_info');
?>
</div>

<h2>
<?php
            echo $data['l10n']->get('dataset_latest_additions');
?>
</h2>

<?php
    } //switch
?>
