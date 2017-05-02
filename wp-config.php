<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', '0bcf6ea9b50e514438b56a910ea7574b20815da35f2ed2e4');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'X>Td1F|I>TGHgg2Xm$>_`N$JfN{Fv7;beHZ5]VpX07nAYA>aEYfSD}1.c6M m|^s');
define('SECURE_AUTH_KEY',  'K#qi3AgtfiWI7>TDFY/]Rd0gp}_2.(WiLb]sCg<-WOt{9Y<Is0Sis77T7gekkhvj');
define('LOGGED_IN_KEY',    'aR%uUvdK}(:7.O<FrTZ%JA@f9H/sOQD/ EJ=:L.j)7*zfb%#:hW8 ZR!_]7uk9R-');
define('NONCE_KEY',        '?0_P>^y%MmjQFR?J&0qUX}0NH5,6dr(s{yj@F 74jbbY+uGMs`s= @mL}2deo}_f');
define('AUTH_SALT',        'iPr:R|}F5@*CKYO{k%YCMFFgaIxNl0W^3B#W91y$m^ZizfK:OuQw4~h=JA!BA|dQ');
define('SECURE_AUTH_SALT', '5bsW !}2zA{Uep@U*U?9C/-4=nR!|Z5VaM,{40WL.eK,fsy=t7kr41zFL?-,F|J%');
define('LOGGED_IN_SALT',   '=kZ520AS8A+8,Fdo>@u8 _,+,o(n|k*%1o(+V/!>??/Km#v3(xpeP7/7fIq#y_fV');
define('NONCE_SALT',       '6lc6Q3i5m,^|ew*8r<[9/!O__zY;;6|B+3%na6vZ{j8n+??wJ17.r/o:7qDZlRPz');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
