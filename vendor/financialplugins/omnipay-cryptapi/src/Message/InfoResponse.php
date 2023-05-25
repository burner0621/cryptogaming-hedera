<?php
namespace Omnipay\Cryptapi\Message;

use Omnipay\Common\Message\AbstractResponse;

class InfoResponse extends AbstractResponse
{
    /**
     * @return false
     */
    public function isSuccessful()
    {
        return false;
    }
    
    
}
