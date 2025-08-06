<?php

use Root\News\NewsWidget;

    return [
        'news.prefix' => '/news',
        'admin.widgets' => \DI\add([
            \DI\get(NewsWidget::class),
        ]),
    ];
