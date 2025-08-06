<?php

use Root\Admin\AdminModule;
use Root\Admin\DashboardAction;
use Root\Admin\AdminTwigExt;

    return [
        'admin.prefix' => '/admin',
        'admin.widgets' => [],
        AdminTwigExt::class => \DI\create()->constructor(\DI\get('admin.widgets')),
        AdminModule::class => \DI\autowire()->constructorParameter('prefix', \DI\get('admin.prefix')),
        DashboardAction::class => \DI\autowire()->constructorParameter('widgets', \DI\get('admin.widgets')),
    ];
