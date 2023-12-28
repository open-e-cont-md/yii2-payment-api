<?php

namespace openecontmd\payment_api;

use yii\base\BootstrapInterface;
use Yii;
use yii\base\Theme;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {


        if ($app->id == 'app-frontend') {

            $app->controllerNamespace = 'openecontmd\payment_api\controllers';
            //echo "<plaintext>"; var_dump($app->controllerNamespace);exit;

            /*
             * Определение пути к папке с шаблоном - backend
             * (вместо указания в файле config/main.php
             */
            $app->view->theme = new Theme([
                'pathMap' => [
                    '@app/views' => [ '@vendor/open-e-cont-md/yii2-payment-api/views' ]
                ]
            ]);
            //echo "<plaintext>"; var_dump($app->view->theme);exit;

            /*
             * Регистрация своих маршрутов
             * (вместо указания в файле config/main.php
             */
            $app->getUrlManager()->addRules([
                ""            => "site/index",
                "maib/test"            => "site/test",
                //"maib/payment"            => "site/payment",

                "maib/payment/<key:\w+>"            => "maib/payment",
                //"maib/payment/<key:\w+>"            => "site/payment",

                "maib/success"            => "site/success",

                "test"            => "site/test",
                '<controller:\w+>' => '<controller>/index',
//                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'
            ], false);

            /*
             * Регистрация обработчика ответа
             * (вместо указания в файле config/main.php
             */
            $app->set('response', [
                'class' => 'yii\web\Response',
                'format' =>  \yii\web\Response::FORMAT_JSON,
                'on beforeSend' => function ($event) {
                    $response = $event->sender;
                    $response_data = $response->data;
//echo "<plaintext>"; var_dump($event); exit;

                    if (is_array($response_data)) {
                        $response->data = [
                            'status' => $response->isSuccessful ? 'OK' : 'FAIL',
                        ];
                        if (Yii::$app->session->get('session_id')) {
                            $response->data['session_id'] = Yii::$app->session->get('session_id');
                        }
                        $response_data['name'] = 'Anonimous';
                        $response->data['data'] = $response_data;
                        $response->statusCode = 200;
                        $response->format = \yii\web\Response::FORMAT_JSON;
                    } else {
                        $response->data = $response_data;
                        $response->format = \yii\web\Response::FORMAT_HTML;
                    }
                }
                ]);

        }

        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле config/web.php
         */
        $app->setModule('payment', 'openecontmd\payment_api\modules\payment\Module');
    }
}
