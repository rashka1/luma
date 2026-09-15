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
define( 'DB_NAME', 'lume_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',          ';!Sbr0</1LmjY;}h?E6 ZXkseh0DrOm<7I}i*D0nQ`(axwG)oy?`58DUhX0f41~@' );
define( 'SECURE_AUTH_KEY',   '1qMy$u%}/@/t@AH#u~a:i.4 ~$`d^1H[PZ>!v`> 9$~z2Zc.bFd2~/#$DQT8}FM4' );
define( 'LOGGED_IN_KEY',     '&5&ulhGCYT:LSzg|)CE#IgGz3:E{xG,neH:p|FZ5R39za6t_9} O:?On`HT3w@ma' );
define( 'NONCE_KEY',         '?PW/])9T<=C#}5:8Y|YWxXz yf1fW@izMaKgXjtgm6.]p9KoZv]bFl/gEKnnnpLM' );
define( 'AUTH_SALT',         '1~)Jr7ji99&Qn?xooZ,# Y65=ba[QURBb3=y11|q7&yvh)ARZF?jy)F{aQ0d3A0<' );
define( 'SECURE_AUTH_SALT',  'qA!bgr=q=@6%HItPA_9A!^lh$[nChf_T@G@_%S^d#qtq{N#3VlNoDIsc}7sEHjWx' );
define( 'LOGGED_IN_SALT',    'VY<8MJZlX@2r+`32:w_XE2@HDHKTTbD[,IS%5xWm{8E|[&Ui)T1]a:NU8Mf{WgSm' );
define( 'NONCE_SALT',        'yYOJOsWbh@URe%RmKB9~HSfU*d.v3?rw&ZA*)QAtb/$%b}@AR$7B9.lSL}d(z)O0' );
define( 'WP_CACHE_KEY_SALT', '9wzpB+6ZJ3vmp^c(HQ8.dJFRE~|p7SuaNhd([!2I8n_<I+/n.jE.`DfV>bPM`_MD' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

// work offline
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_HTTP_BLOCK_EXTERNAL', true );
define( 'WP_DEBUG', false );


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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
