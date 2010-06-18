<?php
    /*
     * Available $data keys:
     *  info, type, class, organization_information, organization_address, organization_contact
     *  license_type
     */
    $info = $data['info'];
    $class = $data['class'];
    $viewlink = '../' . $info->guid;
?>

<div class="info_item &(class);">
    <div class="title">
        <a class="title" href="&(viewlink);">&(info.title);</a>
    </div>

    <div class="url">
    <?php
        if ($info->url != '')
        {
    ?>
    <a class="title" href="&(info.url);">&(info.url);</a>
    <?php
        }
        else
        {
    ?>
    <span class="title">-</span>
    <?php
        }
    ?>
    </div>

    <?php
        switch ($data['type'])
        {
            case 'organization':
                $organization_information = $data['organization_information'];
    ?>

    <div class="information">&(organization_information);</div>
    
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
