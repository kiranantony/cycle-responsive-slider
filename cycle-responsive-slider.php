<?php
/*
  Plugin Name: WP Cycle Responsive Slider
  Plugin URI: http://www.kiranantony.com/wp-cycle2/
  Description: This plugin creates an image slideshow from the images you upload using the jQuery Cycle2 plugin. You can upload/delete images via the administration panel, and display the images in your theme by using the <code>wp_cycle();</code> template tag, which will generate all the necessary HTML for outputting the rotating images. Admin Options Based On wp Cycle Plugin.
  Version: 1.2.1
  Author: Kiran Antony
  Author URI: http://www.kiranantony.com/

  This plugin inherits the GPL license from it's parent system, WordPress.
 */
/* * ***Credits**** */
/*
  Original Plugin Name: WP-Cycle
  Original Plugin URI: http://www.nathanrice.net/plugins/wp-cycle/
  Original Description: This plugin creates an image slideshow from the images you upload using the jQuery Cycle plugin. You can upload/delete images via the administration panel, and display the images in your theme by using the <code>caza_wp_cycle();</code> template tag, which will generate all the necessary HTML for outputting the rotating images.
  Original Version: 0.1.12
  Original Author: Nathan Rice
  Original Author URI: http://www.nathanrice.net/
 */


$caza_wp_cycle_defaults = apply_filters('caza_wp_cycle_defaults', array(
    'rotate' => 1,
    'effect' => 'fade',
    'delay' => 3,
    'duration' => 1,
    'img_width' => 300,
    'img_height' => 200,
    'random' => 0,
    'div' => 'rotator'
        ));

//	pull the settings from the db
$caza_wp_cycle_settings = get_option('caza_wp_cycle_settings');
$caza_wp_cycle_images = get_option('caza_wp_cycle_images');
$caza_wp_cycle_settings = wp_parse_args($caza_wp_cycle_settings, $caza_wp_cycle_defaults);

if (!class_exists('WP_Cycle_Responsive')) {

    class WP_Cycle_Responsive {

        /**
         * Construct the plugin object
         */
        public function __construct() {


//	fallback
            // register actions
            add_action('admin_init', array(&$this, 'caza_wp_cycle_register_settings'));
            add_action('admin_menu', array(&$this, 'add_caza_wp_cycle_menu'));
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'caza_wp_cycle_plugin_action_links'));
            add_shortcode('wp_cycle', array(&$this, 'caza_wp_cycle_shortcode'));
            add_shortcode('wp-cycle', array(&$this, 'caza_wp_cycle_shortcode'));
            add_action('wp_enqueue_scripts', array(&$this, 'caza_wp_cycle_scripts'));
            add_action('admin_enqueue_scripts', array(&$this, 'caza_wp_cycle_admin_script'));
            add_action('wp_footer', array(&$this, 'caza_wp_cycle_args'), 90);
            add_action('wp_head', array(&$this, 'caza_wp_cycle_style'));
            add_action('admin_footer', array(&$this, 'caza_wp_cycle_sortable'), 100);
            if (version_compare(PHP_VERSION, '5.3', '<')) {
                add_action('widgets_init', create_function('', 'return register_widget("WP_CYCLE_WIDGET");')
                );
            } else {
                add_action('widgets_init', function() {
                    register_widget('WP_CYCLE_WIDGET');
                });
            }
        }

// END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate() {
            // Do nothing
        }

// END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate() {
            // Do nothing
        }

// END public static function deactivate
//	this function registers our settings in the db


        public function caza_wp_cycle_register_settings() {
            register_setting('caza_wp_cycle_images', 'caza_wp_cycle_images', array(&$this, 'caza_wp_cycle_images_validate'));
            register_setting('caza_wp_cycle_settings', 'caza_wp_cycle_settings', array(&$this, 'caza_wp_cycle_settings_validate'));
        }

//	this function adds the settings page to the Appearance tab


        public function add_caza_wp_cycle_menu() {
            add_submenu_page('upload.php', 'WP-Cycle Settings', 'WP-Cycle', 'upload_files', 'wp-cycle', array(&$this, 'caza_wp_cycle_admin_page'));
        }

//	add "Settings" link to plugin page


        public function caza_wp_cycle_plugin_action_links($links) {
            $caza_wp_cycle_settings_link = sprintf('<a href="%s">%s</a>', admin_url('upload.php?page=wp-cycle'), __('Settings'));
            array_unshift($links, $caza_wp_cycle_settings_link);
            return $links;
        }

        /*
          ///////////////////////////////////////////////
          this function is the code that gets loaded when the
          settings page gets loaded by the browser.  It calls
          functions that handle image uploads and image settings
          changes, as well as producing the visible page output.
          \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
         */

        public function caza_wp_cycle_admin_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
        }

