<?php
class DrawAttention_User_Style extends DrawAttention_Style {
	function get_saved_styles( $post_id = null ) {
		if ( empty( $post_id ) && !empty( $_GET['post'] ) ) {
			$post_id = esc_attr( $_GET['post'] );
		}

		if ( empty( $post_id ) ) {
			return array();
		}
		$saved_user_styles = get_post_meta( $post_id, '_da_styles', true );
		if ( empty( $saved_user_styles['0']['map_highlight_color'] ) ) {
			return array();
		}

		$style_key_values = array();
		foreach ($saved_user_styles as $key => $saved_user_style) {
			if ( empty( $saved_user_style['title'] ) ) {
				$saved_user_style['title'] = 'Style #'.(((int)$key)+1);

			}

			$style_key_values[sanitize_title( $saved_user_style['title'] )] = $saved_user_style['title'];
		}

		return $style_key_values;
	}
	
	function add_style_fields( $group_details ) {
		$user_styles = $this->get_saved_styles();
		
		foreach ($user_styles as $user_key => $label) {
			$group_details['fields'][0]['fields']['style']['options'][$user_key] = __( $label, 'draw-attention' );
		}

		return $group_details;
	}	
}