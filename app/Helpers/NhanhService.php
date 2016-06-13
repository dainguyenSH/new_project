<?php

/**
 * NhanhService
 *
 * @author      Chu Khanh Van
 * @category   	NhanhAPI library
 * @copyright  	http://nhanh.vn
 * @license    	http://nhanh.vn/license/
 */
namespace App\Helpers;
class NhanhService
{

    const URI_STORE_ADD         = '/api/store/add';
    const URI_GET_PRODUCT       = '/api/product/detail';
    const URI_ADD_PRODUCT       = '/api/product/add';
    const URI_SHIPPING_FEE      = '/api/shipping/fee';
    const URI_SHIPPING_CARRIER  = '/api/shipping/carrier';
    const URI_ORDER_ADD         = '/api/order/add';
    const URI_ORDER_UPDATE      = '/api/order/update';
    const URI_CUSTOMER_SEARCH   = '/api/customer/search';
    const URI_BILL_SEARCH       = '/api/bill/search';
    const URI_CUSTOMER_ADD      = '/api/customer/add';

    /**
     * The server will use this parameter to process your request
     */
    const SERVICE_VERSION = '1.0'; // please DO NOT change or remove this value

    /**
     * the server address.
     * testing: http://dev.nhanh.vn
     * production: https://nhanh.vn
     *
     * @var string
     */
    // protected $server = "http://cuongnv.dev.nhanh.vn";
    protected $server = "https://nhanh.vn";

    /**
     * merchantId
     *
     * @var string
     */
    protected $merchantId = "8128";

    /**
     * apiUsername
     *
     * @var string
     */
    protected $apiUsername = "bosua.vn";

    /**
     * secretKey
     *
     * @var string
     */
    protected $secretKey = "q565ryhVdbvxsadweBtujndZq35467uh";

    /**
     *
     * @return the $server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     *
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     *
     * @return the $merchantId
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     *
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     *
     * @return the $apiUsername
     */
    public function getApiUsername()
    {
        return $this->apiUsername;
    }

    /**
     *
     * @param string $apiUsername
     */
    public function setApiUsername($apiUsername)
    {
        $this->apiUsername = $apiUsername;
    }

    /**
     *
     * @return the $secretKey
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     *
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * create the checksum
     *
     * @param string|array $data
     */
    public function createChecksum($data)
    {
        if (is_array($data)) {
            $dataString = json_encode($data);
        } else {
            $dataString = $data;
        }
        return md5(md5($this->getSecretKey() . $dataString) . $dataString);
    }

    /**
     * validate the checksum of data
     *
     * @param string|array $data
     * @param string $checksum
     */
    public function isValidChecksum($checksum, $data)
    {
        return $checksum == $this->createChecksum($data);
    }

    /**
     * send request to Nhanh.vn
     *
     * @return stdClass
     */
    public function sendRequest($requestUri, $data = null, $storeId = null)
    {
        if (! function_exists('curl_init')) {
            throw new Exception("curl extension is required to process this request");
            return false;
        }

        $dataString = $data;
        if (is_array($data)) {
            $dataString = json_encode($data);
        }
        $postFields = array(
            "version" => self::SERVICE_VERSION,
            "merchantId" => $this->getMerchantId(),
            "apiUsername" => $this->getApiUsername(),
            "storeId" => $storeId,
            "data" => $dataString,
            "checksum" => $this->createChecksum($dataString)
        );

        $curl = curl_init($this->getServer() . $requestUri);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_CAINFO, storage_path().'/SSL/cacert.pem');
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curlResult = curl_exec($curl);

        if (curl_error($curl) == "") {
            $response = json_decode($curlResult);
        } else {
            $response = new \stdClass();
            $response->code = 0;
            $response->messages = array(
                curl_error($curl)
            );
        }
        curl_close($curl);

        return $response;
    }
}