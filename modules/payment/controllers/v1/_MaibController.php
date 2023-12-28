<?php
//namespace frontend\controllers;
//namespace openecontmd\payment_api\controllers;
namespace openecontmd\payment_api\modules\payment\controllers\v1;


use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use openecontmd\backend_api\models\ApiInvoice;
//use openecontmd\payment_api\models\Customer;
use openecontmd\backend_api\models\Content;
use openecontmd\backend_api\models\Terms;
//use openecontmd\payment_api\controllers\MaibEcomm;
//use openecontmd\payment_api\components\MassMail;

use openecontmd\backend_api\models\Client;

use function Symfony\Component\Debug\headers_sent;

/**
 * Site controller
 */
class MaibController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'index' => ['get', 'post'],
                    'check' => ['get', 'post'],
                    'order' => ['get', 'post'],
                    'pay' => ['get', 'post'],
                    'return' => ['get', 'post'],
                    'run' => ['get', 'post'],
                    'success' => ['get', 'post'],
                    'unsuccess' => ['get', 'post'],
                    'reverse' => ['get', 'post'],
                    'error' => ['get', 'post'],
                    'arch' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
/*
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
*/
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $business = 'mychauffeurdrive';
        $order = Yii::$app->request->post('order');
        $name = Yii::$app->request->post('name');
        if (Yii::$app->request->post()) {
            $lang = Yii::$app->request->post('lang');
        } else {
            $lang = Yii::$app->language;
        }
        //var_dump($business, $order, $name); exit;
        $context = Client::getContext($business);

        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;


        $favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
//var_dump($context['business'][0]['ImageURLi'], $file); exit;
        //$picture = @file_get_contents($file, 'r');
        //$type = pathinfo($file, PATHINFO_EXTENSION);
        //$logo = 'data:image/' . $type . ';base64,' . base64_encode($picture);
        $logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        //$favicon = Yii::$app->params['image_url'].'/images/ut_business/'.($context['business'][0]['ImageURLv1'] != '' ? $context['business'][0]['ImageURLv1'] : $context['business'][0]['ImageURLv2']);
        Yii::$app->params['favicon'] = $favicon;
//var_dump($business, $b); exit;

        $this->layout = 'simple';

//            if (Yii::$app->request->post()) {
        $file = Yii::$app->params['image_url'].'images/ut_business/'.$context['business'][0]['ImageURL2'];
//        $picture = @file_get_contents($file, 'r');
//        $type = pathinfo($file, PATHINFO_EXTENSION);
//        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($picture);
//        $doc = Terms::getBusinessDoc($business, 'welcome', $lang);

        $docs = Content::getPageList('help', 'business', $lang);




//var_dump($docs); exit;

/*
                //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'title' => trim(json_decode($doc['Title'])->{$lang}),
                    'description' => json_decode($doc['Description'])->{$lang},
                    'content' => $this->renderPartial('order', [
                        'lang' => $lang,
                        'order' => $order,
                        'name' => $name,
//                        'invoice_num' => $invoice_num,
                        'context' => $context,
                        'visual' => $base64,
                        'logo' => $logo,
                    ], true)];
*/
//            } else {
/*
                return $this->render('order', [
                    'lang' => $lang,
                    'order' => $order,
//                    'invoice_num' => $invoice_num,
                    'name' => $name,
                    'context' => $context,
                ]);
*/
                return $this->render('index', [
//                'key' => $key,
                'lang' => $lang,
                'context' => $context,
                'order' => '',
//                'factura' => $factura,
                'name' => $name,
                'logo' => $logo,
                'favicon' => $favicon,
                'visual' => $file,
                'docs' => $docs
//                'visual' => '/img/visual_1.jpg',
                ]);

