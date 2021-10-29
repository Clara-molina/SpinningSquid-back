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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'spinningsquid' );

/** MySQL database username */
define( 'DB_USER', 'spinningsquid' );

/** MySQL database password */
define( 'DB_PASSWORD', 'spinningsquid' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
    define('AUTH_KEY',         '$X$rtxTL~t ap7>0ys+_m+?wN,&jD[aP<>@m&-x4^K-Tae2A~rrSDf{| [=Oe/wj');
    define('SECURE_AUTH_KEY',  ':%TmYk)hz+87iqxmu,*m7LElGr),F{F4t#9K/#(*)WR`}lj<5yt_k=ZD42GmH}(*');
    define('LOGGED_IN_KEY',    '#( )S2$,4|T*Mm[h5rC=>t768E#E5ACH|h-xBS}S&J)0(|W+,#nKA)9-GP.mFx8b');
    define('NONCE_KEY',        '+hxGM~;-voz<yt @0v1<7qQf|`}69epRI[y;T_T=!I|_.++vO(z|*|f~yY]=NJy?');
    define('AUTH_SALT',        '}(.h!yceC_.D<47oC6Vi:TG<mllJDe44@Us6k@vm-5jpfQ#l51-o6@}0|zdw7p ]');
    define('SECURE_AUTH_SALT', '~K]d*&h:Y<8fQxO#4|a9oX0Lh@e1W9Xe[&n.-h{q+T78L[#M6:~vlh7.1 3wBYC6');
    define('LOGGED_IN_SALT',   ';K`^]*by0E]W/P]O*tO%G_?%d3Q*J,xizQRj07$rwRhL9_2F$<p,4~uks^1?]f8W');
    define('NONCE_SALT',       'f:5i~eLI5[+4qeD6|yKLp|ue>O=^<k^Q+(Uh|UTb2 +Fe)h[{yuy/kJNF;HPovr ');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

//! url vers le dossier public de mon site
// ATTENTION A BIEN RENSEIGNER LA BONNE ADRESSE !!
define('WP_HOME', rtrim ( 'http://localhost/ProjetPro/Back/projet-skatepark/public', '/' ));


// nous spécifions dans quel dossier sont installés les fichiers de wordpress
define('WP_SITEURL', WP_HOME . '/wp');

define('WP_CONTENT_URL', WP_HOME . '/content');
define('WP_CONTENT_DIR', __DIR__ . '/content');


// on peut installer des plugins/theme directement depuis le backoffice
define('FS_METHOD','direct');


/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

