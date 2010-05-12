<h1><?php echo $data['l10n']->get('create_dataset_suggestion'); ?></h1>

<div class="suggestion_info"><?php echo $data['l10n']->get('create_suggestion_info'); ?></div>

<?php
    if(isset($data['controller']))
    {
        $data['controller']->display_form();
    }
?>
