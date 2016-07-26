<?php
use common\models\pos\SeUserControl;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'name' => '班海',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [

        'classes' => [
            'class' => 'frontend\modules\classes\ClassesModule',
        ],
        'classstatistics' => [
            'class' => 'frontend\modules\classstatistics\ClassstatisticsModule',
        ],
        'student' => [
            'class' => 'frontend\modules\student\StudentModule',
            // 'layout'=>'left-menu'
        ],
        'teacher' => [
            'class' => 'frontend\modules\teacher\TeacherModule',
        ]
        ,
        'terrace' => [
            'class' => 'frontend\modules\terrace\TerraceModule',
        ]
        ,
        'common' => [
            'class' => 'frontend\modules\common\CommonModule',
        ]
        ,
        'platform' => [
            'class' => 'frontend\modules\platform\PlatformModule',
        ],

        'mobiles' => [
            'class' => 'frontend\modules\mobiles\MobilesModule',
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'frontend\components\User',
            'enableAutoLogin' => true,
            'on afterLogin' =>
                function ($event) {

                    if (YII_ENV !== 'test') {
                        $user = $event->identity;

                        $db = \common\models\pos\SeUserControl::getDb();
                        /** @var TYPE_NAME $user */
                        $db->useMaster(function() use ($user){
                            $usercontrolModel = \common\models\pos\SeUserControl::find()->where('userID=:userID', [':userID' => $user->userID])->one();
                            if (empty($usercontrolModel)) {
                                $usercontrolModel = new SeUserControl();
                                $usercontrolModel->userID = $user->userID;
                                $usercontrolModel->phoneReg = $user->phoneReg;
                                $usercontrolModel->firstIP = Yii::$app->getRequest()->getUserIP();
                                $usercontrolModel->firstTime = \common\helper\DateTimeHelper::timestampX1000();
                                $usercontrolModel->firstDevice = 'web';
                                $usercontrolModel->firstFromSource = yii::$app->request->get('f');
                            }
                            $usercontrolModel->lastIP = Yii::$app->getRequest()->getUserIP();
                            $usercontrolModel->lastTime = \common\helper\DateTimeHelper::timestampX1000();
                            $usercontrolModel->save(false);

                        });


                        setcookie('BHUserID', $user->userID, time() + 3600 * 720, '/', '.banhai.com');
                        setcookie('BHUserID' . '__ckMd5', substr(md5('JZOKHyXqDMabQOioUSWG-GuCnKYRS_CX' . 'BHUserID'), 0, 16), time() + 3600 * 720, '/', '.banhai.com');
                        setcookie('BHUserName', \frontend\components\WebDataCache::getTrueName($user->userID), time() + 3600 * 720, '/', '.banhai.com');
                        setcookie('BHUserName' . '__ckMd5', substr(md5('JZOKHyXqDMabQOioUSWG-GuCnKYRS_CX' . 'BHUserName'), 0, 16), time() + 3600 * 720, '/', '.banhai.com');
                    }
                },
            'on afterLogout' =>
                function ($event) {
                    setcookie('BHUserID', '', 0, '/', '.banhai.com');
                    setcookie('BHUserID' . '__ckMd5', '', 0, '/', '.banhai.com');
                    setcookie('BHUserName', '', 0, '/', '.banhai.com');
                    setcookie('BHUserName' . '__ckMd5', '', 0, '/', '.banhai.com');
                }
        ],
        'urlManager' => [
            'enablePrettyUrl' => YII_ENV == 'test' ? false : true,
            'showScriptName' => YII_ENV == 'test' ? true : false,
            //  'suffix' => '.htm',

            'rules' => YII_ENV == 'test' ? [] : [
                '<module:classes>/<classId:\d+>' => '<module>/default/index',
                '<module:classes>/<classId:\d+>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
                '<module:teacher>/<teacherId:\d+>' => '<module>/default/index',
                '<module:teacher>/<teacherId:\d+>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
                '<module:student>/<studentId:\d+>' => '<module>/default/index',
                '<module:student>/<studentId:\d+>/<action:\w+(-\w+)*>' => '<module>/default/<action>',
                '<module:(teacher|student|ku)>/<id:\d+>' => '<module>/default/index',
                '<controller:class>/<classId:\d+>/<action:\w+(-\w+)*>' => '<controller>/<action>',
                '<controller:teachgroup>/<groupId:\d+>/<action:\w+(-\w+)*>' => '<controller>/<action>',
                '<controller:school>/<schoolId:\d+>/<action:\w+(-\w+)*>' => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+(-\w+)*>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+(-\w+)*>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+(-\w+)*>' => '<module>/<controller>/<action>',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'system'=>
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                'traceweb'=>
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'levels' => ['info'],
                    'categories'=>['traceweb'],
                    'logFile'=>'@app/runtime/logs/traceweb.log'.date('Ymd'),
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
    ],
    'params' => $params,
];
