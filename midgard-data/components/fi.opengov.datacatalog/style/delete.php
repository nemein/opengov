<h1><?php echo sprintf($data['l10n']->get('delete %s'), $data['type']); ?></h1>

<?php
    switch ($data['type'])
    {
        case 'dataset':
            $data['handler_id'] = 'view';
            midcom_show_style('dataset_list_header');
            midcom_show_style('dataset_item_view');
            midcom_show_style('dataset_list_footer');
            break;
        default:
            midcom_show_style('info_list_header');
            midcom_show_style('info_item_view');
            midcom_show_style('info_list_footer');
    }
?>

<form action="" method="post">
  <input type="submit" class="delete" name="crud_delete" value="<?php echo $data['l10n_midcom']->get('delete'); ?> " />
  <input type="submit" class="cancel" name="crud_cancel" value="<?php echo $data['l10n_midcom']->get('cancel'); ?>" />
</form>

