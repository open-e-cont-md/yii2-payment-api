<?php
//	Last change:  Oleg Dynga  29.03.2017

//namespace frontend\controllers;
namespace openecontmd\payment_api\controllers;

//namespace pauko;
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 02.12.15
 * Time: 15:23
 */

use yii\web\Controller;
use Helper;

class MaibEcomm extends Controller
{
    const CURRENCY_ISO4217_MDL = '498';
    const CURRENCY_ISO4217_EUR = '978';
    const CURRENCY_ISO4217_USD = '840';
    const CURRENCY_ISO4217_RUB = '643';
    const COMMAND_SMS = 'v';
    const COMMAND_TRANSACTION_RESULT = 'c';
    const COMMAND_REGISTER_DMS = 'a';
    const COMMAND_RUN_DMS = 't';
    const COMMAND_RETURN_TRANSACTION = 'r';
    const COMMAND_CLOSE_DAY = 'b';

//    public $certCacert;
//    public $certPcert;
//    public $certKey;
//    public $certCacert_dir;
//    public $certCacert_file;
//    public $certPcert_dir;
//    public $certPcert_file;
//    public $certKey_dir;
//    public $certKey_file;

    public $main_prm;

//    public $certKeyPassPhrase = 'niagara';

//	TESTING!!!

/*
    URL Test
    MerchantHandler: https://maib.ecommerce.md:21440/ecomm/MerchantHandler
    ClientHandler: https://maib.ecommerce.md:21443/ecomm/ClientHandler
*/

    //public $merchantHandlerUrl = 'https://ecomm.maib.md:4499/ecomm2/MerchantHandler';
//    public $merchantHandlerUrl = ''; //'https://maib.ecommerce.md:21443/ecomm/MerchantHandler';
/*
//	PRODUCTION
//    public $merchantHandlerUrl = 'https://ecomm.maib.md:4455/ecomm2/MerchantHandler';
    public $MHU1 = 'https://maib.ecommerce.md';
    public $MHU2 = '/ecomm/MerchantHandler';

//	TESTING!!!
    //public $clientHandlerUrl = 'https://ecomm.maib.md:7443/ecomm2/ClientHandler';
//    public $clientHandlerUrl = 'https://maib.ecommerce.md/ecomm/ClientHandler';
//	PRODUCTION
//    public $clientHandlerUrl = 'https://ecomm.maib.md/ecomm2/ClientHandler';
    public $CHU1 = 'https://maib.ecommerce.md';
    public $CHU2 = '/ecomm/ClientHandler';
*/

/**/
//  TEST
    public $MHU1 = 'https://maib.ecommerce.md';
    public $MHU2 = '/ecomm/MerchantHandler';
    public $CHU1 = 'https://maib.ecommerce.md';
    public $CHU2 = '/ecomm/ClientHandler';
/**
//  PRODUCTION
    public $MHU1 = 'https://maib.ecommerce.md';
    public $MHU2 = '/ecomm01/MerchantHandler';
    public $CHU1 = 'https://maib.ecommerce.md';
    public $CHU2 = '/ecomm01/ClientHandler';
/**/

    public $lastOperation;
    public $lastResult;
    public $lastErrorMessage;
//    public $logFileName;

