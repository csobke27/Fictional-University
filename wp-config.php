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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '9OFT).tCU^6Y}/F|}Vg(Pvy p2qc-Ef0Q/+y,iN?ilH(]1E(o+X% ):/Y)`Vk]g$' );
define( 'SECURE_AUTH_KEY',   'D`Vch sT3A+|S[5/R`ft&f4tD.bKoyx0V<$rL4!M%,|i.5I(M+,T5q-To5@Chf-p' );
define( 'LOGGED_IN_KEY',     '~q?;]XFglRo!5 h(Bg[A}<=VW%5|*r2qEk^A`HNXZ]/Tv-;tip^8m$BmN/q/*}1<' );
define( 'NONCE_KEY',         'fe$#c#WG%AB>9M@!RVGSM^lUz|a#$kH}q5bl]7#2C=xDw+(*ltkCN}Ch:04J?xXO' );
define( 'AUTH_SALT',         'r MU:juPZ+E]/Ij^_ZcD0$$8hQj/l~xY)9]d.qN|zvy$>>>>y;-23nK:I.,1.+9/' );
define( 'SECURE_AUTH_SALT',  ' klr>RhtR8#3$4RENVGfp}jAxV>xH7$9zV[+e5-c.1Wjmo{`o ndmX~0L>7^4/l{' );
define( 'LOGGED_IN_SALT',    ')cQpXzz^]Ah09Vm/jh-` W&Vks~=%k:(Rc<]`cEkdW}[-~#8,*417(5M*lwn1bKl' );
define( 'NONCE_SALT',        '0rcO!K?B 3YA_Xk~oRX<c<`oi)*w9q!ktFn(vK:NB`kY=5E50)*i:AIJTM|Zx*gb' );
define( 'WP_CACHE_KEY_SALT', '2jE4/yJx!$qkM_G<i&#NRZE-.LQD -MEjc?;%hk1~+Q/WN~-HCW}`G|#$$Vh$x;]' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
