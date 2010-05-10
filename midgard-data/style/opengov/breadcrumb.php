<div class="breadcrumb">
<?php
  if (strpos($_MIDCOM->metadata->get_page_class(), 'frontpage') === false)
  {
    $nap = new midcom_helper_nav();
    echo $nap->get_breadcrumb_line();
  }
?>
</div>

        
