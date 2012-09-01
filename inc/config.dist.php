<?php

define('CI_TITLE', 'Cruel Image Host');
define('CI_THEME', 'chevereto');
define('CI_DOMAIN', 'http://images.cruels.net');
define('CI_BASEURL', '/'); // Must end with trailing slash if in subdirectory (ie. '/imagehoster/') 

define('CI_MAX_FILESIZE', '4M'); // Must match maxFileSize value in upload.js
define('CI_MAX_IMAGE_WIDTH', NULL);
define('CI_THUMBNAIL_WIDTH', 200);

// Can be either a single image or a directory for random 404 image cycling
define('CI_IMAGE_404', '/static/404.png');

// Database Configuration
define('CI_DB_TYPE', 'mysql'); // db2, mssql, mysql, oracle, postgresql, sqlite
define('CI_DB_NAME', 'cruelimage');
define('CI_DB_USER', 'root');
define('CI_DB_PASS', '');
define('CI_DB_HOST', NULL); // NULL for default (localhost)
define('CI_DB_PORT', NULL); // NULL for default

define('CI_APIKEY', '392d130b2f90eccc8ca1a45849116c68');

define('CI_TIMEZONE', 'America/New_York'); // http://php.net/manual/en/timezones.php
define('CI_DATEFORMAT', 'n/j/y'); // http://php.net/manual/en/function.date.php

// DON'T TOUCH THESE
// Unless you know how they work
define('CI_UUID_CHARS', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
define('CI_UUID_LENGTH', 4);
define('CI_UPLOAD_DIR', DOC_ROOT.'/uploads/');
define('CI_IMAGE_EXPIRATION', 31536000); // 365 days