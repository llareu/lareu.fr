<?php

require 'public/index.php';

$migrations = [];
$seeds = [];

foreach ($modules as $module) {
    if($module::MIGRATIONS) {
        $migrations[] = $module::MIGRATIONS;
    }
    if($module::SEEDS) {
        $seeds[] = $module::SEEDS;
    }
}


return
[
    'paths' => [
        'migrations' => $migrations,
        'seeds' => $seeds
    ],
    'environments' => [
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $app->getContainer()->get('db.host'),
            'name' => $app->getContainer()->get('db.name'),
            'user' => $app->getContainer()->get('db.user'),
            'pass' => $app->getContainer()->get('db.pass'),
            'port' => $app->getContainer()->get('db.port'),
            'charset' => $app->getContainer()->get('db.charset')
        ]
    ]
];
