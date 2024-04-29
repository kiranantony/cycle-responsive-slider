<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php $this->caza_wp_cycle_settings_update_check(); ?>
<h2><?php _e('WP-Cycle 2 Settings', 'wp-cycle'); ?></h2>
<form method="post" action="options.php">
<?php settings_fields('caza_wp_cycle_settings'); ?>
<?php
global $caza_wp_cycle_settings;
$options = $caza_wp_cycle_settings;
?>
    <table class="form-table" >
        <tr><th scope="row">Enable Random Image Order</th>
            <td><input name="caza_wp_cycle_settings[random]" type="checkbox" value="1" <?php checked('1', $options['random']); ?> /> <label for="caza_wp_cycle_settings[random]">Check this box if you want to enable random image order</td>
            </td></tr>
        <tr valign="top"><th scope="row">Transition Enabled</th>
            <td><input name="caza_wp_cycle_settings[rotate]" type="checkbox" value="1" <?php checked('1', $options['rotate']); ?> /> <label for="caza_wp_cycle_settings[rotate]">Check this box if you want to enable the transition effects</td>
        </tr>
        <tr><th scope="row">Transition Effect</th>
            <td>Please select the effect you would like to use when your images rotate (if applicable):<br />
                <select name="caza_wp_cycle_settings[effect]">
                    <option value="fade" <?php selected('fade', $options['effect']); ?>>fade</option>
                    <option value="fadeout" <?php selected('fadeout', $options['effect']); ?>>fadeout</option>
                    <option value="scrollHorz" <?php selected('scrollHorz', $options['effect']); ?>>scrollHorz</option>
                    <!--<option value="tileSlide" <?php // selected('tileSlide', $options['effect']); ?>>tileSlide</option>-->
                    <!--<option value="tileBlind" <?php // selected('tileBlind', $options['effect']); ?>>tileBlind</option>-->
                    <!--<option value="flipHorz" <?php // selected('flipHorz', $options['effect']); ?>>flipHorz</option>-->
                    <!--<option value="flipVert" <?php // selected('flipVert', $options['effect']); ?>>flipVert</option>-->
                    <!--<option value="scrollVert" <?php // selected('scrollVert', $options['effect']); ?>>scrollVert</option>-->
                    <!--<option value="shuffle" <?php // selected('shuffle', $options['effect']); ?>>shuffle</option>-->
                </select>
            </td></tr>

        <tr><th scope="row">Transition Delay</th>
            <td>Length of time (in seconds) you would like each image to be visible:<br />
                <input type="text" name="caza_wp_cycle_settings[delay]" value="<?php echo $options['delay'] ?>" size="4" />
                <label for="caza_wp_cycle_settings[delay]">second(s)</label>
            </td></tr>

        <tr><th scope="row">Transition Length</th>
            <td>Length of time (in seconds) you would like the transition length to be:<br />
                <input type="text" name="caza_wp_cycle_settings[duration]" value="<?php echo $options['duration'] ?>" size="4" />
                <label for="caza_wp_cycle_settings[duration]">second(s)</label>
            </td></tr>

        <tr><th scope="row">Image Dimensions</th>
            <td>Please input the width of the image rotator:<br />
                <input type="text" name="caza_wp_cycle_settings[img_width]" value="<?php echo $options['img_width'] ?>" size="4" />
                <label for="caza_wp_cycle_settings[img_width]">px</label>
                <br /><br />
                Please input the height of the image rotator:<br />
                <input type="text" name="caza_wp_cycle_settings[img_height]" value="<?php echo $options['img_height'] ?>" size="4" />
                <label for="caza_wp_cycle_settings[img_height]">px</label>
            </td></tr>

        <tr><th scope="row">Rotator DIV Class</th>
            <td>Please indicate what you would like the rotator DIV Class to be:<br />
                <input type="text" name="caza_wp_cycle_settings[div]" value="<?php echo $options['div'] ?>" />
            </td></tr>

        <input type="hidden" name="caza_wp_cycle_settings[update]" value="UPDATED" />

    </table>
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
</form>

<!-- The Reset Option -->
<form method="post" action="options.php">
<?php settings_fields('caza_wp_cycle_settings'); ?>
<?php global $caza_wp_cycle_defaults; // use the defaults    ?>
    <?php foreach ((array) $caza_wp_cycle_defaults as $key => $value) : ?>
        <input type="hidden" name="caza_wp_cycle_settings[<?php echo $key; ?>]" value="<?php echo $value; ?>" />
    <?php endforeach; ?>
    <input type="hidden" name="caza_wp_cycle_settings[update]" value="RESET" />
    <input type="submit" class="button" value="<?php _e('Reset Settings') ?>" />
</form>
<!-- End Reset Option -->
</p>

<?php