//            }

    }

    public function actionPricing()
    {
        return $this->render('pricing');
    }

    public function actionTest()
    {
        $this->layout = 'simple';
        return $this->render('test');
    }

    public function actionContent($alias)
    {
        $business = 'mychauffeurdrive';
        $lang = Yii::$app->language;
        $context = Client::getContext($business);

        $favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        $logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        Yii::$app->params['favicon'] = $favicon;
        $file = Yii::$app->params['image_url'].'images/ut_business/'.$context['business'][0]['ImageURL2'];
        $docs = Content::getPageList('help', 'business', $lang);
//var_dump($docs);exit;
        $this->layout = 'simple';
        return $this->render('content', [
            //                'key' => $key,
            'lang' => $lang,
            'context' => $context,
            'order' => '',
            'alias' => $alias,
//            'key' => $key,
            'logo' => $logo,
            'favicon' => $favicon,
            'visual' => $file,
            'docs' => $docs
            //'visual' => '/img/visual_1.jpg',
        ]);
    }

    public function actionError($alias = 'error')
    {
        $business = 'mychauffeurdrive';
        $lang = Yii::$app->language;
        $context = Client::getContext($business);
//var_dump($context);exit;
        $favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        $logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        Yii::$app->params['favicon'] = $favicon;
        $file = Yii::$app->params['image_url'].'images/ut_business/'.$context['business'][0]['ImageURL2'];
        $docs = Content::getPageList('error', 'site', $lang);
//var_dump($docs);exit;
        $this->layout = 'simple';
        return $this->render('error', [
            //                'key' => $key,
            'lang' => $lang,
            'context' => $context,
            'order' => '',
            'alias' => $alias,
            //            'key' => $key,
            'logo' => $logo,
            'favicon' => $favicon,
            'visual' => $file,
            'docs' => $docs
            //'visual' => '/img/visual_1.jpg',
        ]);
    }


    public function actionCheck()
    {
        $lang = Yii::$app->language;
        $order = trim(Yii::$app->request->post('order'));
        $name = trim(Yii::$app->request->post('name'));

        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE
                (outer_number = '".addslashes($order)."') AND ( (buyer_name LIKE '%".addslashes($name)."%') OR (contact_phone LIKE '%".addslashes($name)."%') )
                AND (status <> 'draft') ORDER BY moment DESC LIMIT 1")->queryOne();
        if ($invoice)
            return Yii::$app->getResponse()->redirect('/'.$lang.'/order/'.$invoice['inner_hash']);
        else
        {
            Yii::$app->session->setFlash('danger', Yii::t('apl', 'No such order!' /*Terms::translate('wrong_email', 'login_form')*/));
            return Yii::$app->getResponse()->redirect('/'.$lang);
        }

    }

    public function actionSuccess($key = null)
    {
        $lang = Yii::$app->language;
        if (!$key) return Yii::$app->getResponse()->redirect('/'.$lang);
        $this->layout = 'simple';
        $business = 'mychauffeurdrive';
        $context = Client::getContext($business);
        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;
        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE
                (inner_hash = '".addslashes($key)."')
                ORDER BY moment DESC LIMIT 1")->queryOne();
        $factura = @json_decode($invoice['json_data'])->Document;
        $status = Client::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{$lang};
        $favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        $logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        Yii::$app->params['favicon'] = $favicon;
        $this->layout = 'simple';
        $file = Yii::$app->params['image_url'].'images/ut_business/'.$context['business'][0]['ImageURL2'];

        $result = 'success';
        return $this->render('check', [
            'lang' => $lang,
            'context' => $context,
            'order' => $invoice,
            'factura' => $factura,
            'key' => $key,
            'logo' => $logo,
            'favicon' => $favicon,
            'visual' => $file,
            'result' => $result
        ]);
    }

    public function actionUnsuccess($key = null)
    {
        $lang = Yii::$app->language;
        if (!$key) return Yii::$app->getResponse()->redirect('/'.$lang);
        $this->layout = 'simple';
        $business = 'mychauffeurdrive';
        $context = Client::getContext($business);
        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;
        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE
                (inner_hash = '".addslashes($key)."')
                ORDER BY moment DESC LIMIT 1")->queryOne();
        $factura = @json_decode($invoice['json_data'])->Document;
        $status = Client::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{$lang};
        $favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        $logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        Yii::$app->params['favicon'] = $favicon;
        $this->layout = 'simple';
        $file = Yii::$app->params['image_url'].'images/ut_business/'.$context['business'][0]['ImageURL2'];

        $result = 'unsuccess';
        return $this->render('check', [
            'lang' => $lang,
            'context' => $context,
            'order' => $invoice,
            'factura' => $factura,
            'key' => $key,
            'logo' => $logo,
            'favicon' => $favicon,
            'visual' => $file,
            'result' => $result
        ]);
    }


    public function actionOrder($key = null)
    {
        $lang = Yii::$app->language;
//var_dump($lang, $key); exit;
        if (!$key) return Yii::$app->getResponse()->redirect('/'.$lang);

        $this->layout = 'main';

        //$business = '2ea9685ae0420f057f98b0dbc2d55ad0';
        ///$order = Yii::$app->request->post('order');
        //$name = Yii::$app->request->post('name');
        /*
         if (Yii::$app->request->post()) {
         $lang = Yii::$app->request->post('lang');
         } else {
         $lang = Yii::$app->language;
         }
         */


//var_dump($business, $order, $name); exit;
//        $context = Customer::getContext($business);
//var_dump($business, $context); exit;
//        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
//        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;

        //        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
        //        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (outer_number = '{$order}') AND (buyer_name LIKE '%{$name}%')")->queryOne();
        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."') ORDER BY moment DESC LIMIT 1")->queryOne();
        $factura = @json_decode($invoice['json_data'])->Document;
//echo "<pre>"; var_dump($business, $invoice, $factura); exit;

//        echo "<pre>"; var_dump($invoice['json_data'], json_decode($invoice['json_data'], JSON_PRETTY_PRINT)); exit;

        //        echo "<pre>"; var_dump("SELECT * FROM ut_factura WHERE                (outer_number = '".addslashes($order)."') AND                (buyer_name LIKE '%".addslashes($name)."%')", $invoice, $business, $context); echo "</pre>"; exit;
        $status = ApiInvoice::getStatusCaption($invoice['status'])['caption'];
        //echo "<pre>"; var_dump($lang, $status, json_decode($status)); echo "</pre>"; exit;
        $status = json_decode($status)->{$lang};

//echo "<pre>"; var_dump($status); echo "</pre>"; exit;



        //$file = Yii::$app->params['image_url'].'/images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        //$favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        //var_dump($context['business'][0]['ImageURLi'], $file); exit;
        //$picture = @file_get_contents($file, 'r');
        //$type = pathinfo($file, PATHINFO_EXTENSION);
        //$logo = 'data:image/' . $type . ';base64,' . base64_encode($picture);
        //$logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        //var_dump($business, $b); exit;
        //Yii::$app->params['favicon'] = $favicon;



        //            if (Yii::$app->request->post()) {
        //$file = Yii::$app->params['image_url'].'images/ut_business/'.$context['business'][0]['ImageURL2'];
        //        $picture = @file_get_contents($file, 'r');
        //        $type = pathinfo($file, PATHINFO_EXTENSION);
        //        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($picture);
        //        $doc = Terms::getBusinessDoc($business, 'welcome', $lang);
        //var_dump($business, $file, $base64); exit;


        $this->layout = 'main';
        $result = 'check';

