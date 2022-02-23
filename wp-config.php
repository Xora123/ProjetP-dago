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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'GCPYx1N3tbd9fW1paA+1lAogfQ78JNPlgaILBU/GK3XGzJ8XMqxnHjA7yugGDDNFiFGFijVTjXCr/u+/NS6l+Q==');
define('SECURE_AUTH_KEY',  'IAroOFAgFsPNMVkv3QWyzrkfpyRdXYjKFmVVirTXu+fsBsVQBiqV3+LA750O/AhFbB/QCKeJykF7/S4flnjFUA==');
define('LOGGED_IN_KEY',    'mPw0PPz9utGFakqwditc04ZtwM5o5/hwm5KSCbyn3qqOu+w0jm9UFJXZsMD+3qLiyrNewpX34JlLAkdd/H6HSQ==');
define('NONCE_KEY',        'PD6+QRsGdM/NHNhxIq8UyquKnFOUviliaqIjQocT/L6DyTcTyoM6uHLNHA+/JUJDzjoUMGowd2w0Z8XsVHuIEQ==');
define('AUTH_SALT',        'vuWR5VZw6KDj3jwt1I8aELLxzrej2skNSFsGK/Tkmy62tX5cRX2hNf8h6Pqelg6M8kX4a7qRwwxruR6PG+1iWw==');
define('SECURE_AUTH_SALT', '2ziegr8+5jlT5SqjPI6OAqT0D5gCIlcY9TRpjdBmDnyncQ5tS5CcoRyvhw3cGye+wsU/kthksDzCnWtWmN5dRw==');
define('LOGGED_IN_SALT',   'nScWfjIS8SlnK+bMYE3RjeYx61zAoSq9NPVCUZcPAHYFbiq6Ukh2upiyumQB8ubc2qBbUqDCRulpbeoWvTiRng==');
define('NONCE_SALT',       'xYyesS/U6kmZlzQi3HXJdNldG5OvfIdKElJVeXi1CT4Vmm6Pfl6WPS2Lmkb9VTBqIacRNpwmfShZrpXDK7H/TQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
