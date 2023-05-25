<?php
namespace Omnipay\Cryptapi;

use Omnipay\Common\AbstractGateway;

/**
 * Cryptapi Gateway
 *
 *
 *
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Cryptapi';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
        ];
    }

    public function getToken() {
        return $this->getParameter('token');
    }
    
    public function setToken($value) {
        return $this->setParameter('token', $value);
    }
    
    public function getCoin() {
        return $this->getParameter('coin');
    }
    
    public function setCoin($value) {
        return $this->setParameter('coin', $value);
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
    
    /**
     * Create a new charge.
     *
     * @param  array $parameters request parameters
     *
     * @return Message\PaymentResponse               response
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('Omnipay\Cryptapi\Message\PaymentRequest', $parameters);
    }

    /**
     * Finalises a payment (callback).
     *
     * @param  array $parameters request parameters
     *
     * @return Message\CompletePurchaseRequest               response
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('Omnipay\Cryptapi\Message\CompletePurchaseRequest', $parameters);
    }
    
    /**
     * Get info.
     *
     * @param  array $parameters request parameters
     *
     * @return Message\InfoResponse               response
     */
    public function info()
    {
        return $this->createRequest('Omnipay\Cryptapi\Message\InfoRequest', []);
    }
}