//echo "<pre>"; var_dump($invoice); echo "</pre>"; exit;

        return $this->render('payment', [
            'key' => $key,
            'lang' => $lang,
//            'context' => $context,
            'order' => $invoice,
            'factura' => $factura,
//            'logo' => $logo,
//            'favicon' => $favicon,
//            'visual' => $file,
            'result' => $result
        ]);

//var_dump($business, $order, $name); exit;
        //return $this->render('check');
    }



    public function _actionCheck($business = '', $key = '')
    {
        if (Yii::$app->request->post()) {
            $business = Yii::$app->request->post('business');
            $key = Yii::$app->request->post('key');
            $lang = Yii::$app->request->post('lang');
        } else {
            $lang = Yii::$app->language;
        }

        $context = Client::getContext($business);
        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;

        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
        $factura = @json_decode($invoice['json_data'])->Document;
        //echo "<pre>"; var_dump(Yii::$app->language, $invoice, $business, $key, $context); echo "</pre>"; exit;
        $status = Client::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{$lang};

        $file = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURL2']);
        $picture = @file_get_contents($file, 'r');
        $type = pathinfo($file, PATHINFO_EXTENSION);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($picture);

        $file = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv1']);
        $picture = @file_get_contents($file, 'r');
        $type = pathinfo($file, PATHINFO_EXTENSION);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($picture);
        /*
         if ($invoice)
         {
         $queryUrl = 'https://b24-f2n931.bitrix24.ru/rest/1/ety1zzhiyf4gh5oo/crm.invoice.get';
         $queryData = http_build_query(
         array(
         "id" => $invoice['outer_number']
         )
         );
         $curl = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_POST => 1,
         CURLOPT_HEADER => 0,
         CURLOPT_RETURNTRANSFER => 1,
         CURLOPT_URL => $queryUrl,
         CURLOPT_POSTFIELDS => $queryData,
         ));
         $result = curl_exec($curl);
         curl_close($curl);
         $result = json_decode($result, true);
         //var_dump($queryData, $result); exit;
         }
         else
         {
         $result = null;
         }
         */
        /*
         require(__DIR__ . '/../helpers/helpers.php');
         $victoriaBankGateway = new VictoriaBankGateway();
         $certDir = __DIR__ . '/../..';
         $dotenv = Dotenv::createImmutable($certDir);
         $dotenv->load();
         $victoriaBankGateway->configureFromEnv($certDir);
         */
        //var_dump($certDir, $victoriaBankGateway); exit;

        $this->layout = 'simple';
        if (Yii::$app->request->post()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('check', [
                'key' => $key,
                'lang' => $lang,
                'context' => $context,
                'invoice' => $invoice,
                'factura' => $factura,
                'status' => $status,
                'logo' => $logo,
                'visual' => $base64,
                //                'victoriaBankGateway' => $victoriaBankGateway
            ], true);
        } else {
            if ($b['alias'] != 'wiza') {
                return $this->render('check', [
                    'key' => $key,
                    'lang' => $lang,
                    'context' => $context,
                    'invoice' => $invoice,
                    'factura' => $factura,
                    'status' => $status,
                    'logo' => $logo,
                    'visual' => $base64,
                    //                    'victoriaBankGateway' => $victoriaBankGateway
                ]);
            }
            else
            {
                return $this->render('old_check', [
                    'key' => $key,
                    'lang' => $lang,
                    'context' => $context,
                    'invoice' => $invoice,
                    'factura' => $factura,
                    'logo' => $logo,
                    'visual' => $base64,
                    //                    'victoriaBankGateway' => $victoriaBankGateway
                ]);
            }
        }
    }

    public function __actionCheck($key = null)
    {
        $invoice = Yii::$app->db->createCommand("SELECT invoice_num FROM ut_invoice WHERE (invoice_hash = '".addslashes($key)."')")->queryOne();
        //var_dump($key, $invoice); exit;
        if ($invoice)
        {
            $queryUrl = 'https://b24-f2n931.bitrix24.ru/rest/1/ety1zzhiyf4gh5oo/crm.invoice.get';
            $queryData = http_build_query(
                array(
                    "id" => $invoice['invoice_num']
                )
                );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $queryUrl,
                CURLOPT_POSTFIELDS => $queryData,
            ));
            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result, true);
