<?php

return
[
    'class' => 'yii\db\Connection',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',

    // configuration for the master
    'dsn' => 'mysql:host=192.168.1.225:8066;dbname=pos_admin_test;charset=utf8',
    'username' => 'admin',
    'password' => 'neptune@admin'
];