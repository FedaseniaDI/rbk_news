<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    // ...
    'db' => [
        'driver' => 'Pdo',
        'dsn'    => 'mysql:dbname=rbk_news;host=mysql;charset=utf8',
        'username' => 'rbk_news_user',
        'password' => 'rbk_news_user_pass'
    ],
    'session_config'  => [],
];