//var_dump($queryData, $result); exit;
        }
        else
        {
            $result = null;
        }

        $this->layout = 'simple';
        return $this->render('check', [
            'key' => $key,
            'result' => (isset($result['result'])) ? $result['result'] : null
        ]);
    }
    public function actionEmpty()
    {
        $this->layout = '//main-login';

        return $this->render('check', [
            'key' => null
        ]);
    }

    public function actionReturn()
    {
        //$this->layout = 'simple';
        $ip = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
//echo '<plaintext>'; //var_dump(Yii::$app->request->post());

        //$card = 'ts_card';
        $card = 'ww_card';
        $curr = MaibEcomm::CURRENCY_ISO4217_USD;
        $transaction_id = Yii::$app->request->post('trans_id');
        $bankGateway = new MaibEcomm();
        $result = $bankGateway->resultTransaction(
            $transaction_id,
            $curr,
            $ip,
            $card
            );
//        var_dump($result);

        $bank_id = 4;  //  4 -test 1 -rod
        //$terminal = '149583';
        $terminal = '0100239';

        $sql = "SELECT hash FROM ut_transactions WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$transaction_id}')";

        /**/
         $log = "\n------------------------\n";
         $log .= date("Y.m.d G:i:s")."\n";
         $log .= "SQL: ".print_r($sql, 1)."\n";
         $log .= "\n------------------------\n";
         file_put_contents(__DIR__."/../runtime/return.log", $log, FILE_APPEND);
         /**/

        $res = Yii::$app->db->createCommand($sql)->queryOne();
        if ($res) $key = $res['hash']; else $key = null;

        $apc  = isset($result['APPROVAL_CODE']) ? ", `approval` = '".$result['APPROVAL_CODE']."'" : '';
        $rrn  = isset($result['RRN']) ? ", `rrn` = '".$result['RRN']."'" : '';
        $card = isset($result['CARD_NUMBER']) ? ", `card` = '".$result['CARD_NUMBER']."'" : '';
        $bin  = isset($result['CARD_NUMBER']) ? ", `bin` = '".substr($result['CARD_NUMBER'], 0, 6)."'" : '';

        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '{$key}') LIMIT 1")->queryOne();



        if ($result['RESULT'] == 'OK') {
            if ($result['RESULT_CODE'] == '000') {
                $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e', `moment` = NOW(),
                    `trtype` = 'CONFIRM', `status` = 'CONFIRMED' ".$apc.$rrn.$card.$bin."
                    WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$transaction_id}')
                    AND ( (status = 'WAITING') OR (status = 'FAILED') )";

                /**/
                $log = "\n------------------------\n";
                $log .= date("Y.m.d G:i:s")."\n";
                $log .= "SQL: ".print_r($sql, 1)."\n";
                $log .= "\n------------------------\n";
                file_put_contents(__DIR__."/../runtime/return.log", $log, FILE_APPEND);
                /**/

                Yii::$app->db->createCommand($sql)->execute();

                $sql = "UPDATE ut_factura SET status = 'full', paid_date = NOW() WHERE (inner_hash = '{$key}')";
                Yii::$app->db->createCommand($sql)->execute();
/*
                if ($invoice['contact_email'] != '') {
                    $send_flag = false;
                    $emailModel = (object)null;
                    $emailModel->trans_id = $nonce;
                    $emailModel->customer = $invoice['buyer_name'];
                    if (MassMail::SendMail(
                        $invoice['contact_email'],
                        [\Yii::$app->params['supportEmail'] => 'mychauffeurdrive.com'],
                        //            Terms::translate('welcome_email', 'email'). ' ' . \Yii::$app->name,
                        'PAY.MYCHAUFFEURDRIVE.COM: Payment order #'.$invoice['outer_number'],
                        'payment',
                        //            ['confirmLink' => 'https://pay.mychauffeurdrive.com/order/'.$invoice['inner_hash']]
                        $emailModel
                        )) {
                            $send_flag = true;
                        }
                }
*/
                return Yii::$app->getResponse()->redirect('/success/'.$key);
            }
            else if ($result['RESULT_CODE'] == '400') {
                $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e',
                    `trtype` = 'REFUND', `status` = 'REVERSED' ".$apc.$rrn.$card.$bin.", reverse_moment = NOW()
                    WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$transaction_id}')
                    AND ( (status = 'WAITING') OR (status = 'FAILED') )";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "UPDATE ut_factura SET status = 'refunded' WHERE (inner_hash = '{$key}')";
                Yii::$app->db->createCommand($sql)->execute();

                return Yii::$app->getResponse()->redirect('/reverse/'.$key);
            }
            else if ($result['RESULT_CODE'] != '000') {
                $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e', `moment` = NOW(),
                    `trtype` = 'CONFIRM', `status` = 'FAILED' ".$apc.$rrn.$card.$bin."
                    WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$transaction_id}')
                    AND ( (status = 'WAITING') OR (status = 'FAILED') )";
                Yii::$app->db->createCommand($sql)->execute();
                return Yii::$app->getResponse()->redirect('/unsuccess/'.$key);
            }
        }
        else
        {
            $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e', `moment` = NOW(),
                    `trtype` = 'CONFIRM', `status` = 'FAILED' ".$apc.$rrn.$card.$bin."
                    WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$transaction_id}')
                    AND ( (status = 'WAITING') OR (status = 'FAILED') )";
            Yii::$app->db->createCommand($sql)->execute();
            return Yii::$app->getResponse()->redirect('/unsuccess/'.$key);
        }


        //exit;

        //return $this->render('return', ['request' => Yii::$app->request]);
