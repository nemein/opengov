<?php
    /*
     * Available $data keys:
     *  info, type, class, organization_information, organization_address, organization_contact
     *  license_type
     */
    $info = $data['info'];
    $class = $data['class'];    
?>

<div class="info_item &(class);">
    <div class="title">
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

    <div class="information">&(organization_information);</div>
    <div class="address">&(organization_address);</div>
    <div class="contact">&(organization_contact);</div>
    
    <?php
                break;
            case 'license':
                $license_type = $data['license_type'];
    ?>

    <div class="type">&(license_type);</div>

    <?php
                break;
            case 'format':
                break;
        } //switch
  ?>
</div>
