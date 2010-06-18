<div class="info_list">
  <div class="header">
    <div class="title"><?php echo $data['l10n']->get('info_title'); ?></div>
    <div class="url"><?php echo $data['l10n']->get('url'); ?></div>
    <?php
        switch ($data['type'])
        {
            case 'organization':
    ?>

    <div class="information"><?php echo $data['l10n']->get('info_organization_information'); ?></div>
    
    <?php
                break;
            case 'license':
    ?>

    <div class="type"><?php echo $data['l10n']->get('info_license_type'); ?></div>

    <?php
                break;
            case 'format':
                break;
        }
    ?>
  </div>