/*
array(3) {
  ["trans_id"]=>
  string(28) "ZPeWvgeQuIrSSCzRqneFSxvPR4M="
  ["Ucaf_Cardholder_Confirm"]=>
  string(1) "0"
  ["language"]=>
  string(2) "en"
}
array(5) {
  ["RESULT"]=>
  string(2) "OK"
  ["RESULT_CODE"]=>
  string(3) "000"
  ["RRN"]=>
  string(12) "233310158752"
  ["APPROVAL_CODE"]=>
  string(6) "489316"
  ["CARD_NUMBER"]=>
  string(16) "510218******1124"
}
*/

    }

    public function actionRun()
    {
        //$this->layout = 'simple';
        $ip = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
//        echo '<plaintext>'; var_dump(Yii::$app->request->post());

        //$card = 'ts_card';
        $card = 'ww_card';
        $curr = MaibEcomm::CURRENCY_ISO4217_USD;
        $transaction_id = Yii::$app->request->post('trans_id');
        $bankGateway = new MaibEcomm();
        $result = $bankGateway->runDmsTransaction(
            "LMsRsrdyJYv8dvnCCS5yqCqhkTo=",
            '11500',
            $curr,
            $ip,
            $card
            );
//        var_dump($result);



        exit;

        //return $this->render('return', ['request' => Yii::$app->request]);
    }

    public function actionReverse($key = null, $amount = 0.0)
    {
        Yii::$app->response->format = 'json';
        $headers = Yii::$app->response->headers;
        $headers->add('Access-Control-Allow-Origin', '*');

        $res_json = false;

        $amount = str_replace(',', '.', preg_replace('~.,\D+~','', $amount));
        $amount = str_replace(' ', '', $amount);
        $matches = substr_count($amount, '.', 0);
        if ($matches > 1) return "Check the number of delimiter characters ('.' or ',')";
        if (!is_numeric($amount)) return "Wrong amount format!";
        if (floatval($amount) <= 0.0) return "Wrong amount!";

        if ($key) {

            $sql = "SELECT * FROM ut_transactions WHERE (hash = '".$key."')
            AND ( ( (trtype = 'CONFIRM') AND (status = 'CONFIRMED') )
                    OR ( (trtype = 'REFUND') AND (status = 'CONFIRMED') )
                    OR ( (trtype = 'REFUND') AND (status = 'REVERSED') )
                    OR ( (trtype = 'REFUND') AND (status = 'FAILED') ))
            LIMIT 1";
            $tr = Yii::$app->db->createCommand($sql)->queryOne();

            /**
            $log = "\n------------------------\n";
            $log .= date("Y.m.d G:i:s")."\n";
            $log .= "SQL: ".print_r($sql, 1)."\n";
            $log .= "Tr: ".print_r($tr, 1)."\n";
            $log .= "\n------------------------\n";
            file_put_contents(__DIR__."/../runtime/reverse.log", $log, FILE_APPEND);
            /**/

            $tr_prev = floatval($tr['refund']);
            $tr_amount = floatval($tr['amount']) - $tr_prev;
            if (floatval($tr_amount) < floatval($amount) ) return 'Return amount exeed the amount of transaction!';
            $tr_ref = $tr_prev + floatval($amount);
            if (floatval($tr['amount']) == $tr_ref) $s = "`status` = 'REVERSED',"; else $s = "";

            if (floatval($amount) > 0.0) $am = round($amount * 100.0, 0);
            else $am = round($tr_amount * 100.0, 0);
            //$amr = round($am / 100.0, 2);

            if ($tr) {
                $ip = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
                $bank_id = 4;  //  4 -test 1 -rod
                //$terminal = '149583';
                $terminal = '0100239';
                //$card = 'ts_card';
                $card = 'ww_card';
                $curr = MaibEcomm::CURRENCY_ISO4217_USD;
                $tr_id = $tr['transaction_id'];
                //$transaction_id = Yii::$app->request->post('trans_id');

//                return true;
//return [floatval($tr['amount']) == $tr_ref, $s];
//return [$tr_id, $amount, $am, $amr, $tr_amount, $tr_ref, $tr_prev == floatval($tr['amount']), $curr, $ip, $card];

                /**
                $log = "\n------------------------\n";
                $log .= date("Y.m.d G:i:s")."\n";
                $log .= "tr_id: ".print_r($tr_id, 1)."\n";
                $log .= "am: ".print_r($am, 1)."\n";
                $log .= "curr: ".print_r($curr, 1)."\n";
                $log .= "card: ".print_r($card, 1)."\n";
                $log .= "\n------------------------\n";
                file_put_contents(__DIR__."/../runtime/reverse.log", $log, FILE_APPEND);
                /**/
return true;

                $bankGateway = new MaibEcomm();
/*
                $result = $bankGateway->resultTransaction( $tr_id, $curr, $ip, $card );
                var_dump($result); return true;
*/
                $result = $bankGateway->returnSmsTransaction( $tr_id, $am, $curr, $ip, $card );
                //$res_flag = true;
                //$res_json = $result;
//return $result;

                /**
                $log = "\n------------------------\n";
                $log .= date("Y.m.d G:i:s")."\n";
                $log .= "Result: ".print_r($result, 1)."\n";
                $log .= "\n------------------------\n";
                file_put_contents(__DIR__."/../runtime/reverse.log", $log, FILE_APPEND);
                /**/

                if ( (isset($result['RESULT'])) && ( ($result['RESULT'] == 'OK') || ($result['RESULT'] == 'REVERSED') ) ) {
                    if ($result['RESULT_CODE'] == '400') {
                        $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e',
                        `trtype` = 'REFUND', $s refund = '$tr_ref', reverse_moment = NOW()
                        WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$tr_id}')
                        /*AND ( (status = 'CONFIRM') OR (status = 'CONFIRMED') )*/";

                        /**
                        $log = "\n------------------------\n";
                        $log .= date("Y.m.d G:i:s")."\n";
                        $log .= "SQL: ".print_r($sql, 1)."\n";
                        $log .= "\n------------------------\n";
                        file_put_contents(__DIR__."/../runtime/reverse.log", $log, FILE_APPEND);
                        /**/

                        Yii::$app->db->createCommand($sql)->execute();
                        $sql = "UPDATE ut_factura SET status = 'refunded' WHERE (inner_hash = '{$key}')";
                        Yii::$app->db->createCommand($sql)->execute();
                        //return Yii::$app->getResponse()->redirect('/reverse/'.$key);
                        $res_json = true;
                    }
                    else
                    {
                        $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e', `moment` = NOW(),
                        `trtype` = 'REFUND', /*`status` = 'FAILED',*/ moment = NOW()
                        WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$tr_id}')
                        /*AND ( (status = 'WAITING') OR (status = 'FAILED') )*/";
                        Yii::$app->db->createCommand($sql)->execute();
                        //return Yii::$app->getResponse()->redirect('/unsuccess/'.$key);
                    }
                }
                else
                {
                    $sql = "UPDATE ut_transactions SET ParentID = '913e22dd1f7a01788a5d7d3735d4bc7e', `moment` = NOW(),
                    `trtype` = 'REFUND', /*`status` = 'FAILED',*/ moment = NOW()
                    WHERE (`bank_id` = '$bank_id') AND (terminal = '$terminal') AND (transaction_id = '{$tr_id}')
                    /*AND ( (status = 'WAITING') OR (status = 'FAILED') )*/";
                    Yii::$app->db->createCommand($sql)->execute();
                    //return Yii::$app->getResponse()->redirect('/unsuccess/'.$key);
                }

            }
        }
