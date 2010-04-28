<?php
  // Generate dynamic navigation
  $_MIDCOM->componentloader->load('fi.protie.navigation');
  $navi = new fi_protie_navigation();
  $navi->list_leaves = false;
  $navi->follow_selected = true;
  $navi->url_name_to_class = true;
  $navi->list_levels = 1;
  $navi->draw();
?>

