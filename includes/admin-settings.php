<?php

echo '<div class="wrap">';

//	handle image upload, if necessary
if (isset($_REQUEST["action"]) && $_REQUEST['action'] == 'wp_handle_upload')
    $this->caza_wp_cycle_handle_upload();

//	delete an image, if necessary
if (isset($_REQUEST['delete']))
    $this->caza_wp_cycle_delete_upload($_REQUEST['delete']);

//	the image management form
$this->caza_wp_cycle_images_admin();

//	the settings management form
$this->caza_wp_cycle_settings_admin();

echo '</div>';
