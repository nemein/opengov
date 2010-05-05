<h1><?php echo $data['l10n']->get('create_info'); ?></h1>

<?php
    if(isset($data['controller']))
    {
        $data['controller']->display_form();
    }
?>
