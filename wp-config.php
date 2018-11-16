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
define('DB_NAME', 'enterpreneur');

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
define('AUTH_KEY',         '2X)2Z!/=t/3Iwq*!$^6_R.$$a+2C}$jeW5!RP>}U5p3<tPYv~h<gyZzMS$,1but_');
define('SECURE_AUTH_KEY',  'yU:8:+4m9BSiAyYt{3m3;$m8;hz{}GlTK/H$C1}iYIsUv8u;i6y5%eGAdM*p36[A');
define('LOGGED_IN_KEY',    '_;~JnDMmZ!c[TUcK`<oM3B4xx-R|-KM^E`h&Vb79{C+PDWXMU)<OM=zJ8)G?X.h;');
define('NONCE_KEY',        '>q*0o|zRcAZJ~!YOa^6PS1b&U#%&,W dR)^7H`G!ir!?2#-KVfY[BRaaTXb0Srip');
define('AUTH_SALT',        'zjI]anZ}o8V+|}>p>VH}OKrftQ5V-zo_uh)M4I[0,bX>Bg:{$g];CoZ5K4&Mvb8t');
define('SECURE_AUTH_SALT', 'LEDksLY6U*r[P1 :aOwgCF2E4S`@hE,I|C 5o]@)|)r3$n73uO%*>1G4BmH~rf%S');
define('LOGGED_IN_SALT',   'iY-E.HVpe#,BdM2MEDy:e>!X^BrY@KY=n2eGNeF#lgus;5 D.-gF40rOyLlqUKcC');
define('NONCE_SALT',       'n2_P=o 1!,rgknE_=(rQdixf9MhbE~42P`<gIqTm@|Q^7M:O-|*Q{53_C(^]`I4I');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
