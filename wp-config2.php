<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */
define('WP_MEMORY_LIMIT', '1024M');

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'primeana_pesona' );

/** Database username */
define( 'DB_USER', 'primeana_cabinite' );

/** Database password */
define( 'DB_PASSWORD', 'Z2uarM2TQ4CzPHF' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lt8fbn3ruez2wtdikuzky00psknswfpuwla6l4tzpciw2ebk8fako5zuwgv20lxm' );
define( 'SECURE_AUTH_KEY',  'y9pxqjxej2bgtyurwfzv8bgujfnth13gv5o5owwrxcwwfdo8oeys08khfjkll9bj' );
define( 'LOGGED_IN_KEY',    'ftvkim3ohkhs6hogl2surputfitxdqz56ftrxckvtopfb95tfzihrlh27vzjika6' );
define( 'NONCE_KEY',        'nzbct9cctwox7ie8dh0hyy4re1qoyco6owftp4rovedcbhlcmnwa5orvm1iqvsg4' );
define( 'AUTH_SALT',        'jsxr6tzn0runxbcqmw3r2xjgmckuwn5pt49gdqgo5pnoeusb26ixbdutuvyvtk7c' );
define( 'SECURE_AUTH_SALT', 'u9vvazkd1numhzayihthfaryhgptjqs6krtl0y7fdwae5bs3fka0rebu7dnbdhnd' );
define( 'LOGGED_IN_SALT',   'bvmqlir8jcf9n9sm0pfibderd8qddwe7chc5aexynw6gy8syuxtbvn0qimkx4egt' );
define( 'NONCE_SALT',       'iaknx2orhxsp9kdbmcfneve7i2tlboj4kyqtcb3jnxwwsgvix8qelery4nevjbbb' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wppp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
