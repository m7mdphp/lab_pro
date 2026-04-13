<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace'   => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    'channels' => [

        'stack' => [
            'driver'            => 'stack',
            'channels'          => explode(',', env('LOG_STACK', 'single')),
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver'               => 'single',
            'path'                 => storage_path('logs/laravel.log'),
            'level'                => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver'               => 'daily',
            'path'                 => storage_path('logs/laravel.log'),
            'level'                => env('LOG_LEVEL', 'debug'),
            'days'                 => env('LOG_DAILY_DAYS', 14),
            'replace_placeholders' => true,
        ],

        'stderr' => [
            'driver'       => 'monolog',
            'level'        => env('LOG_LEVEL', 'debug'),
            'handler'      => StreamHandler::class,
            'handler_with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'null' => [
            'driver'  => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        // Migration pipeline — writes to storage/logs/migration/migration.log
        // Set MIGRATION_LOG_LEVEL=debug in .env for verbose per-URL output.
        'migration' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/migration/migration.log'),
            'level'  => env('MIGRATION_LOG_LEVEL', 'info'),
            'days'   => 14,
        ],

    ],

];
