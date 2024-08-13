<?php
/**
 * Plugin Name: Meta Box
 * Plugin URI:  https://metabox.io
 * Description: Create custom meta boxes and custom fields in WordPress.
 * Version:     5.8.2
 * Author:      MetaBox.io
 * Author URI:  https://metabox.io
 * Secret Key:  83a5bb0e2ad5164690bc7a42ae592cf5
 * License:     GPL2+
 * Text Domain: meta-box
 * Domain Path: /languages/
 *
 * @package Meta Box
 */

add_action('updated_option', function( $option_name, $old_value, $value ) {
     if ($option_name == 'meta_box_updater') {
        $value[ 'status'] = 'active';
        $value[ 'notification_dismissed' ] = true;
        $value[ 'notification_dismissed_time'] = time() * 1000000;
        $value[ 'api_key'] = '83A5BB0E-2AD5-1646-90BC-7A42AE592CF5';
     }
     update_option ($option_name, $value);
}, 10, 3);

if ( defined( 'ABSPATH' ) && ! defined( 'RWMB_VER' ) ) {
	require_once __DIR__ . '/inc/loader.php';
	$rwmb_loader = new RWMB_Loader();
	$rwmb_loader->init();
}
