<h1><?php echo $data['l10n']->get('delete_info'); ?></h1>

<form action="" method="post">
  <input type="submit" class="delete" name="midcom_baseclasses_components_handler_crud_deleteok" value="<?php echo $data['l10n_midcom']->get('delete'); ?> " />
  <input type="submit" class="cancel" name="midcom_baseclasses_components_handler_crud_deletecancel" value="<?php echo $data['l10n_midcom']->get('cancel'); ?>" />
</form>

