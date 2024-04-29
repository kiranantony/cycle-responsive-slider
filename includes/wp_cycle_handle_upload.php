<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//	this function handles the file upload,
//	resize/crop, and adds the image data to the db
$dest_path = ABSPATH . 'wp-content/uploads/resized/';
if (wp_mkdir_p($dest_path)) {
    
}
global $caza_wp_cycle_settings, $caza_wp_cycle_images;

//	upload the image
$upload = wp_handle_upload($_FILES['caza_wp_cycle'], 0);
//    var_dump($upload);
//    var_dump($dest_path);
//    exit();
//	extract the $upload array
extract($upload);

//	the URL of the directory the file was loaded in
$upload_dir_url = get_bloginfo('url') . '/wp-content/uploads/resized/';

//	get the image dimensions
list($width, $height) = getimagesize($file);

//	if the uploaded file is NOT an image
if (strpos($type, 'image') === FALSE) {
    unlink($file); // delete the file
    echo '<div class="error" id="message"><p>Sorry, but the file you uploaded does not seem to be a valid image. Please try again.</p></div>';
    return;
}

//	if the image doesn't meet the minimum width/height requirements ...
if ($width < $caza_wp_cycle_settings['img_width'] || $height < $caza_wp_cycle_settings['img_height']) {
    unlink($file); // delete the image
    echo '<div class="error" id="message"><p>Sorry, but this image does not meet the minimum height/width requirements. Please upload another image</p></div>';
    return;
}

//	if the image is larger than the width/height requirements, then scale it down.
if ($width > $caza_wp_cycle_settings['img_width'] || $height > $caza_wp_cycle_settings['img_height']) {
    //	resize the image

    $image = wp_get_image_editor($file);

    if (!is_wp_error($image)) {
        $image->resize($caza_wp_cycle_settings['img_width'], $caza_wp_cycle_settings['img_height'], true);
        $dest_file = $image->generate_filename('resized', $dest_path);
        $final_image = $image->save($dest_file);
    }
    if (isset($final_image)) {
        if (!is_wp_error($final_image)) {
            $resized_url = $upload_dir_url . basename($final_image['file']);
            //print_r($final_image);
            //	delete the original
            unlink($file);
            $file = $final_image['path'];
            $url = $resized_url;
        }
    }
}

//	make the thumbnail
$thumb_height = round((100 * $caza_wp_cycle_settings['img_height']) / $caza_wp_cycle_settings['img_width']);
if (isset($upload['file'])) {

    $thumbnail = wp_get_image_editor($file);

    if (!is_wp_error($thumbnail)) {
        $thumbnail->resize(100, $thumb_height, true);
        $dest_thumb = $thumbnail->generate_filename('thumb', $dest_path);
        $final_thumbnail = $thumbnail->save($dest_thumb);
    }
    if (isset($final_thumbnail)) {
        if (!is_wp_error($final_thumbnail)) {
            $thumbnail_url = $upload_dir_url . basename($final_thumbnail['file']);
            $thumbnail = $final_thumbnail['path'];
        }
    } else {
        $thumbnail_url = "";
        $thumbnail = "";
    }
}

//	use the timestamp as the array key and id
$time = date('YmdHis');

//	add the image data to the array
// UPDATE April 2011 by Chris Grab - added caza_wp_cycle_image_caption to the array

$caza_wp_cycle_images[$time] = array(
    'id' => $time,
    'file' => $file,
    'file_url' => $url,
    'thumbnail' => $thumbnail,
    'thumbnail_url' => $thumbnail_url,
    'image_links_to' => '',
    'caza_wp_cycle_image_caption' => ''
);


//	add the image information to the database
$caza_wp_cycle_images['update'] = 'Added';
update_option('caza_wp_cycle_images', $caza_wp_cycle_images);
