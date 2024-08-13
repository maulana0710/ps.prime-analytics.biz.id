<?php

// uncomment this line for testing
//set_site_transient( 'update_plugins', null );

class DrawAttention_Updater {
	public $parent;
	
	const edd_store_url = 'https://wpdrawattention.com';

	function __construct( $parent ) {
		$this->parent = $parent;

		add_action( 'admin_init', array( $this, 'plugin_updater') );
		add_action( 'admin_init', array( $this, 'activate_license' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'plugin_action_links_' . 'draw-attention-pro/'.$this->parent->plugin_slug.'.php', array( $this, 'add_action_links' ) );
	}

	public function admin_menu() {
		global $submenu;

		add_submenu_page( 'edit.php?post_type=da_image', __( 'License & Support', 'draw-attention' ), __( 'License & Support', 'draw-attention' ), 'manage_options', 'da_license', array( $this, 'output_license_page' ) );
	}

	public function add_action_links( $links ) {
		$license_key_status = get_option( 'da_license_key_status' );
		if ( $license_key_status == 'valid' ) { return $links; }

		return array_merge(
			array(
				'license' => '<a href="'.admin_url( 'edit.php?post_type=da_image&page=da_license' ).'">' . __( 'Enter License Key', 'draw-attention' ) . '</a>'
			),
			$links
		);

	}

	function output_license_page() {
		echo '<div class="wrap">';
		echo '<h1>'.__('Draw Attention Pro', 'draw-attention' ).'</h1>';
		echo '<h2 class="title">'.__('License', 'draw-attention' ).'</h2>';
		echo '<p>'.__('Please enter your license key to enable automatic updates.', 'draw-attention' ).'</p>';
		echo $this->license_key_html();
		echo '<h2 class="title">'.__('Support', 'draw-attention' ).'</h2>';
		echo '<p>'.__('If you need help getting started, please review <a target="_blank" href="https://wpdrawattention.com/help-center/features/getting-started/">our documentation</a>.', 'draw-attention' ).'</p>';
		echo '<p>'.__('If you have any questions or problems, please <a target="_blank" href="https://wpdrawattention.com/contact/">contact our 5-star support team</a>.', 'draw-attention' ).'</p>';
		echo '</div>';
	}

	function license_key_html() {
		$license_key_status = get_option( 'da_license_key_status' );
		if ( empty( $license_key_status ) ) {
			$license_key_status_html = '<span class="status status-yellow">'.__( 'Please enter your license key to receive support & updates', 'draw-attention' ).'. <a href="https://wpdrawattention.com" target="_blank">'.__( 'Click here to purchase or renew a license', 'draw-attention' ).'</a></span>';
		} elseif ( $license_key_status == 'valid' ) {
			$license_key_status_html = '<span class="status status-green">'.__( 'Valid license', 'draw-attention' ).'</span>';
		} elseif ( $license_key_status == 'invalid' ) {
			$license_key_status_html = '<span class="status status-red">'.__( 'Invalid license. Please verify the license key, you may need to <a href="https://wpdrawattention.com" target="_blank">renew your license</a> or <a href="mailto:support@wpdrawattention.com">contact support</a></span>', 'draw-attention' );
		}

		$html  = '<form>';
		$html .= '<input type="hidden" name="post_type" value="da_image" />';
		$html .= '<input type="hidden" name="page" value="da_license" />';
		$html .= '<table class="form-table" role="presentation">';
		$html .= '<tbody>';
		$html .= '<tr>';
		$html .= '<th scope="row">';
		$html .= '<label for="da_license_key">'.__( 'License Key', 'draw-attention' ).'</label>';
		$html .= '</th>';
		$html .= '<td>';
		if ( $license_key = get_option( 'da_license_key' ) ) {
			$html .= '<input type="password" name="da_license_key" id="da_license_key" size="32" value="'.$license_key.'" class="regular-text" />';
		} else {
			$html .= '<input type="text" name="da_license_key" id="da_license_key" size="32" class="regular-text" />';
		}
		$html .= '<div>';
		$html .= $license_key_status_html;
		$html .= '</div>';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<p class="submit">';
		$html .= '<input type="submit" value="'.__( 'Update', 'draw-attention' ).'" class="button button-primary" />';
		$html .= '</p>';
		$html .= '</form>';

		return $html;
	}

	function plugin_updater() {
		$license_key = trim( get_option( 'da_license_key' ) );
		$edd_updater = new TD_DA_EDD_SL_Plugin_Updater( self::edd_store_url, dirname ( dirname( DrawAttention::file ) ).'/draw-attention.php', array( 
				'version' 	=> DrawAttention::VERSION, 				// current version number
				'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
				'item_name' => DrawAttention::name, 	// name of this plugin
				'author' 	=> 'Tyler Digital'  // author of this plugin
			)
		);
	}

	function activate_license() {
		if ( !isset( $_REQUEST['da_license_key'] ) ) return;

		$license_key = trim( $_REQUEST['da_license_key'] );
		if ( empty( $license_key ) ) {
			delete_option( 'da_license_key_status' );
			delete_option( 'da_license_key' );
			return;
		}


		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license_key, 
			'item_name' => urlencode( DrawAttention::name ) // the name of our product in EDD
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, DrawAttention_Updater::edd_store_url ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "active" or "inactive"

		update_option( 'da_license_key_status', $license_data->license );
		
		if ( $license_data->license == 'valid' ) {
			update_option( 'da_license_key', $license_key );
		}
	}

}
if ( class_exists( 'TD_DA_EDD_SL_Plugin_Updater' ) ) return;
class TD_DA_EDD_SL_Plugin_Updater {
	private $api_url  = '';
	private $api_data = array();
	private $name     = '';
	private $slug     = '';
	public $version;
	public $wp_override;
	public $beta;
	public $cache_key;
	

