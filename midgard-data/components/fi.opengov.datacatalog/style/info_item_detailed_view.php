<?php
/*
 * Available $data keys:
 * info, type, organization_information, organization_address, organization_contact, license_type
 */
    $info = $data['info'];
?>

<h2><?php echo ucfirst($data['type']) . ': ' . $info->title; ?></h2>


<div class="info_item_detail">
    <div class="title">
    <label><?php echo $data['l10n']->get('info_title'); ?></label>
    <?php
        if ($info->url != '')
        {
    ?>
    <a class="title" href="&(info.url);">&(info.title);</a>
    <?php
        }
        else
        {
    ?>
    <span class="title">&(info.title);</span>
    <?php
        }
    ?>
    </div>
    <?php
        switch ($data['type'])
        {
            case 'organization':
                $organization_information = $data['organization_information'];
                $organization_address = $data['organization_address'];
                $organization_contact = $data['organization_contact'];
    ?>

    <div class="information">
        <label><?php echo $data['l10n']->get('info_organization_information'); ?></label>
        &(organization_information);
    </div>
    <div class="address">
        <label><?php echo $data['l10n']->get('info_organization_address'); ?></label>
        &(organization_address);
    </div>
    <div class="contact">
        <label><?php echo $data['l10n']->get('info_organization_contact'); ?></label>
        &(organization_contact);
    </div>
    
    <?php
                break;
            case 'license':
                $license_type = $data['license_type'];
    ?>

    <div class="type">
        <label><?php echo $data['l10n']->get('info_license_type'); ?></label>
        &(license_type);
    </div>

    <?php
                break;
            case 'format':
                break;
        } //switch
  ?>
</div>
