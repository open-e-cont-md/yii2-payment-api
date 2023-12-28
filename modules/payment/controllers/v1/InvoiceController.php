<?php

namespace openecontmd\payment_api\modules\payment\controllers\v1;

use Yii;
use yii\rest\Controller;
use OpenApi\Annotations as OA;

use DateTime;
use Einvoicing\Invoice;
use Einvoicing\Presets;
use Einvoicing\Presets\Peppol;
use Einvoicing\Presets\CiusAtGov;
use Einvoicing\Presets\CiusRo;
use Einvoicing\Identifier;
use Einvoicing\Party;
use Einvoicing\InvoiceLine;
use Einvoicing\Writers\UblWriter;

use openecontmd\payment_api\models\PaymentInvoiceAPI;
use yii\web\HttpException;

class InvoiceController extends Controller
{
	public function __construct($id, $module, $config = [])
	{
		parent::__construct($id, $module, $config);
		return true;
	}

/** Get Available Actions
 * @OA\Post(
 *     path="/payment/v1/invoice",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Available Invoice Actions",
 *         @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/result_invoice"),
 *              @OA\Examples(example="result_invoice", value={
 "status": "OK",
 "data": {
 "available_invoice_actions": {"set-invoice-number", "set-issue-date", "set-due-date", "set-contact-name", "set-contact-email", "set-identifier"},
 "controller": "invoice",
 "entry_point": "https://api.open.e-cont.md/payment/v1/invoice/{action}"
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
 *              @OA\Examples(example="result_error", value={
 "status": "FAIL",
 "data": {
 "name": "Bad Request",
 "message": {"session_id":"ID сессии cannot be blank."},
 "code": 0,
 "status": 400,
 "type": "yii\web\BadRequestHttpException"
 }
 }, summary="An result error."),
 *          )
 *     ),
 * )
 */
	public function actionIndex()
	{
	    return [
	        'available_actions' => [
	            'set-invoice-number',
	            'set-issue-date',
	            'set-due-date',
	            'set-contact-name',
	            'set-contact-email',
	            'set-identifier'],
	        'controller' => 'invoice',
	        'entry_point' => 'https://api.open.e-cont.md/payment/v1/invoice/{action}',
	    ];
	}

/** Set Invoice Number
 * @OA\Post(
 *     path="/payment/v1/invoice/set-invoice-number",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Set invoice number",
 *     ),
 *     @OA\Parameter(
 *         name="invoice-number",
 *         in="query",
 *         description="Invoice number",
 *         required=true,
 *         @OA\Schema(
 *             default="ABC-123",
 *             type="string",
 *         )
 *     )
 * )
 */
	public function actionSetInvoiceNumber()
	{
	    $response = [
	        'invoice-number' => 'ABC-123',
	        'action' => 'set',
	        'status' => 'OK',
	    ];
	    return $response;
	}

/** Set Issue Date
 * @OA\Post(
 *     path="/payment/v1/invoice/set-issue-date",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Set issue date",
 *     ),
 *     @OA\Parameter(
 *         name="issue-date",
 *         in="query",
 *         description="Issue date",
 *         required=true,
 *         @OA\Schema(
 *             default="2023-08-08",
 *             type="string",
 *         )
 *     )
 * )
 */
	public function actionSetIssueDate()
	{
	    $response = [
	        'issue-date' => '2023-08-08',
	        'action' => 'set',
	        'status' => 'OK',
	    ];
	    return $response;
	}

/** Set Due Date
 * @OA\Post(
 *     path="/payment/v1/invoice/set-due-date",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Set due date",
 *     ),
 *     @OA\Parameter(
 *         name="due-date",
 *         in="query",
 *         description="Due date",
 *         required=true,
 *         @OA\Schema(
 *             default="2023-08-17",
 *             type="string",
 *         )
 *     )
 * )
 */
	public function actionSetDueDate()
	{
	    $response = [
	        'due-date' => '2023-08-17',
	        'action' => 'set',
	        'status' => 'OK',
	    ];
	    return $response;
	}


/** Set Contact Name
 * @OA\Post(
 *     path="/payment/v1/invoice/set-contact-name",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Set contact name",
 *     ),
 *     @OA\Parameter(
 *         name="contact-name",
 *         in="query",
 *         description="Contact name",
 *         required=true,
 *         @OA\Schema(
 *             default="John Smith",
 *             type="string",
 *         )
 *     )
 * )
 */
	public function actionSetContactName()
	{
	    $response = [
	        'contact-name' => 'John Smith',
	        'action' => 'set',
	        'status' => 'OK',
	    ];
	    return $response;
	}

/** Set Contact E-Mail
 * @OA\Post(
 *     path="/payment/v1/invoice/set-contact-email",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Set contact email",
 *     ),
 *     @OA\Parameter(
 *         name="contact-email",
 *         in="query",
 *         description="Contact email",
 *         required=true,
 *         @OA\Schema(
 *             default="john.smith@email.test",
 *             type="string",
 *         )
 *     )
 * )
 */
	public function actionSetcontacteEmail()
	{
	    $response = [
	        'contact-email' => 'john.smith@email.test',
	        'action' => 'set',
	        'status' => 'OK',
	    ];
	    return $response;
	}

/** Set Identifier
 * @OA\Post(
 *     path="/payment/v1/invoice/set-identifier",
 *     tags={"payment"},
 *     @OA\Response(
 *         response="200",
 *         description="Set identifier",
 *     ),
 *     @OA\Parameter(
 *         name="identifier",
 *         in="query",
 *         description="Identifier",
 *         required=true,
 *         @OA\Schema(
 *             default="123456789012",
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="scheme",
 *         in="query",
 *         description="Scheme",
 *         required=true,
 *         @OA\Schema(
 *             default="0088",
 *             type="string",
 *         )
 *     )
 * )
 */
	public function actionSetIdentifier()
	{
	    $response = [
	        'identifier' => '123456789012',
	        'scheme' => '0088',
	        'action' => 'set',
	        'status' => 'OK',
	    ];
	    return $response;
	}

}

