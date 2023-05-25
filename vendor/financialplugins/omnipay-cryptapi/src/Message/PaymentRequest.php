<?php
namespace Omnipay\Cryptapi\Message;

use DateTime;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\RuntimeException;

class PaymentRequest extends AbstractRequest
{

    public function isToken() {
        return $this->getToken() != null;
    }

    public function getCoin() {
        return $this->getParameter('coin');
    }

    public function setCoin($value) {
        return $this->setParameter('coin', $value);
    }

    public function getToken() {
        return $this->getParameter('token');
    }

    public function setToken($value) {
        return $this->setParameter('token', $value);
    }

    public function getAddress() {
        return $this->getParameter('address');
    }

    public function setAddress($value) {
        return $this->setParameter('address', $value);
    }

    public function getConfirmations() {
        return $this->getParameter('confirmations');
    }

    public function setConfirmations($value) {
        return $this->setParameter('confirmations', $value);
    }

    public function getEmail() {
        return $this->getParameter('email');
    }

    public function setEmail($value) {
        return $this->setParameter('email', $value);
    }

    public function getPriority() {
        return $this->getParameter('priority');
    }

    public function setPriority($value) {
        return $this->setParameter('priority', $value);
    }

    public function getAmount() {
        return $this->getParameter('amount');
    }

    public function setAmount($value) {
        return $this->setParameter('amount', $value);
    }

    public function getCallback() {
        return $this->getParameter('callback');
    }

    public function setCallback($value) {
        return $this->setParameter('callback', $value);
    }

    /**
     * Get the data for this request.
     *
     * @return array request data
     */
    public function getData()
    {
        $data = [];
        $data['pending'] = 0;
        $data['post'] = 1;
        if ($this->getCallback()) $data['callback'] = $this->getCallback();
        if ($this->getAddress()) $data['address'] = $this->getAddress();
        if ($this->getConfirmations()) $data['confirmations'] = $this->getConfirmations();
        if ($this->getEmail()) $data['email'] = $this->getEmail();
        if ($this->getPriority()) $data['priority'] = $this->getPriority();
        return $data;
    }

    /**
     * @param array $data payment data to send
     *
     * @return PaymentResponse payment response
     */
    public function sendData($data)
    {
        $url = $this->getEndpoint() . '/create/?' . http_build_query(array_intersect_key($data, array_flip(['callback', 'address', 'pending', 'confirmations', 'email', 'post', 'priority'])));
        $httpResponse = $this->httpClient->request('GET', $url);
        if ($httpResponse->getStatusCode() != 200) {
            $err = 'Unexpected cryptapi response for create address ' . PHP_EOL . $url . ' [' . $httpResponse->getStatusCode() . '] with body:' . PHP_EOL . ((string) $httpResponse->getBody()->getContents());
            throw new RuntimeException($err);
        }
        $responseData = json_decode((string)$httpResponse->getBody()->getContents());
        $min = $responseData->minimum_transaction_coin;
        $decimals = strlen(explode('.', $min)[1]);
        $min = floatval($min);
        $this->setAmount(number_format($this->getAmount(), $decimals, '.', ''));
        if ($this->getAmount() < $min) {
            throw new RuntimeException('Minimal transaction is ' . $min . ' but your\'s ' . $this->getAmount());
        }
        $url = $this->getEndpoint() . '/qrcode/?' . http_build_query(['address' => $responseData->address_in, 'value' => $this->getAmount()]);
        $httpResponse = $this->httpClient->request('GET', $url);
        if ($httpResponse->getStatusCode() != 200) {
            $err = 'Unexpected cryptapi response for create qr ' . PHP_EOL . $url . ' [' . $httpResponse->getStatusCode() . '] with body:' . PHP_EOL . ((string) $httpResponse->getBody()->getContents());
            throw new RuntimeException($err);
        }
        $responseQrData = json_decode((string)$httpResponse->getBody()->getContents());
        $responseData->url = $responseQrData->payment_uri;
        $responseData->qr = $responseQrData->qr_code;
        return $this->response = new PaymentResponse($this, $responseData);
    }

    /**
     * Get the endpoint for this request.
     *
     * @return string endpoint
     */
    public function getEndpoint()
    {
        return 'https://api.cryptapi.io/' . ($this->isToken() ? (strtolower($this->getToken()) . '/') : '') . strtolower($this->getCoin());
    }
}
