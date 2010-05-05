<?php
/*
 * Available $data keys: $info
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
    ?>

    <div class="information">&(info.information);</div>
    <div class="address">&(info.address);</div>
    <div class="contact">&(info.contact);</div>
    
    <?php
                break;
            case 'license':
    ?>

    <div class="type">&(info.type);</div>

    <?php
                break;
            case 'format':
                break;
        } //switch
  ?>
</div>
