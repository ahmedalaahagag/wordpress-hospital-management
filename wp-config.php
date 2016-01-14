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
define('DB_NAME', 'gmhc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '-`QyGx4|@R<)oJ:k9N7x$,v*<L>%L03jok*=@-gc-#!@N(E3Pl=|WQCLW3:J:9Ja');
define('SECURE_AUTH_KEY',  '+?~9iziu&Q${1_u^]-:T`-yriT9;[-_:-Jev6j~61&/o,S:Q{A9qhpm3#*{f>]<l');
define('LOGGED_IN_KEY',    'CqCw~3;|+vf?}&av]M/Qawe<?aM^2<L{m(f8JSeR%Z2Et+mCJ`Qx e~N;e5b0g-0');
define('NONCE_KEY',        'dmiQWHg~2SFJu[h#8Pp~)XZ0g;pU-!7@x7tg%gb>]+nkNj|Uq:dDs3Hkg(2j>Cp%');
define('AUTH_SALT',        ')#-WO}0Lt/zn2d+,HR_p3qAD#;|7-;E?j9%@OgN$b]^)tM#ft#7.D%j(2::l$3Qs');
define('SECURE_AUTH_SALT', '|v&|D3y?1m3d1 0iik5AJz#:1!p:}+]Ni$kwK@/4Q,T&R:lGcN41//;T|~Rg&|ws');
define('LOGGED_IN_SALT',   'Z?w,m=BGw8G$Pc10A}+Q<!*XD;+&uO}T$IcIlP,.!Bxx9yJD-US-SRVSn-g262K+');
define('NONCE_SALT',       'vvrRMyNrO~CJ:uf*L8RY_Q)QLU=-JQM1+Io<gQU+zF}@3s+8|:Q4!Hsd90sJT@X3');

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