/*

=== Seller ===
{
  "electronic_address": {
    "value": "51370471",
    "scheme": "0000",
    "description": "CUIIO"
  },
  "company_id": {
    "value": "1029600052345",
    "scheme": "0001",
    "description": "IDNO"
  },
  "name": "Societatea cu Răspundere Limitată Network Solutions",
  "trading_name": "Network Solutions",
  "vat_number": "",
  "address": "sec. Ciocana, str. Mircea cel Bătrân bd., 17",
  "city": "mun. Chişinău",
  "country": "MD",
  "contact": {
    "name": "John Smith",
    "email": "office@ns.md",
    "phone": "+37369123456",
    "website": "https://ns.md"
  }
}

=== Buyer ===
{
  "electronic_address": {
    "value": "27545081",
    "scheme": "0000",
    "description": "CUIIO"
  },
  "company_id": {
    "value": "1022600037675",
    "scheme": "0001",
    "description": "IDNO"
  },
  "name": "SOCIETATEA PE ACŢIUNI TRANSPORT SERVICE",
  "trading_name": "TRANSPORT SERVICE",
  "vat_number": "7654321",
  "address": "sec. Rîşcani, str. Florilor, 21",
  "city": "mun. Chişinău",
  "country": "MD",
  "contact": {
    "name": "Anna Johnson",
    "email": "office@ts.com",
    "phone": "+37369234567",
    "website": "https://ts.com"
  }
}


=== Invoice ===
{
  "cius": "peppol",
  "id": "DIG-00005",
  "issue_date": "2023-08-21",
  "due_date": "2023-08-28",
  "currency": "EUR",
  "parties": {
    "seller": {
      "electronic_address": {
        "value": "51370471",
        "scheme": "0000",
        "description": "CUIIO"
      },
      "company_id": {
        "value": "1029600052345",
        "scheme": "0001",
        "description": "IDNO"
      },
      "name": "Societatea cu Răspundere Limitată Network Solutions",
      "trading_name": "Network Solutions",
      "vat_number": "",
      "address": "sec. Ciocana, str. Mircea cel Bătrân bd., 17",
      "city": "mun. Chişinău",
      "country": "MD",
      "contact": {
        "name": "John Smith",
        "email": "office@ns.md",
        "phone": "+37369123456",
        "website": "https://ns.md"
      }
    },
    "buyer": {
      "electronic_address": {
        "value": "27545081",
        "scheme": "0000",
        "description": "CUIIO"
      },
      "company_id": {
        "value": "1022600037675",
        "scheme": "0001",
        "description": "IDNO"
      },
      "name": "SOCIETATEA PE ACŢIUNI TRANSPORT SERVICE",
      "trading_name": "TRANSPORT SERVICE",
      "vat_number": "7654321",
      "address": "sec. Rîşcani, str. Florilor, 21",
      "city": "mun. Chişinău",
      "country": "MD",
      "contact": {
        "name": "Anna Johnson",
        "email": "office@ts.com",
        "phone": "+37369234567",
        "website": "https://ts.com"
      }
    }
  },
  "item_lines": [
    {
      "name": "Product Name",
      "description": null,
      "price": {
        "price": 40,
        "base_quantity": null
      },
      "vat_rate": 16,
      "quantity": 4
    },
    {
      "name": "Line #2",
      "description": "The description for the second line",
      "price": {
        "price": 10,
        "base_quantity": 5
      },
      "vat_rate": 4,
      "quantity": 27
    }
  ]
}



*/