//	this function sanitizes our settings data for storage
        public function caza_wp_cycle_settings_validate($input) {
            $input['rotate'] = ($input['rotate'] == 1 ? 1 : 0);
            $input['random'] = ($input['random'] == 1 ? 1 : 0);
            $input['effect'] = wp_filter_nohtml_kses($input['effect']);
            $input['img_width'] = intval($input['img_width']);
            $input['img_height'] = intval($input['img_height']);
            $input['div'] = wp_filter_nohtml_kses($input['div']);

            return $input;
        }

//	this function sanitizes our image data for storage
        public function caza_wp_cycle_images_validate($input) {
            foreach ((array) $input as $key => $value) {
                if ($key != 'update') {
                    $input[$key]['file_url'] = esc_url($value['file_url']);
                    $input[$key]['thumbnail_url'] = esc_url($value['thumbnail_url']);

                    if ($value['image_links_to'])
                        $input[$key]['image_links_to'] = esc_url($value['image_links_to']);

                    if ($value['caza_wp_cycle_image_caption'])
                        $input[$key]['caza_wp_cycle_image_caption'] = wp_filter_nohtml_kses($value['caza_wp_cycle_image_caption']);
                }
            }
            return $input;
        }

        //	this function checks to see if we just updated the settings
//	if so, it displays the "updated" message.
        public function caza_wp_cycle_settings_update_check() {
            global $caza_wp_cycle_settings;
            if (isset($caza_wp_cycle_settings['update'])) {
                echo '<div class="updated fade" id="message"><p>WP-Cycle Settings <strong>' . $caza_wp_cycle_settings['update'] . '</strong></p></div>';
                unset($caza_wp_cycle_settings['update']);
                update_option('caza_wp_cycle_settings', $caza_wp_cycle_settings);
            }
        }

//	this function checks to see if we just added a new image
//	if so, it displays the "updated" message.
        public function caza_wp_cycle_images_update_check() {
            global $caza_wp_cycle_images;
            if (isset($caza_wp_cycle_images['update']) && $caza_wp_cycle_images['update'] == 'Added' || isset($caza_wp_cycle_images['update']) && $caza_wp_cycle_images['update'] == 'Deleted' || isset($caza_wp_cycle_images['update']) && $caza_wp_cycle_images['update'] == 'Updated') {
                echo '<div class="updated fade" id="message"><p>Image(s) ' . $caza_wp_cycle_images['update'] . ' Successfully</p></div>';
                unset($caza_wp_cycle_images['update']);
                update_option('caza_wp_cycle_images', $caza_wp_cycle_images);
            }
        }

        public function caza_wp_cycle_handle_upload() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            require_once plugin_dir_path(__FILE__) . 'includes/wp_cycle_handle_upload.php';
        }

        public function caza_wp_cycle_delete_upload($id) {
            global $caza_wp_cycle_images;

            //	if the ID passed to this function is invalid,
            //	halt the process, and don't try to delete.
            if (!isset($caza_wp_cycle_images[$id]))
                return;

            //	delete the image and thumbnail
            unlink($caza_wp_cycle_images[$id]['file']);
            unlink($caza_wp_cycle_images[$id]['thumbnail']);

            //	indicate that the image was deleted
            $caza_wp_cycle_images['update'] = 'Deleted';

            //	remove the image data from the db
            unset($caza_wp_cycle_images[$id]);
            update_option('caza_wp_cycle_images', $caza_wp_cycle_images);
        }

        public function caza_wp_cycle_images_admin() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            require_once plugin_dir_path(__FILE__) . 'includes/wp_cycle_images_admin.php';
        }

        //	display the settings administration code
        public function caza_wp_cycle_settings_admin() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            require_once plugin_dir_path(__FILE__) . 'includes/wp_cycle_settings_admin.php';
        }

        public function caza_wp_cycle($args = array(), $content = null) {
            global $caza_wp_cycle_settings, $caza_wp_cycle_images;
            // possible future use
            $args = wp_parse_args($args, $caza_wp_cycle_settings);

            $newline = "\n"; // line break

            echo '<div class="' . $caza_wp_cycle_settings['div'] . '">' . $newline;

            foreach ((array) $caza_wp_cycle_images as $image => $data) {
                echo '<div>';
                if ($data['image_links_to'])
                    echo '<a href="' . $data['image_links_to'] . '" >';
                echo '<img src="' . $data['file_url'] . '" width="' . $caza_wp_cycle_settings['img_width'] . '" height="' . $caza_wp_cycle_settings['img_height'] . '" class="' . $data['id'] . '" alt="' . $data['caza_wp_cycle_image_caption'] . '" />';

                if ($data['image_links_to'])
                    echo '</a>';
                //updated 8/1/2012 by Toby Brommerich -- Moved Caption inside foreach, copied caption variable from img alt
                if (!empty($data['caza_wp_cycle_image_caption']))
                    echo '<p id="caption" class="cycle-caption">' . $data['caza_wp_cycle_image_caption'] . '</p>';
                echo '</div>';
            }

            echo '</div>' . $newline;
        }