    public function __construct()
    {
//        $certCacert = __DIR__ . '/maib/cert/cacert.pem';
//        $certPcert = __DIR__ . '/maib/cert/pcert.pem';
//        $certKey = __DIR__ . '/maib/cert/key.pem';

//        $certCacert_dir  = __DIR__ . '/maib/cert_';
//        $certCacert_file = '/cacert.pem';
//        $certPcert_dir = __DIR__ . '/maib/cert_';
//        $certPcert_file = '/pcert.pem';
//        $certKey_dir = __DIR__ . '/maib/cert_';
//        $certKey_file = '/key.pem';

//        $logFileName = __DIR__ . '/maib/request.log';

//        assert('file_exists($certCacert)');
//        assert('file_exists($certPcert)');
//        assert('file_exists($certKey)');

//        $this->certCacert = $certCacert;
//        $this->certPcert = $certPcert;
//        $this->certKey = $certKey;

//        $this->certCacert_dir = $certCacert_dir;
//        $this->certCacert_file = $certCacert_file;
//        $this->certPcert_dir = $certPcert_dir;
//        $this->certPcert_file = $certPcert_file;
//        $this->certKey_dir= $certKey_dir;
//        $this->certKey_file = $certKey_file;

//        $this->logFileName = $logFileName;

//	29.03.2017 =======================
        $this->main_prm = array(
        	'ww_card' => array('dir_pref' => 'yes3ds_', 'cacert' => '/cacert.pem', 'pcert' => '/pcert.pem', 'key' => '/key.pem', 'pass' => 'niagara', 'log' => __DIR__ . '/logs/'.date('Ym').'_ww_request.log', 'MHU_Port' => ':11440', 'CHU_Port' => '443'),
        	'md_card' => array('dir_pref' => 'non3ds_', 'cacert' => '/cacert.pem', 'pcert' => '/pcert.pem', 'key' => '/key.pem', 'pass' => 'niagara', 'log' => __DIR__ . '/logs/'.date('Ym').'_md_request.log', 'MHU_Port' => ':21440', 'CHU_Port' => ''),
            'ts_card' => array('dir_pref' => 'test_',   'cacert' => '/cacert.pem', 'pcert' => '/pcert.pem', 'key' => '/key.pem', 'pass' => 'niagara', 'log' => __DIR__ . '/logs/'.date('Ym').'_ts_request.log', 'MHU_Port' => ':21440', 'CHU_Port' => ':21443'),  //  :7443
        	'dir' => __DIR__ . '/../../keys/maib_test'
        );
    }

    public function registerSmsTransaction($amount, $currency, $clientIpAddress, $lang = 'en', $description = '', $domain = 'ts_card')
    {
//        $merchantHandlerUrl = $this->merchantHandlerUrl;
        $merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;

        #print("START</br></br>");
        $command = self::COMMAND_SMS;
        $postData = 'command=' . $command . '&amount=' . $amount . '&currency=' . $currency . '&client_ip_addr=' . $clientIpAddress;  // .'&language=' . $lang . '&description=' . $description ;
        $this->logString(' REQUEST SMS TO: ' . "\t" . $merchantHandlerUrl . ' WITH _POST ' . $postData, $domain);

        $merchantHandler = curl_init($merchantHandlerUrl);

        curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
        curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
        curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
        curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
        curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
        curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($merchantHandler);
        $resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray($response);
        $this->logString(' RESPONSE SMS' . "\t" . 'RESPONSE RAW: ' . $response . 'RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
        $this->lastOperation = 'SMS';

        if (curl_error($merchantHandler)){
            $this->lastErrorMessage = curl_error($merchantHandler);
            $this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);

        }

        return  $resultArray;
    }

    public function registerDmsTransaction($amount, $currency, $clientIpAddress, $lang = 'en', $description = '', $domain = 'ts_card')
    {
//        $merchantHandlerUrl = $this->merchantHandlerUrl;
        $merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;

        #print("START</br></br>");
        $command = self::COMMAND_REGISTER_DMS;
//        $postData = 'command=' . $command . '&amount=' . $amount . '&currency=' . $currency . '&client_ip_addr=' . $clientIpAddress ;
        $postData = 'command=' . $command . '&amount=' . $amount . '&currency=' . $currency . '&client_ip_addr=' . $clientIpAddress .'&language=' . $lang . '&description=' . $description ;
        $this->logString(' REQUEST REGISTER DMS TO: ' . "\t" . $merchantHandlerUrl . ' WITH _POST ' . $postData, $domain);
        $merchantHandler = curl_init($merchantHandlerUrl);
//var_dump($merchantHandlerUrl, $merchantHandler);
        curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
        curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
        curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
        curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
        curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
        curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($merchantHandler);
//var_dump($response);
        $resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray($response);
        $this->logString(' RESPONSE REGISTER DMS' . "\t" . 'RESPONSE RAW: ' . $response . ' RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
        $this->lastOperation = 'DMS REGISTER';

        if (curl_error($merchantHandler)){
            $this->lastErrorMessage = curl_error($merchantHandler);
            $this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);

        }

        return  $resultArray;
    }

