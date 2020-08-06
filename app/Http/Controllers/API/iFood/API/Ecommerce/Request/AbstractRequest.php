<?php

namespace iFood\API\Ecommerce\Request;

use iFood\API\Merchant;

/**
 * Class AbstractSaleRequest
 *
 * @package iFood\API\Ecommerce\Request
 */
abstract class AbstractRequest
{

    private $merchant;

    /**
     * AbstractSaleRequest constructor.
     *
     * @param Merchant $merchant
     */
    public function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * @param $param
     *
     * @return mixed
     */
    public abstract function execute($param);

    /**
     * @param                        $method
     * @param                        $url
     * @param \JsonSerializable|null $content
     *
     * @return mixed
     *
     * @throws \iFood\API\Ecommerce\Request\ifoodRequestException
     * @throws \RuntimeException
     */
    protected function sendRequest($method, $url, \JsonSerializable $content = null)
    {
        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip',
//            'Authorization: bearer {acess_token}',
            'Cache-Control: no-cache',
//            'Content-Type: application/json'

//            'User-Agent: iFood/PHP SDK',
//            'RequestId: ' . uniqid()
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if ($content !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($content));

            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response   = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            throw new \RuntimeException('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        return $this->readResponse($statusCode, $response);
    }

    /**
     * @param $statusCode
     * @param $responseBody
     *
     * @return mixed
     *
     * @throws ifoodRequestException
     */
    protected function readResponse($statusCode, $responseBody)
    {
        $unserialized = null;

        switch ($statusCode) {
            case 200:
            case 201:
            case 202:
                $unserialized = $this->unserialize($responseBody);
                break;
            case 400:
                $exception = null;
                $response  = json_decode($responseBody);

                $iFoodError = new ifoodError('400', 'Bad Request');
                $exception  = new ifoodRequestException('Bad Request Error', $statusCode, $exception);
                $exception->setiFoodError($iFoodError);


                throw $exception;
            case 401:
                $response  = json_decode($responseBody);

                $exception  = new ifoodRequestException('Unauthorized', $statusCode, $response);

                throw $exception;
            case 403:
                $response  = json_decode($responseBody);

                $exception  = new ifoodRequestException('Forbidden', $statusCode, $response);

                throw $exception;
            case 404:
                throw new ifoodRequestException('Resource not found', 404, null);
            case 429:
                $response  = json_decode($responseBody);

                $exception  = new ifoodRequestException('Too many requests', $statusCode, $response);

                throw $exception;
            default:
                throw new ifoodRequestException('Unknown status', $statusCode);
        }

        return $unserialized;
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    protected abstract function unserialize($json);
}
