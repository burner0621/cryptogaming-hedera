<?php
namespace Omnipay\Skrill\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Skrill Payment Response
 *
 * This is the associated response to our PaymentRequest where we get Skrill's session,
 * and thus the URL to where we shall redirect users to the payment page.
 */
class PaymentResponse extends AbstractResponse implements RedirectResponseInterface
{
    private $responseData = NULL;
    /**
     * @return false
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return $this->getSessionId() !== null;
    }

    /**
     * @return string redirect url
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint() . '?sid=' . $this->getSessionId();
    }

    /**
     * @return string redirect method
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return null
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * Get the session identifier to be submitted at the next step.
     *
     * @return string|null session id
     */
    public function getSessionId()
    {
        $cookie = $this->data->getHeader('Set-Cookie');
        return 
            (
                is_array($cookie) &&
                count($cookie) &&
                preg_match('~SESSION_ID=([0-9a-fA-F]+)~', $cookie[0], $matches)
            )
            ? $matches[1]
            : null;
    }
    
    public function getData()
    {
        if (is_null($this->responseData)) {
            $this->responseData = $this->data->getBody()->getContents();
        }
        return $this->responseData;
    }
    
    public function getTransactionReference()
    {
        return $this->getSessionId();
    }
    
    /**
     * Get the skrill status of this response.
     *
     * @return string status
     */
    public function getStatus()
    {
        return count($this->data->getHeader('X-Skrill-Status'))
            ? (string)$this->data->getHeader('X-Skrill-Status')[0]
            : null;
    }

    /**
     * Get the status code.
     *
     * @return string|null status code
     */
    public function getCode()
    {
        $statusTokens = explode(':', $this->getStatus());
        return array_shift($statusTokens) ?: null;
    }

    /**
     * Get the status message.
     *
     * @return string|null status message
     */
    public function getMessage()
    {
        if ($this->data && $this->data->getStatusCode() == 400) {
            $data = $this->getData();
            if ($data) $data = json_decode($data, true);
            if (is_array($data)) {
                $error = isset($data['code']) ? $data['code'] : 400;
                $message = isset($data['message']) ? $data['message'] : 'Unknown server error';
                return "$message [$error]";
            } else {
                return 'Unknown server error [400]';
            }
        } else {
            $statusTokens = explode(':', $this->getStatus());
            return array_pop($statusTokens) ?: null;
        }
    }
}