	/**
	 * Class constructor.
	 *
	 * @uses plugin_basename()
	 * @uses hook()
	 *
	 * @param string $_api_url The URL pointing to the custom API endpoint.
	 * @param string $_plugin_file Path to the plugin file.
	 * @param array $_api_data Optional data to send with API calls.
	 * @return void
	 */
	function __construct( $_api_url, $_plugin_file, $_api_data = null ) {
		$this->api_url     = trailingslashit( $_api_url );
		$this->api_data    = $_api_data;
		$this->name        = plugin_basename( $_plugin_file );
		$this->slug        = basename( $_plugin_file, '.php' );
		$this->version     = $_api_data['version'];
		$this->wp_override = isset( $_api_data['wp_override'] ) ? (bool) $_api_data['wp_override'] : false;
		$this->beta        = ! empty( $this->api_data['beta'] ) ? true : false;
		$this->cache_key   = md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) );

		$edd_plugin_data[ $this->slug ] = $this->api_data;

		// Set up hooks.
		$this->init();

	}

	/**
	 * Set up WordPress filters to hook into WP's update process.
	 *
	 * @uses add_filter()
	 *
	 * @return void
	 */
	public function init() {

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
		remove_action( 'after_plugin_row_' . $this->name, 'wp_plugin_update_row', 10 );
		add_action( 'after_plugin_row_' . $this->name, array( $this, 'show_update_notification' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'show_changelog' ) );
		add_action( 'after_plugin_row_' . $this->name, array( $this, 'maybe_display_invalid_license_banner' ), 10, 2 );


	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @uses api_request()
	 *
	 * @param array   $_transient_data Update array build by WordPress.
	 * @return array Modified update array with custom plugin data.
	 */
	public function check_update( $_transient_data ) {

		global $pagenow;

		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new stdClass;
		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {
			return $_transient_data;
		}

		if ( ! empty( $_transient_data->response ) && ! empty( $_transient_data->response[ $this->name ] ) && false === $this->wp_override ) {
			return $_transient_data;
		}

		$version_info = $this->get_cached_version_info();

		if ( false === $version_info ) {
			$version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug, 'beta' => $this->beta ) );

			$this->set_version_info_cache( $version_info );

		}

		if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

			if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {

				$_transient_data->response[ $this->name ] = $version_info;

			}

			$_transient_data->last_checked           = current_time( 'timestamp' );
			$_transient_data->checked[ $this->name ] = $this->version;

		}

		return $_transient_data;
	}

	/**
	 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
	 *
	 * @param string  $file
	 * @param array   $plugin
	 */
	public function show_update_notification( $file, $plugin ) {

		if ( is_network_admin() ) {
			return;
		}

		if( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		if( ! is_multisite() ) {
			return;
		}

		if ( $this->name != $file ) {
			return;
		}

		// Remove our filter on the site transient
		remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 10 );

		$update_cache = get_site_transient( 'update_plugins' );

		$update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();

		if ( empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {

			$version_info = $this->get_cached_version_info();

			if ( false === $version_info ) {
				$version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug, 'beta' => $this->beta ) );

				$this->set_version_info_cache( $version_info );
			}

			if ( ! is_object( $version_info ) ) {
				return;
			}

			if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {

				$update_cache->response[ $this->name ] = $version_info;

			}

			$update_cache->last_checked = current_time( 'timestamp' );
			$update_cache->checked[ $this->name ] = $this->version;

			set_site_transient( 'update_plugins', $update_cache );

		} else {

			$version_info = $update_cache->response[ $this->name ];

		}

		// Restore our filter
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

		if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $version_info->new_version, '<' ) ) {

			// build a plugin list row, with update notification
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
			# <tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
			echo '<tr class="plugin-update-tr" id="' . $this->slug . '-update" data-slug="' . $this->slug . '" data-plugin="' . $this->slug . '/' . $file . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message notice inline notice-warning notice-alt">';

			$changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

			if ( empty( $version_info->download_link ) ) {
				printf(
					__( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s.', 'easy-digital-downloads' ),
					esc_html( $version_info->name ),
					'<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
					esc_html( $version_info->new_version ),
					'</a>'
				);
			} else {
				printf(
					__( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s or %5$supdate now%6$s.', 'easy-digital-downloads' ),
					esc_html( $version_info->name ),
					'<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
					esc_html( $version_info->new_version ),
					'</a>',
					'<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) ) .'">',
					'</a>'
				);
			}

			do_action( "in_plugin_update_message-{$file}", $plugin, $version_info );

			echo '</div></td></tr>';
		}
	}

	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed   $_data
	 * @param string  $_action
	 * @param object  $_args
	 * @return object $_data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

		if ( $_action != 'plugin_information' ) {

			return $_data;

		}

		if ( ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {

			return $_data;

		}

		$to_send = array(
			'slug'   => $this->slug,
			'is_ssl' => is_ssl(),
			'fields' => array(
				'banners' => array(),
				'reviews' => false
			)
		);

		$cache_key = 'edd_api_request_' . md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) );

		// Get the transient where we store the api request for this plugin for 24 hours
		$edd_api_request_transient = $this->get_cached_version_info( $cache_key );

		//If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
		if ( empty( $edd_api_request_transient ) ) {

			$api_response = $this->api_request( 'plugin_information', $to_send );

			// Expires in 3 hours
			$this->set_version_info_cache( $api_response, $cache_key );

			if ( false !== $api_response ) {
				$_data = $api_response;
			}

		} else {
			$_data = $edd_api_request_transient;
		}

		// Convert sections into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
			$new_sections = array();
			foreach ( $_data->sections as $key => $value ) {
				$new_sections[ $key ] = $value;
			}

			$_data->sections = $new_sections;
		}

		// Convert banners into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
			$new_banners = array();
			foreach ( $_data->banners as $key => $value ) {
				$new_banners[ $key ] = $value;
			}

			$_data->banners = $new_banners;
		}

		return $_data;
	}

	/**
	 * Disable SSL verification in order to prevent download update failures
	 *
	 * @param array   $args
	 * @param string  $url
	 * @return object $array
	 */
	public function http_request_args( $args, $url ) {
		// If it is an https request and we are performing a package download, disable ssl verification
		if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = false;
		}
		return $args;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string  $_action The requested action.
	 * @param array   $_data   Parameters for the API action.
	 * @return false|object
	 */
	private function api_request( $_action, $_data ) {

		global $wp_version;

		$data = array_merge( $this->api_data, $_data );

		if ( $data['slug'] != $this->slug ) {
			return;
		}

		if( $this->api_url == trailingslashit (home_url() ) ) {
			return false; // Don't allow a plugin to ping itself
		}

		$api_params = array(
			'edd_action' => 'get_version',
			'license'    => ! empty( $data['license'] ) ? $data['license'] : '',
			'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
			'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
			'version'    => isset( $data['version'] ) ? $data['version'] : false,
			'slug'       => $data['slug'],
			'author'     => $data['author'],
			'url'        => home_url(),
			'beta'       => ! empty( $data['beta'] ),
		);

		$request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( ! is_wp_error( $request ) ) {
			$request = json_decode( wp_remote_retrieve_body( $request ) );
		}

		if ( $request && isset( $request->sections ) ) {
			$request->sections = maybe_unserialize( $request->sections );
		} else {
			$request = false;
		}

		if ( $request && isset( $request->banners ) ) {
			$request->banners = maybe_unserialize( $request->banners );
		}

		if( ! empty( $request->sections ) ) {
			foreach( $request->sections as $key => $section ) {
				$request->$key = (array) $section;
			}
		}

		return $request;
	}

	public function show_changelog() {

		global $edd_plugin_data;

		if( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' != $_REQUEST['edd_sl_action'] ) {
			return;
		}

		if( empty( $_REQUEST['plugin'] ) ) {
			return;
		}

		if( empty( $_REQUEST['slug'] ) ) {
			return;
		}

		if( ! current_user_can( 'update_plugins' ) ) {
			wp_die( __( 'You do not have permission to install plugin updates', 'easy-digital-downloads' ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
		}

		$data         = $edd_plugin_data[ $_REQUEST['slug'] ];
		$beta         = ! empty( $data['beta'] ) ? true : false;
		$cache_key    = md5( 'edd_plugin_' . sanitize_key( $_REQUEST['plugin'] ) . '_' . $beta . '_version_info' );
		$version_info = $this->get_cached_version_info( $cache_key );

		if( false === $version_info ) {

			$api_params = array(
				'edd_action' => 'get_version',
				'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
				'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
				'slug'       => $_REQUEST['slug'],
				'author'     => $data['author'],
				'url'        => home_url(),
				'beta'       => ! empty( $data['beta'] )
			);

			$request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( ! is_wp_error( $request ) ) {
				$version_info = json_decode( wp_remote_retrieve_body( $request ) );
			}


			if ( ! empty( $version_info ) && isset( $version_info->sections ) ) {
				$version_info->sections = maybe_unserialize( $version_info->sections );
			} else {
				$version_info = false;
			}

			if( ! empty( $version_info ) ) {
				foreach( $version_info->sections as $key => $section ) {
					$version_info->$key = (array) $section;
				}
			}

			$this->set_version_info_cache( $version_info, $cache_key );

		}

		if( ! empty( $version_info ) && isset( $version_info->sections['changelog'] ) ) {
			echo '<div style="background:#fff;padding:10px;">' . $version_info->sections['changelog'] . '</div>';
		}

		exit;
	}

	public function get_cached_version_info( $cache_key = '' ) {

		if( empty( $cache_key ) ) {
			$cache_key = $this->cache_key;
		}

		$cache = get_option( $cache_key );

		if( empty( $cache['timeout'] ) || current_time( 'timestamp' ) > $cache['timeout'] ) {
			return false; // Cache is expired
		}

		return json_decode( $cache['value'] );

	}

	public function set_version_info_cache( $value = '', $cache_key = '' ) {

		if( empty( $cache_key ) ) {
			$cache_key = $this->cache_key;
		}

		$data = array(
			'timeout' => strtotime( '+3 hours', current_time( 'timestamp' ) ),
			'value'   => json_encode( $value )
		);

		update_option( $cache_key, $data );

	}

	/**
	 * Prompt unlicensed users to activate/purchase license in wp-admin/plugins.php list
	 *
	 * @since 3.0.5
	 * 
	 * @param string  $file
	 * @param array   $plugin
	 */
	public function maybe_display_invalid_license_banner( $file, $plugin ) {


		$license_key_status = get_option( 'da_license_key_status' );

		if ( ! empty( $license_key_status ) && $license_key_status == 'valid' ) {
			return; 
		}

		$license_page 	= admin_url( 'edit.php?post_type=da_image&page=da_license' );
		$store = 'https://wpdrawattention.com';

		echo '<tr class="plugin-update-tr active">';
		echo '<td colspan="4" class="plugin-update colspanchange">';
		echo '<div class="update-message notice inline notice-warning notice-alt">';
		echo '<p>' . sprintf( __( 'Your Draw Attention License key is currently inactive or expired. %1$s Enter your license key %2$s or %3$s purchase a new one  %4$s to enable automatic updates and support.', 'draw-attention' ),
						'<a href="' . $license_page . '">',
						'</a>',
						'<a href="' . $store . '" target="_blank">',
						'</a>' ) . '</p>';
		echo '</div>';
		echo '</td>';
		echo '</tr>';
	}
}
