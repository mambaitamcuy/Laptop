<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    */
    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */
    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        // Koneksi Standar / Default (Menuju Operasional)
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'arkadialp_oltp'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // 1. KONEKSI UTAMA OPERASIONAL (OLTP)
        'mysql_oltp' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_OLTP', '127.0.0.1'),
            'port' => env('DB_PORT_OLTP', '3306'),
            'database' => env('DB_DATABASE_OLTP', 'arkadialp_oltp'),
            'username' => env('DB_USERNAME_OLTP', 'root'),
            'password' => env('DB_PASSWORD_OLTP', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        // 2. ALIAS KONEKSI UNTUK DASHBOARD OPERASIONAL
        'oltp' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_OLTP', '127.0.0.1'),
            'port' => env('DB_PORT_OLTP', '3306'),
            'database' => env('DB_DATABASE_OLTP', 'arkadialp_oltp'),
            'username' => env('DB_USERNAME_OLTP', 'root'),
            'password' => env('DB_PASSWORD_OLTP', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        // 3. KONEKSI DATA WAREHOUSE (DWH) - DIKUNCI KE SINI
        'mysql_dwh' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_DWH', '127.0.0.1'),
            'port' => env('DB_PORT_DWH', '3306'),
            'database' => env('DB_DATABASE_DWH', 'arkadialp_dwh'),
            'username' => env('DB_USERNAME_DWH', 'root'),
            'password' => env('DB_PASSWORD_DWH', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    */
    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USER'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USER'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],

];
