<h2><?php echo $data['l10n']->get('info_list');?></h2>

<div class="info_list_info">
  <?php echo $data['l10n']->get('info_list_info');?>
</div>

<div class="info_list">
  <div class="header">
    <div class="title"><?php echo $data['l10n']->get('info_title'); ?></div>
    <?php
        switch ($data['type'])
        {
            case 'organization':
    ?>

    <div class="information"><?php echo $data['l10n']->get('info_organization_information'); ?></div>
    <div class="address"><?php echo $data['l10n']->get('info_organization_address'); ?></div>
    <div class="contact"><?php echo $data['l10n']->get('info_organization_contact'); ?></div>
    
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
