<h1><?php echo sprintf($data['l10n']->get('create %s'), $data['type']); ?></h1>

<?php
    if(isset($data['controller']))
    {
        $data['controller']->display_form();
    }
?>
