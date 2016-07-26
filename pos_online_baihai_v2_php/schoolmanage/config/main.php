<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [

    'id' => 'app-schoolmanage',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'name' => '学校管理',
    'controllerNamespace' => 'schoolmanage\controllers',
    'bootstrap' => ['log'],
    'modules' => [

        'posweb' => [
            'class' => 'schoolmanage\modules\posweb\Module',
        ]

        ,
        'jf' => [
            'class' => 'schoolmanage\modules\jf\Module',
        ]

        ,
        'exam' => [
            'class' => 'schoolmanage\modules\exam\Module',
        ],
        'statistics' => [
            'class' => 'schoolmanage\modules\statistics\Module',
        ],
        'personnel' => [
            'class' => 'schoolmanage\modules\personnel\Module',
        ],
        'shortboard' => [

            'class' => 'schoolmanage\modules\shortboard\Module',

        ],
        'organization' => [

            'class' => 'schoolmanage\modules\organization\Module',

        ]
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //‘enableStrictParsing‘ => false,
//            'suffix' => '.htm',
            'rules' => [
                '<controller:\w+>/<action:\w+(-\w+)*>' => '<controller>/<action>',

              '<module:exam>/<id:\d+>' => '<module>/default/index',
            '<module:exam>/<id:\d+>/<action:\w+(-\w+)*>' => '<module>/default/<action>',

//                '<module:exam>/<id:\d+>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
//                '<module:(exam)>/<id:\d+>' => '<module>/default/index',
                '<module:\w+>/<controller:\w+>/<action:\w+(-\w+)*>' => '<module>/<controller>/<action>',
            ],
        ],
        'user' => [
            'identityClass' => 'schoolmanage\models\User',
            'enableAutoLogin' => true,
        ],
//        'authManager' => [
//            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
//            'defaultRoles' => ['guest'],
//        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => false,
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,

            ],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html','VarDumper'=>'\yii\helpers\VarDumper'],
                    'functions'=>['publicResources_new'=>'publicResources_new']
                ],
                // ...
            ],
        ],
    ],

    'params' => $params,
];
