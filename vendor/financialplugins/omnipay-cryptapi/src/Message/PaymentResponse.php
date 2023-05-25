<?php
namespace Omnipay\Cryptapi\Message;

use Omnipay\Common\Message\AbstractResponse;

class PaymentResponse extends AbstractResponse
{
    /**
     * @return false
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return TRUE;
    }

    /**
     * @return string status
     */
    public function getStatus()
    {
        return true;
    }
    
}