//	create the shortcode [wp_cycle]
        public function caza_wp_cycle_shortcode($atts) {
            // Temp solution, output buffer the echo function.
            ob_start();
            $this->caza_wp_cycle();
            $output = ob_get_clean();

            return $output;
        }

        public function caza_wp_cycle_scripts() {
            global $caza_wp_cycle_settings;
            wp_enqueue_script('cycle', plugins_url('/jquery.cycle2.min.js', __FILE__), array('jquery'), '');
            if ($caza_wp_cycle_settings['effect'] == 'tileSlide' || $caza_wp_cycle_settings['effect'] == 'tileBlind')
                wp_enqueue_script('cycle-plugin', plugins_url('/jquery.cycle2.tile.min.js', __FILE__), array('cycle'));
            if ($caza_wp_cycle_settings['effect'] == 'flipHorz' || $caza_wp_cycle_settings['effect'] == 'flipVert')
                wp_enqueue_script('cycle-plugin', plugins_url('/jquery.cycle2.flip.min.js', __FILE__), array('cycle'), '', true);
            if ($caza_wp_cycle_settings['effect'] == 'scrollVert')
                wp_enqueue_script('cycle-plugin', plugins_url('/jquery.cycle2.scrollVert.min.js', __FILE__), array('cycle'), '', true);
            if ($caza_wp_cycle_settings['effect'] == 'shuffle')
                wp_enqueue_script('cycle-plugin', plugins_url('/jquery.cycle2.shuffle.min.js', __FILE__), array('cycle'), '', true);
        }

        public function caza_wp_cycle_admin_script() {
            wp_enqueue_script('jquery-ui-sortable');
        }

        public function caza_wp_cycle_args() {
            global $caza_wp_cycle_settings;
            ?>

            <?php if ($caza_wp_cycle_settings['rotate']) : ?>
                <script type="text/javascript">
                    jQuery('document').ready(function () {
                        jQuery(".<?php echo $caza_wp_cycle_settings['div']; ?>").cycle({
                            fx: '<?php echo $caza_wp_cycle_settings['effect']; ?>',
                            timeout: <?php echo $caza_wp_cycle_settings['delay'] * 1000; ?>,
                            speed: <?php echo $caza_wp_cycle_settings['duration'] * 1000; ?>,
                            random: <?php echo $caza_wp_cycle_settings['random']; ?>,
                            slides: '> div',
                            autoHeight: 'calc',
                            loader: 'wait'
                        });
                    });
                </script>
            <?php endif; ?>

            <?php
        }

        public function caza_wp_cycle_style() {
            global $caza_wp_cycle_settings;
            ?>

            <style type="text/css" media="screen">
                .<?php echo $caza_wp_cycle_settings['div']; ?> {
                    /*position: relative;*/ 
                    /*width: <?php // echo $caza_wp_cycle_settings['img_width'];                                                     ?>px;*/
                    /*height: <?php // echo $caza_wp_cycle_settings['img_height']                                                     ?>px;*/
                    /*margin: 0; padding: 0;*/ 


                    /*overflow: hidden;*/


                }
            </style>

            <?php
        }

        public function caza_wp_cycle_sortable() {
            ?>
            <style type="text/css">
                .wp-cycle-image-list tbody.ui-sortable > tr > td.order {
                    background: #f4f4f4 none repeat scroll 0 0;
                    border-right-color: #e1e1e1;
                    color: #aaa;
                    cursor: move;
                    text-align: center !important;
                    text-shadow: 0 1px 0 #fff;
                    vertical-align: middle;
                    width: 16px !important;
                }
                .wp-cycle-image-list tbody.ui-sortable > tr > td {
                    border-bottom: 1px solid #ededed;
                    padding: 8px;
                    position: relative;
                }
                .wp-cycle-image-list tbody .row {
                    background: #fff none repeat scroll 0 0;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery(".wp-cycle-image-list .ui-sortable").sortable({
                        cursor: 'move',
                        items: 'tr.row',
                        handle: 'td.order',
                        cancel: '.fixed,input',
                        update: function () {
                            var order = jQuery('.ui-sortable').sortable('serialize');
                            jQuery.ajax({
                                type: "POST",
                                url: "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
                                data: "action=update_field_order&order=" + order
                            });
                        }});
                    //            jQuery(".ui-sortable").disableSelection();
                    jQuery("tbody.ui-sortable > tr > td").each(function (index) {
                        jQuery(this).width(jQuery(this).width());
                    });
                });
            </script>
            <?php
        }

    }

    // END class WP_Cycle_Responsive
} // END if(!class_exists('WP_Cycle_Responsive'))

if (class_exists('WP_Cycle_Responsive')) {
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WP_Cycle_Responsive', 'activate'));
    register_deactivation_hook(__FILE__, array('WP_Cycle_Responsive', 'deactivate'));

    // instantiate the plugin class
    $wp_plugin_template = new WP_Cycle_Responsive();

    // support for wp_cycle() ;
    function wp_cycle($args = array(), $content = null) {
        global $wp_plugin_template;
        $wp_plugin_template->caza_wp_cycle($args, $content);
    }

}

require_once plugin_dir_path(__FILE__) . 'includes/wp_cycle_widget.php';
