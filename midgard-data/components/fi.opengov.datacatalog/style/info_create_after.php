<script type="text/javascript">
<?php
    if ($data['action'] != 'cancel')
    {
        echo "window.parent.add_item({$data['jsdata']});";
    }
    echo "var handler='" . $data['handler'] . "';\n";
?>
    window.parent.jQuery('#' + handler + '_chooser_widget_creation_dialog').hide();
    window.parent.jQuery('#' + handler + '_chooser_widget_creation_dialog div.chooser_widget_creation_dialog_content_holder').empty();
</script>
