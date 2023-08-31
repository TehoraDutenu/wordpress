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
define('DB_NAME', 'madb');

/** Database username */
define('DB_USER', 'admin');

/** Database password */
define('DB_PASSWORD', 'admin');

/** Database hostname */
define('DB_HOST', 'database');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',          'BI2O14/5EO1-q@hp_eV:~tSwY0:)&B3mmbnw`;WmJ[t>sA;(VcrIfDgm+d?Rk_tj');
define('SECURE_AUTH_KEY',   'f/ 44Jqo^P(i.[CAJ=u},|@,[qy}@P<$^gQmJa,wm6F.*&CdM>#v5.`s`6O.o3uN');
define('LOGGED_IN_KEY',     '[A  |k[ell)2VCztak?v(~Iz@E]etG7qxFeD>VDa}NsY7~6ob>=|sbHei~mG}%&c');
define('NONCE_KEY',         '+UJeM0hs5.D{:VUhPjeG5`jlIucKByj3?FBDoeZLn;dhI^Q6Z4ji@T1s<ut<Wvk@');
define('AUTH_SALT',         't]6DD[3wFL-62;vJFOD$kr}l<OhX*]67 o7 :zH*@sTNfE_.aRt`{ok7h;Y5b*Z{');
define('SECURE_AUTH_SALT',  'K0:[Nz~YMKGr.{Qz 9*?J)qK9UBA*J<AzQaR^r5zC2oNDtisT>N1CB%p],,&J# R');
define('LOGGED_IN_SALT',    's6Z?v)aC:cIzsA%mYO/c#jN:y_wdx2Mv<aWu}A<-@9_a9fB*,XM[v1onDG2:rp?k');
define('NONCE_SALT',        '#k]b^,I$6>e1hB8]<B|M1!SYC$fY`JoIlo.E%P@e_1;M}z0D(C_HM=zw#E`uixXN');
define('WP_CACHE_KEY_SALT', 'qj.g;JQ9J>}T}^6rERw^h(nwS/4xIop;IJ[?.NWR4+Gr_uf%w~m}(n+.K`f*?)*U');


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
if (!defined('WP_DEBUG')) {
	define('WP_DEBUG', true);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
