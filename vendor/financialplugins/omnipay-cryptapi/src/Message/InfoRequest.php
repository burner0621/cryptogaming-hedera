<?php
namespace Omnipay\Cryptapi\Message;

use DateTime;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\RuntimeException;

class InfoRequest extends AbstractRequest
{
    
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
    
    public function isToken() {
        return $this->getParameter('token') != null;
        // 'erc20', 'bep20', 'trc20'
    }
    
    /**
     * Get the data for this request.
     *
     * @return array request data
     */
    public function getData()
    {
        return [];
    }

    /**
     * @param array $data payment data to send
     *
     * @return PaymentResponse payment response
     */
    public function sendData($data)
    {
        $info = $this->httpClient->request('GET', $this->getEndpoint());
        $r = (string)$info->getBody()->getContents();
        $info = json_decode($r, true);
        return $this->response = new InfoResponse($this, $info);
    }

    /**
     * Get the endpoint for this request.
     *
     * @return string endpoint
     */
    public function getEndpoint()
    {
        return 'https://api.cryptapi.io/' . ($this->isToken() ? (strtolower($this->getToken()) . '/') : '') . strtolower($this->getCoin()) . '/info/';
    }
}