    public function runDmsTransaction($trans_id ,  $amount, $currency, $clientIpAddress, $domain = 'ts_card')
    {
//        $merchantHandlerUrl = $this->merchantHandlerUrl;
        $merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;
        #print("START</br></br>");
        $transactionIdUrlEncoded = urlencode($trans_id);
        $command = self::COMMAND_RUN_DMS;
        $postData = 'command=' . $command . '&trans_id=' . $transactionIdUrlEncoded . '&amount=' . $amount . '&currency=' . $currency . '&client_ip_addr=' . $clientIpAddress  ;
        $this->logString(' REQUEST RUN DMS TO: ' . "\t" . $merchantHandlerUrl . ' WITH _POST ' . $postData, $domain);
        $merchantHandler = curl_init($merchantHandlerUrl);

        curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
        curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
        curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
/*
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->certCacert);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->certPcert);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->certKey);
*/
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
        curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
        curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
        curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($merchantHandler);
        $resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray($response);
        $this->logString(' RESPONSE RUN DMS' . "\t" . 'RESPONSE RAW: ' . $response . 'RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
        $this->lastOperation = 'DMS RUN';

        if (curl_error($merchantHandler)){
            $this->lastErrorMessage = curl_error($merchantHandler);
            $this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);
        }

        $this->lastResult = $this->parseResult($response);
        return  $resultArray;
    }


    public function returnDmsTransaction($trans_id ,  $amount, $currency, $clientIpAddress, $domain = 'ts_card')
    {
//    	$merchantHandlerUrl = $this->merchantHandlerUrl;
    	$merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;
    	#print("START</br></br>");
    	$transactionIdUrlEncoded = urlencode($trans_id);
    	$command = self::COMMAND_RETURN_TRANSACTION;
    	$postData = 'command=' . $command . '&trans_id=' . $transactionIdUrlEncoded . '&amount=' . $amount . '&currency=' . $currency . '&client_ip_addr=' . $clientIpAddress  ;
    	$this->logString(' REQUEST RETURN DMS TO: ' . "\t" . $merchantHandlerUrl . ' WITH _POST ' . $postData, $domain);
    	$merchantHandler = curl_init($merchantHandlerUrl);

    	curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
    	curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
    	curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
/*
    	curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->certCacert);
    	curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->certPcert);
    	curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->certKey);
*/
    	curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
    	curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
    	curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
    	curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
    	curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
    	curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
    	curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
    	$response = curl_exec($merchantHandler);
    	$resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray($response);
    	$this->logString(' RESPONSE RETURN_TRANSACTION' . "\t" . ' RESPONSE RAW: ' . $response . ' RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
    	$this->lastOperation = 'DMS RETURN';

    	if (curl_error($merchantHandler)){
    		$this->lastErrorMessage = curl_error($merchantHandler);
    		$this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);
    	}

    	$this->lastResult = $this->parseResult($response);
    	return  $resultArray;
    }

    public function returnSmsTransaction($trans_id ,  $amount, $currency, $clientIpAddress, $domain = 'ts_card')
    {
        //    	$merchantHandlerUrl = $this->merchantHandlerUrl;
        $merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;
        #print("START</br></br>");
        $transactionIdUrlEncoded = urlencode($trans_id);
        $command = self::COMMAND_RETURN_TRANSACTION;
        $postData = 'command=' . $command . '&trans_id=' . $transactionIdUrlEncoded . '&amount=' . $amount . '&currency=' . $currency . '&client_ip_addr=' . $clientIpAddress  ;
        $this->logString(' REQUEST RETURN SMS TO: ' . "\t" . $merchantHandlerUrl . ' WITH _POST ' . $postData, $domain);
        $merchantHandler = curl_init($merchantHandlerUrl);

        curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
        curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
        curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
        /*
         curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->certCacert);
         curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->certPcert);
         curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->certKey);
         */
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
        curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
        curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
        curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($merchantHandler);
        $resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray($response);
        $this->logString(' RESPONSE RETURN_TRANSACTION' . "\t" . ' RESPONSE RAW: ' . $response . ' RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
        $this->lastOperation = 'SMS RETURN';

        if (curl_error($merchantHandler)){
            $this->lastErrorMessage = curl_error($merchantHandler);
            $this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);
        }

        $this->lastResult = $this->parseResult($response);
        return  $resultArray;
    }


