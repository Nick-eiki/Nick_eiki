<?php
return [
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '192.168.1.48:9200'],
                ['http_address' => '192.168.1.40:9200'],
                ['http_address' => '192.168.1.41:9200'],
                ['http_address' => '192.168.1.42:9200']
                // configure more hosts if you have a cluster
            ],

        ],
        'db' => require(__DIR__ . '/db.php'),
        'db_school' => require(__DIR__ . '/db_school.php'),
        'db_sanku' => require(__DIR__ . '/db_sanku.php'),
        'db_school_manage' => require(__DIR__ . '/db_school_manage.php'),
        'db_databusiness' => require(__DIR__ . '/db_databusiness.php'),

        'session' => [
            'class' => 'yii\redis\Session'
        ],
    ]
];
