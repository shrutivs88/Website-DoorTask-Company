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
define('DB_NAME', 'i3599506_wp10');

/** MySQL database username */
define('DB_USER', 'i3599506_wp10');

/** MySQL database password */
define('DB_PASSWORD', 'G.aDXnYCX1mnP9gVtYT64');

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
define('AUTH_KEY',         '6ge8ddmFcvC7EFgqroxZvSYf2XwUmDQqf8z0KTaTKpmlZEvQba8H1ckzxm2zOcRt');
define('SECURE_AUTH_KEY',  '7fM83llSpGdAhm5Ao7cHe7N0Epd6S4OfbNKY3OGxBhJE6Qjy5WRRZvf1X2k1DLay');
define('LOGGED_IN_KEY',    '0FSlGf8GrEbSyDaZyFnHaZb41oYifO5IlnZWQgp1XBUrJlOKgW5QxFBXEJzaCDrc');
define('NONCE_KEY',        'jQ38DW6v9yqUyhyOCmBDjt9XDa1FhGzeHyYxQLMkIOFsjpqfnQCKpyO3lSSYLZ4F');
define('AUTH_SALT',        '7DAE1snPvdZ9mMdIWxeIYE7XcRlDnSPxBLKJfIYar903huCvx42EEb8iId50psWD');
define('SECURE_AUTH_SALT', 'nnXN31jMvnfExTi0jYKOCeeXJqD4frzlozUtHjg6NLZ2UGtDkpxsH9ELnXmSpfBp');
define('LOGGED_IN_SALT',   'J82J4iKdeXgax52JPyzJyREyV9j8nxjYs7djuOpIkxpKqMmmOOTC69L5qbY28QXf');
define('NONCE_SALT',       '0T6pJ7LVONgopxANeIgYw9IMONV6y7loJnCIih9DJ9QJhyTkm6qSIWQQ0KxlsptY');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