    public function resultTransaction($transactionId, $currency, $clientIpAddress, $domain = 'ww_card')
    {
//        $merchantHandlerUrl = $this->merchantHandlerUrl;
        $merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;
        #print("START</br></br>");
        $transactionIdUrlEncoded = urlencode($transactionId);

        $postData = 'command=' . self::COMMAND_TRANSACTION_RESULT . '&trans_id=' . $transactionIdUrlEncoded . '&client_ip_addr=' . $clientIpAddress  ;
        $this->logString(' REQUEST RESULT TRANSACTION ' . "\t" . $merchantHandlerUrl . ' POST ' . $postData, $domain);

        #echo "1. POSTDATA ->", $postData, "\n</br>\n</br>";
        $merchantHandler = curl_init($merchantHandlerUrl);
        #echo "2. CURL_INIT -> $merchantHandlerUrl\n</br>\n</br>";
        curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
        curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
        curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
/*
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->certCacert);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->certPcert);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->certKey);
*/
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
        curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
        curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
        curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($merchantHandler);
        $resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray($response);
        $this->logString(' RESPONSE RESULT TRANSACTION  ' . "\t" . 'RESPONSE RAW: ' . $response . ' RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
        $this->lastOperation = 'RESULT';

        if (curl_error($merchantHandler)){
            $this->lastErrorMessage = curl_error($merchantHandler);
            $this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);
        }

		$this->lastResult = $this->parseResult($response);
		return $this->lastResult; // $resultArray;
    }

    public function closeDay($currency, $domain = 'ww_card')
    {
//        $merchantHandlerUrl = $this->merchantHandlerUrl;
        $merchantHandlerUrl = $this->MHU1.$this->main_prm[$domain]['MHU_Port'].$this->MHU2;
        #print("START</br></br>");
        $postData = 'command=' . self::COMMAND_CLOSE_DAY ;
       	$this->logString(__METHOD__ . ' REQUEST ' . "\t" . $merchantHandlerUrl . ' POST ' . $postData, $domain);

        #echo "1. POSTDATA ->", $postData, "\n</br>\n</br>";
        $merchantHandler = curl_init($merchantHandlerUrl);
        #echo "2. CURL_INIT -> $merchantHandlerUrl\n</br>\n</br>";
        curl_setopt($merchantHandler, CURLOPT_VERBOSE, false);
        curl_setopt($merchantHandler, CURLOPT_CERTINFO, true);
        curl_setopt($merchantHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($merchantHandler, CURLOPT_SSL_VERIFYHOST, false);
/*
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->certCacert);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->certPcert);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->certKey);
*/
        curl_setopt($merchantHandler, CURLOPT_CAINFO, $this->main_prm['dir'].$this->main_prm[$domain]['cacert']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERT, $this->main_prm['dir'].$this->main_prm[$domain]['pcert']);
        curl_setopt($merchantHandler, CURLOPT_SSLKEY, $this->main_prm['dir'].$this->main_prm[$domain]['key']);
        curl_setopt($merchantHandler, CURLOPT_SSLCERTPASSWD, $this->main_prm[$domain]['pass']);
        curl_setopt($merchantHandler, CURLOPT_HEADER, 0);
        curl_setopt($merchantHandler, CURLOPT_POST, TRUE);
        curl_setopt($merchantHandler, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($merchantHandler);
        $resultArray = \frontend\controllers\Helper::InitLikeResultStringToArray2($response);
       	$this->logString(__METHOD__ . ' RESPONSE ' . "\t" . 'RESPONSE RAW: ' . $response . ' RESPONSE AS_ARRAY: ' . var_export($resultArray, true), $domain);
        $this->lastOperation = 'CLOSE DAY';

        if (curl_error($merchantHandler)){
            $this->lastErrorMessage = curl_error($merchantHandler);
           	$this->logString(__METHOD__ . "\t" . 'CurlError:' . $this->lastErrorMessage, $domain);
        }
        return  $resultArray;
    }

    public function parseResult($in)
    {
    	$res = array();
    	$tmp = explode("\n", $in);
    	foreach ($tmp as $k=>$v)
    	{
    		if ($v != '')
    		{
    			$vv = explode(":", $v);
    			$res[trim($vv[0])] = trim($vv[1]);
    		}
    	}
    	return $res;
    }

    public function logString($in, $domain = 'ts_card')
    {
    	switch ($domain)
    	{
    		case 'ww_card': $pref = ' WW: '; break;
    		case 'md_card': $pref = ' MD: '; break;
    		case 'ts_card': $pref = ' TST: '; break;
    	}
    	$fd = fopen($this->main_prm[$domain]['log'], 'a');
//    	var_dump($fd, $domain, $in);
    	fwrite($fd, "\n".date('Y-m-d H:i:s').$pref.$in . "\r\n");
    }

}
