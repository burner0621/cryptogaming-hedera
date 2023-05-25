<?php

namespace Omnipay\Cryptapi\Message;

use Omnipay\Common\Message\AbstractResponse;

class StatusCallback extends AbstractResponse
{
    /**
     * Construct a StatusCallback with the respective POST data.
     *
     * @param array $post post data
     */
    public function __construct(array $post)
    {
        $this->data = $post;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data['confirmations'] >= $this->data['confirmations_required'];
    }
}
