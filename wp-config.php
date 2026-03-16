<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'M+I.9Il`{[i6C3rClVZqH|#K.<RBIzLo7B^I) dy>F+k*NB y~aWNy}[>c6dLOLB' );
define( 'SECURE_AUTH_KEY',  '}W153><uh(obz}KM)L#G/S6*ib[Hm1GJWaqw^==`h1IfU3OMgcr(<4Wb,|NP$5eb' );
define( 'LOGGED_IN_KEY',    'o_|lX$8%Cf?y*g~#OXw,Vt-[yQkUr>RzP>*|,T<f/DwFZ#pF49>Nvk?Me1>s`YIU' );
define( 'NONCE_KEY',        '%;8cKs*RCRy#]PNDZp@U5YU}N#I-y0^3*o#K?j^k{0-X{=lWlg23*K4PA7zzq{8*' );
define( 'AUTH_SALT',        ' [Z)1,|coei hncV$Bd&B|zO: `d=e4(Y+MU4omx*po8?WP,f,HM)|jY|(q2U,T3' );
define( 'SECURE_AUTH_SALT', 'Hl`RVY(^XGf/ H)Lkp^7^*uj3E%J )M55qA }m58{bYjy`>|4cH[M9LyB0*XKOe0' );
define( 'LOGGED_IN_SALT',   '6+9S@YUl8%BB~u9hn!VGYiK~[ 03E$Br[ n6Vd*{m}C;q*v+A4&@Z?|i=DN#OFOS' );
define( 'NONCE_SALT',       '_>kOnr!?AchiEZF$xzE^fJ!@EPA{[5VESru|_),T&KA[]eZI0K$H:oJy<uior,96' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
