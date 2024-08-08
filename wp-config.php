<?php
define( 'SBI_ENCRYPTION_KEY', 'dY@Zh%sYV)DgreCDGSwk@dQw0E5vrzca8Z98LJqi^&FiyUR1@9wjMwcnNQ@BsCi!' );

define( 'SBI_ENCRYPTION_SALT', 'rMQUcvzDj82VCvpdKWw&4F6%M$bsZ^mxIFCdLcW0@vEGGtER7csoONrFs@Sn#Z!^' );

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

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '' );

/** Database username */
define( 'DB_USER', '' );

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
define( 'AUTH_KEY',         'U1cz4v;Dot/)C}~8^t}v~CI$_X4yW$#:A()+7&<|QQ3lJxSR,YvkwOrNamt1_xo)' );
define( 'SECURE_AUTH_KEY',  'e&%{!+B~:c:}>*JRQK!_N,Z|jfD[R#,itAe=6b;i:nBqX/r`T&eRl Ma>HD9~HR.' );
define( 'LOGGED_IN_KEY',    ',5`tQLw1!UAX~DJQ%%aH=bUiT T_?$xSlxGq%l=*TV__&nk;Q57h+H?]sm.q+%9z' );
define( 'NONCE_KEY',        'EA1_l(pv8a3|`S$Yq5E<R2Y nWP#58m/oBz!k0<+Scc1_br;/CfGO4YL0 RZt{8R' );
define( 'AUTH_SALT',        '/pn#/XXcj5NGt~MjSG<SSl4COBu;zxx0OK4VM@u//7RZM1;D%HxEx>fF2lqbNeW%' );
define( 'SECURE_AUTH_SALT', '@m]Oj,q&PfUL(ov.ata~hRTKmcPI>D:xz0bMk/Q$[tE![+/<eMl|YzCq2Zn+DF?T' );
define( 'LOGGED_IN_SALT',   '.*yJA]t2zar0xAjg9>8C:HW-in)f?q~bH0Jn[-,a0rA ~$M^._yUz1VdF:l!OnuC' );
define( 'NONCE_SALT',       ')9fuJmdP.+sZ+kGXWn:kO.42Su[IrV6%V#v$~vdvG$`AT_u4D_Ef0l9iMV&LqKC;' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'frekenbok_';

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

// theme
define( 'WP_DEFAULT_THEME', 'frekenbok' );

// general changes
define('WP_POST_REVISIONS', 0);
define('AUTOSAVE_INTERVAL', 3600);
define('EMPTY_TRASH_DAYS', 3600);

// disabling updates
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);

// allow svg
define('ALLOW_UNFILTERED_UPLOADS', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
