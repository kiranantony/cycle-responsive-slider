<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php global $caza_wp_cycle_images; ?>
<?php $this->caza_wp_cycle_images_update_check(); ?>
<h2><?php _e('WP-Cycle Plus Images', 'caza_wp_cycle'); ?></h2>

<table class="form-table">
    <tr valign="top"><th scope="row">Upload New Image</th>
        <td>
            <form enctype="multipart/form-data" method="post" action="?page=wp-cycle">
                <input type="hidden" name="post_id" id="post_id" value="0" />
                <input type="hidden" name="action" id="action" value="wp_handle_upload" />

                <label for="caza_wp_cycle">Select a File: </label>
                <input type="file" name="caza_wp_cycle" id="caza_wp_cycle" />
                <input type="submit" class="button-primary" name="html-upload" value="Upload" />
            </form>
        </td>
    </tr>
</table><br />

<?php if (!empty($caza_wp_cycle_images)) : ?>
    <form method="post" action="options.php">
        <table class="widefat wp-cycle-image-list" cellspacing="0">
            <thead>
                <tr>
                    <th class="order"></th>
                    <th scope="col" class="column-slug">Image</th>
                    <th scope="col">Image Links To</th>
                    <th scope="col">Image Caption</th>
                    <th scope="col" class="column-slug">Actions</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th class="order"></th>
                    <th scope="col" class="column-slug">Image</th>
                    <th scope="col">Image Links To</th>
                    <th scope="col">Image Caption</th>
                    <th scope="col" class="column-slug">Actions</th>
                </tr>
            </tfoot>

            <tbody class="ui-sortable">


    <?php settings_fields('caza_wp_cycle_images'); ?>
    <?php
    $i = 0;
    foreach ((array) $caza_wp_cycle_images as $image => $data) : $i++;
        ?>
                    <tr class="row">
                        <td class="order" style="width: 16px;"><?php echo $i; ?></td>
                <input type="hidden" name="caza_wp_cycle_images[<?php echo $image; ?>][id]" value="<?php echo $data['id']; ?>" />
                <input type="hidden" name="caza_wp_cycle_images[<?php echo $image; ?>][file]" value="<?php echo $data['file']; ?>" />
                <input type="hidden" name="caza_wp_cycle_images[<?php echo $image; ?>][file_url]" value="<?php echo $data['file_url']; ?>" />

        <?php if ($data['thumbnail']): ?><input type="hidden" name="caza_wp_cycle_images[<?php echo $image; ?>][thumbnail]" value="<?php echo $data['thumbnail']; ?>" />
                    <input type="hidden" name="caza_wp_cycle_images[<?php echo $image; ?>][thumbnail_url]" value="<?php echo $data['thumbnail_url']; ?>" /><?php endif; ?>
                <td scope="row" class="column-slug">
                <?php if ($data['thumbnail']): ?><img src="<?php echo $data['thumbnail_url']; ?>" /><?php else: ?><img width="100" src="<?php echo $data['file_url']; ?>" /><?php endif; ?>
                    <div><?php echo basename($data['file_url']); ?></div>
                </td>
                <td><input type="text" name="caza_wp_cycle_images[<?php echo $image; ?>][image_links_to]" value="<?php echo $data['image_links_to']; ?>" style="width:100%" /></td>
                <td><input type="text" name="caza_wp_cycle_images[<?php echo $image; ?>][caza_wp_cycle_image_caption]" value="<?php echo $data['caza_wp_cycle_image_caption']; ?>" style="width:100%" /></td>
                <td class="column-slug"><a href="?page=wp-cycle&amp;delete=<?php echo $image; ?>" class="button">Delete</a></td>
                </tr>
    <?php endforeach; ?>
            <input type="hidden" name="caza_wp_cycle_images[update]" value="Updated" />


            </tbody>

        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="Update Images">
        </p>
    </form>
<?php endif; ?>

<?php
