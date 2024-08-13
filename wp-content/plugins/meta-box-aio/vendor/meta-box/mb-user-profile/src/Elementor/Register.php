<?php
namespace MetaBox\UserProfile\Elementor;

class Register {
	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register' ] );
	}

	public function register( $widgets_manager ) {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}
		$widgets_manager->register( new LoginForm() );
		$widgets_manager->register( new RegistrationForm() );
		$widgets_manager->register( new ProfileForm() );
	}
}

