<?php

namespace openecontmd\payment_api\modules\payment\controllers\v1;

use Yii;
use yii\rest\Controller;
use openecontmd\payment_api\models\Helper;
use OpenApi\Annotations as OA;

class MerchantController extends Controller
{
	public function __construct($id, $module, $config = [])
	{
		parent::__construct($id, $module, $config);
		return true;
	}

/**
 * @OA\Post(
 *     path="/payment/v1/merchant",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Available Actions",
 *         @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/result"),
 *              @OA\Examples(example="result", value={
 "status": "OK",
 "data": {
 "available_actions": {"check", "info", "spend"},
 "controller": "merchant",
 "entry_point": "https://api.open.e-cont.md/payment/v1/merchant/{action}"
 }
 }, summary="An result object."),
 *          )
 *     ),
 *
 *     @OA\Response(
 *         response="400",
 *         description="Bad Request",
 *         @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/result"),
 *              @OA\Examples(example="result", value={
 "status": "FAIL",
 "data": {
 "name": "Bad Request",
 "message": {"session_id":"ID сессии cannot be blank."},
 "code": 0,
 "status": 400,
 "type": "yii\web\BadRequestHttpException"
 }
 }, summary="An result object."),
 *          )
 *     )
 * )
 */
	public function actionIndex()
	{
	    return [
	        'available_actions' => ['init', 'pay', 'check'],
	        'controller' => 'merchant',
	        'entry_point' => 'https://api.open.e-cont.md/payment/v1/merchant/{action}',
	    ];
	}

/**
 * @OA\Post(
 *     path="/payment/v1/merchant/check",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Check Availability",
 *     )
 * )
 */
	public function actionCheck()
	{
	    $response = [
	        'endpoint' => 'payment',
	        'controller' => 'merchant',
	        'version' => 'Version-1.0',
	        'status' => 'In work',
	        'availability' => '100%',
	        'entry_point' => 'https://api.open.e-cont.md/payment/v1/merchant/{action}',
	    ];
	    return $response;
	}

/**
 * @OA\Post(
 *     path="/payment/v1/merchant/info",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="API Branch Info",
 *     )
 * )
 */
	public function actionPay()
	{
	    $data = Yii::$app->request->post();

	    $response = [
	        'endpoint' => 'payment',
	        'controller' => 'merchant',
	        'caption' => 'API for Payment Invoice Repository',
	        'version' => 'Version-1.0',
	    ];
	    return $response;
	}

/**
 * @OA\Post(
 *     path="/payment/v1/merchant/spend",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="API Spend Info",
 *     )
 * )
 */
/*
	public function actionPay()
	{
	    $response = [
	        'endpoint' => 'payment',
	        'controller' => 'merchant',
	        'account_records' => [
	            'departments' => 3,
	            'invoices' => 122,
	            'customers' => 7,
	        ],
	    ];
	    return $response;
	}
*/

	public function actionStatus()
	{
	    $order_number = Yii::$app->request->get('order_number');

	    $response = [
	        'endpoint' => 'payment',
	        'controller' => 'merchant',
	        'order_number' => $order_number,
	        'payment_status' => 'not_paid',
	    ];
	    return $response;
	}


/**
 * @OA\Post(
 *     path="/payment/v1/merchant/init",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="GUID",
 *     )
 * )
 */
	public function actionInit()
	{
	    $merchant_url = 'https://maib.ecommerce.md:11440/ecomm01/MerchantHandler';
	    $client_url = 'https://maib.ecommerce.md:443/ecomm01/ClientHandler';
	    $order_hash = Yii::$app->request->get('order_hash');
	    $order_number = Yii::$app->request->get('order_number');
	    $transaction_id = str_replace('-', '', Helper::newid()).'=';
	    $check_status = 'http://admin-test.repo.tst/payment/v1/merchant/status?transaction_id='.$transaction_id.'&order_number='.$order_number;

	    $response = [
	        'endpoint' => 'payment',
	        'controller' => 'merchant',
	        'action' => 'init',
	        'order_hash' => $order_hash,
	        'order_number' => $order_number,
	        'transaction_id' => $transaction_id,
	        'pay_url' => $client_url,
	        'status' => 'initiate',
	        'check_status' => $check_status,
	    ];
	    return $response;
	}

}