//        return $res_json;
/*
        if ($res_flag) {
            $result = $bankGateway->resultTransaction(
                $tr_id,
                $curr,
                $ip,
                $card
                );
*/


//        }


        return $res_json;
        //return $this->render('return', ['request' => Yii::$app->request]);
    }

    public function actionDonate()
    {
        $this->layout = 'simple';
        return $this->render('donate');
    }


    public function actionRegister()
    {
        if (isset($_REQUEST['auth']['application_token']))
        {
            if ( isset($_REQUEST['data']['FIELDS']['ID']) && ($_REQUEST['auth']['application_token'] == '84ybc470gj3kuggneutst9r8esqa5wt9') )
            {
                $hash = md5(strval(time()).$_REQUEST['data']['FIELDS']['ID']);
                Yii::$app->db->createCommand("INSERT INTO ut_invoice (moment, post_data, invoice_num, invoice_hash)
                    VALUES (NOW(), '".addslashes(var_export($_REQUEST, true))."', '".$_REQUEST['data']['FIELDS']['ID']."', '$hash')")->execute();

                Yii::$app->mailer->compose()
                ->setFrom('noreply.aviamd@yandex.ru')
                //              ->setTo('oleg.dynga@gmail.com')
                ->setTo('info@diginet.md')
                ->setSubject('Оплата счета №'.$_REQUEST['data']['FIELDS']['ID'])
                //              ->setTextBody('<a href="">Счет на оплату</a>')
                ->setHtmlBody('<a href="https://invoice.diginet.md/check/'.$hash.'">Оплатить счет №'.$_REQUEST['data']['FIELDS']['ID'].'</a>')
                ->send();
            }
            else
            {
                Yii::$app->db->createCommand("INSERT INTO ut_invoice (moment, post_data)
                    VALUES (NOW(), '".addslashes(var_export($_REQUEST, true))."')")->execute();
            }
        }


    }





/*
    RETURN mst/pg/maib/return_mst.php
    GET: array (
        'lang' => 'ru',
    )
    POST: array (
        'trans_id' => 'EDBe0kEtSjIQhHPTxCc1mqEPbkg=',
        'Ucaf_Cardholder_Confirm' => '0',
        'language' => 'ro',
    )
*/


    public function actionPay()
    {
        $business = 'mychauffeurdrive';
        $context = Client::getContext($business);
        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;
        $key = '';
        if (Yii::$app->request->post()) {
            //            var_dump(Yii::$app->request->post());exit;
            $key = Yii::$app->request->post('key');
            $lang = Yii::$app->request->post('lang');
//echo"<plaintext>"; var_dump($business, $b); exit;
            $check = Yii::$app->db->createCommand("SELECT * FROM ut_transactions WHERE (transaction_id = '".addslashes($key)."')")->queryOne();
            if ($check) $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (facturaID = '{$check['factura_id']}')")->queryOne();
            else $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();

            $bn = Client::findBankByBusiness($business, $invoice['currency'], $b['mode']);
            $part_summ = floatval(Yii::$app->request->post('amount'));
            self::log($bn->alias, 'Pay / Post', Yii::$app->request->post());
//echo"<plaintext>"; var_dump($business, $bn, $part_summ, $invoice); exit;
        } else {
            $lang = Yii::$app->language;
        }
        $gourl = '';
/*
        if (!isset($context)) {
            $context = Client::getContext($business);
            $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
            $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;
        }
*/
        if (!$invoice) $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
        $factura = @json_decode($invoice['json_data'])->Document;
        if (!isset($pg)) $pg = Client::findPaymentByBusiness($business, $invoice['currency'], $b['mode']);
        if (!isset($bn)) $bn = Client::findBankByBusiness($business, $invoice['currency'], $b['mode']);

//echo"<plaintext>"; var_dump($pg); exit;

        if ($b['is_iframe'] == '1')
        {
            $back_url_s = $b['site'].'/'.$lang.'/success/'.$key;
            $back_url_u = $b['site'].'/'.$lang.'/unsuccess/'.$key;
        }
        else
        {
            $back_url_s = $pg->back_url_success.'/'.$lang.'/success/'.$key;
            $back_url_u = $pg->back_url_unsuccess.'/'.$lang.'/unsuccess/'.$key;
        }
        $amount = $rest = 0.0;
        $card = '';
        $this->layout = 'simple';
        $url = '';
        /*
         $prev = Yii::$app->db->createCommand("SELECT SUM(amount) AS prev_amount FROM ut_transactions
         WHERE (factura_id = '{$invoice['facturaID']}') AND  (`trtype` = 'CONFIRM') AND (`status` = 'CONFIRMED')")->queryOne();
         */

//echo"<plaintext>"; var_dump($business, $bn, $part_summ, $back_url_s, $back_url_u); exit;

/*
        $prev = Yii::$app->db->createCommand("
        SELECT
        SUM( IF(status = 'REVERSED', -amount, amount)  ) AS prev_amount
        FROM ut_transactions
        WHERE (factura_id = '{$invoice['facturaID']}')
        AND ( `trtype` IN ('CONFIRM', 'REVERSE') )
        AND (`status` IN ('CONFIRMED', 'REVERSED') )
        ")->queryOne();
        $prev_amount = ($prev) ? floatval($prev['prev_amount']) : 0.0;
*/
        //var_dump(Yii::$app->request->post, $business, $key, $pg, $bn); exit;
//echo"<plaintext>"; var_dump($prev_amount); exit;

        switch ($bn->alias)
        {


            case 'maib':
            case 'maib_test':
                //$terminal = '0149583';
                $terminal = '0100239';

                $amount = floatval($invoice['amount']);
                if ( ($part_summ > 0) && ($part_summ < $amount) ) $amount = $part_summ;
                $rest = floatval($invoice['amount']) - $amount;
                $amount_maib = intval( $amount * 100 );

                switch ($invoice['currency'])
                {
                    case 'EUR': $curr = '978'; break;
                    case 'USD': $curr = '840'; break;
                    case 'MDL': $curr = '498'; break;
                    case 'RUB': $curr = '643'; break;
                }
                switch ($pg->mode)
                {
                    case 'TEST': $card = 'ts_card'; break;
                    case 'PROD': $card = 'ww_card'; break;
                }

                //$card = 'ts_card';
                $card = 'ww_card';
                $curr = MaibEcomm::CURRENCY_ISO4217_USD;

                $ip = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
                $bankGateway = new MaibEcomm();
                $result = $bankGateway->registerSmsTransaction(
                    $amount_maib,
                    $curr,
                    $ip,
                    $lang,
                    //Terms::translate('invoice', 'invoice', $lang).': '.$invoice['outer_number'],
                    'Order: '.$invoice['outer_number'],
                    $card
                    );
//echo"<plaintext>"; var_dump($result); exit;
                self::log($bn->alias, 'Pay / Invoice', $invoice);
                self::log($bn->alias, 'Pay / Amount',  $amount);
                self::log($bn->alias, 'Pay / Result',  $result);

                if (!$result) return Yii::$app->getResponse()->redirect('/'.$lang);

                $nonce = $result['TRANSACTION_ID'];
                $order_id = Yii::$app->db->createCommand("CALL order_seq({$bn->bankID}, {$context['client']->clientID}, {$b['businessID']}, '$terminal', '$nonce', {$invoice['facturaID']}, {$amount}, {$rest}, '{$invoice['currency']}', 'AUTH', '{$key}', '{$invoice['outer_number']}')")->queryOne();
                $r = Yii::$app->db->createCommand("UPDATE ut_factura SET order_id = '{$order_id['order_id']}' WHERE (inner_hash = '".addslashes($key)."')")->execute();
                //            $result = $maib->runDmsTransaction('r9lqEYm8weOagyOXeVbT5DBnyB8=', 100, $maib::CURRENCY_ISO4217_MDL, '127.0.0.1', 'ts_card');
                //            $result = $maib->returnDmsTransaction('r9lqEYm8weOagyOXeVbT5DBnyB8=', 100, $maib::CURRENCY_ISO4217_MDL, '127.0.0.1', 'ts_card');
                //            $result = $maib->resultTransaction('r9lqEYm8weOagyOXeVbT5DBnyB8=', $maib::CURRENCY_ISO4217_MDL, '127.0.0.1', 'ts_card');

/*
                if ($invoice['contact_email'] != '') {
                    $send_flag = false;
                    $emailModel = (object)null;
                    $emailModel->trans_id = $nonce;
                    $emailModel->customer = $invoice['buyer_name'];
                    if (MassMail::SendMail(
                        $invoice['contact_email'],
                        [\Yii::$app->params['supportEmail'] => 'mychauffeurdrive.com'],
                        //            Terms::translate('welcome_email', 'email'). ' ' . \Yii::$app->name,
                        'PAY.MYCHAUFFEURDRIVE.COM: Order #'.$invoice['outer_number'].' - New Transaction',
                        'transaction',
                        //            ['confirmLink' => 'https://pay.mychauffeurdrive.com/order/'.$invoice['inner_hash']]
                        $emailModel
                        )) {
                            $send_flag = true;
                        }
                }
*/
                $url = $bankGateway->CHU1.$bankGateway->main_prm[$card]['CHU_Port'].$bankGateway->CHU2 . '?language='.$lang.'&trans_id=' . urlencode($nonce); //.'&cn=';

                //                var_dump($url); exit;
//echo"<plaintext>"; var_dump($url); exit;
                $view = 'pay_maib';
                break;
        }





        //        header("Location: $url"); exit;
        //        var_dump($view, $url); exit;

//      перенаправление
        Yii::$app->response->redirect($url);
        return Yii::$app->getResponse()->redirect($url);

/**
        if (Yii::$app->request->post())
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial($view, [
                'lang' => $lang,
                'back_url_s' => $back_url_s,
                'back_url_u' => $back_url_u,
                'amount' => $amount,
                'currency' => $invoice['currency'],
                'invoice' => Terms::translate('invoice', 'invoice', $lang).': '.$invoice['outer_number'],
                'email' => $factura->SupplierInfo->Buyer->Email,
                'bankGateway' => $bankGateway,
                'nonce' => $nonce,
                'order_id' => $order_id['order_id'],
                'card' => $card,
                'url' => $url,
                'gourl' => $gourl,
                'postmode' => true
            ], true);
        }
        else
        {
/**/

        $favicon = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURLi'] != '' ? $context['business'][0]['ImageURLi'] : $context['business'][0]['ImageURLv2']);
        //var_dump($context['business'][0]['ImageURLi'], $file); exit;
        //$picture = @file_get_contents($file, 'r');
        //$type = pathinfo($file, PATHINFO_EXTENSION);
        //$logo = 'data:image/' . $type . ';base64,' . base64_encode($picture);
//        $logo = Yii::$app->params['image_url'].'images/ut_business/'.($context['business'][0]['ImageURL'] != '' ? $context['business'][0]['ImageURL'] : $context['business'][0]['ImageURLv2']);
        //$favicon = Yii::$app->params['image_url'].'/images/ut_business/'.($context['business'][0]['ImageURLv1'] != '' ? $context['business'][0]['ImageURLv1'] : $context['business'][0]['ImageURLv2']);
        Yii::$app->params['favicon'] = $favicon;

            return $this->render($view, [
                'lang' => $lang,
                'back_url_s' => $back_url_s,
                'back_url_u' => $back_url_u,
                'amount' => $amount,
                'currency' => $invoice['currency'],
                'invoice' => Terms::translate('invoice', 'invoice', $lang).': '.$invoice['outer_number'],
                'email' => $factura->SupplierInfo->Buyer->Email,
                'bankGateway' => $bankGateway,
                'nonce' => $nonce,
                'order_id' => $order_id['order_id'],
                'card' => $card,
                'url' => $url,
                'gourl' => $gourl,
                'postmode' => false
            ]);
/**
        }
/**/
    }

    public function actionBank()
    {
        $maibEcomm = new MaibEcomm();
        $result = $maibEcomm->closeDay(MaibEcomm::CURRENCY_ISO4217_USD, 'ww_card');
        if (count($result) > 0)
        {
            $query = "INSERT INTO ut_bank_day (ParentID, result_txt, result_code, moment, fld_075, fld_076, fld_087, fld_088, fld_074, fld_077, fld_086, fld_089)
            VALUES	(
            	'011d7e8f3e5b55898d2b7e99f3cbb3c6',
            	'USD: {$result['RESULT']}',
            	'{$result['RESULT_CODE']}',
            	NOW(),
            	'{$result['FLD_075']}',
            	'{$result['FLD_076']}',
            	'".floatval($result['FLD_087'])/100.0."',
            	'".floatval($result['FLD_088'])/100.0."',
            	'{$result['FLD_074']}',
            	'{$result['FLD_077']}',
            	'{$result['FLD_086']}',
            	'{$result['FLD_089']}'
            )";
            $r = Yii::$app->db->createCommand($query)->execute();
        }
    }

    public function actionArch()
    {
        $slot = intval(Yii::$app->params['arch_slot']);
        if ($slot > 1) {
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (`is_archived` = 0) AND (`status` = 'full') AND (`paid_date` != 0) AND  (`paid_date` < ADDDATE(NOW(), INTERVAL -{$slot} DAY))")->execute();
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (`is_archived` = 0) AND (`status` = 'full') AND (`paid_date` = 0) AND (`due_on` != 0) AND (`due_on` < ADDDATE(NOW(), INTERVAL -{$slot} DAY))")->execute();
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (`is_archived` = 0) AND (`status` IN ('actual', 'sended') ) AND (`due_on` != 0) AND (`due_on` < ADDDATE(NOW(), INTERVAL -{$slot} DAY))")->execute();
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (`is_archived` = 0) AND (`status` IN ('actual', 'sended') ) AND (`due_on` = 0) AND (`issue_date` != 0) AND (`issue_date` < ADDDATE(NOW(), INTERVAL -{$slot} DAY))")->execute();
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (`is_archived` = 0) AND (`status` = 'refunded') AND (`refund_date` != 0) AND (`refund_date` < ADDDATE(NOW(), INTERVAL -{$slot} DAY))")->execute();
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (`is_archived` = 0) AND (`status` = 'draft') AND (`issue_date` != 0) AND (`issue_date` < ADDDATE(NOW(), INTERVAL -{$slot} DAY))")->execute();
        }
    }

    public function log($service, $title, $data)
    {
        $log = "\n------------  Request from $service ------------\n";
        $log .= date("Y.m.d G:i:s")."\n";
        $log .= "$title\n";
        //        $log .= var_export($data, 1);
        $log .= print_r($data, 1);
        $log .= "\n";
        file_put_contents(__DIR__."/../runtime/$service.log", $log, FILE_APPEND);
    }

}
