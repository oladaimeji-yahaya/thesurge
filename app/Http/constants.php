<?php

/**
 * User roles
 */
define('USER_STATUS_ACTIVE', 1);
define('USER_STATUS_SUSPENDED', 0);

/**
 * Wallet states
 */
define('BUCKET_STATUS_ACTIVE', 1);
define('BUCKET_STATUS_FROZEN', 0);

/**
 * Alias for PHP constant <code>DIRECTORY_SEPARATOR</code>
 */
define('DS', DIRECTORY_SEPARATOR);


/**
 * Models to string mapping
 */
define('MODEL_MAP', [
    'user' => App\Models\User::class,
]);
