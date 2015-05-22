<?php
/**
 * Worldpay Abstract Request
 */

namespace Omnipay\Worldpay\Message;

/**
 * Worldpay Abstract Request
 *
 * This is the parent class for all Worldpay requests.
 *
 * Setting the testMode flag on this gateway will enable you to use .  To
 * use test mode just use your test mode API key.
 *
 * You can use any of the cards listed at https://stripe.com/docs/testing
 * for testing.
 *
 * @see \Omnipay\Worldpay\Gateway
 * @link https://online.worldpay.com/api-reference#introduction
 * @method \Omnipay\Stripe\Message\Response send()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Endpoint URL
     *
     * @var string URL
     */
    protected $endpoint = 'https://api.worldpay.com/v1/';

    /**
     * Get the gateway Service Key
     *
     * @return string
     */
    public function getServiceKey()
    {
        return $this->getParameter('serviceKey');
    }

    /**
     * Set the gateway Service Key
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setServiceKey($value)
    {
        return $this->setParameter('serviceKey', $value);
    }
    /**
     * Get the gateway Client Key
     *
     * @return string
     */
    public function getClientKey()
    {
        return $this->getParameter('clientKey');
    }

    /**
     * Set the gateway Client Key
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setClientKey($value)
    {
        return $this->setParameter('clientKey', $value);
    }

    /**
     * @deprecated
     */
    public function getCardToken()
    {
        return $this->getParameter('token');
    }

    /**
     * @deprecated
     */
    public function setCardToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    abstract public function getEndpoint();

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $data
        );
        $httpResponse = $httpRequest
            ->setHeader('Authorization', $this->getServiceKey() )
            ->setHeader('Contnt-type', 'application/json' )
            ->send();

        return $this->response = new Response($this, $httpResponse->json());
    }

    /**
     * Get the card data.
     *
     * Because the stripe gateway uses a common format for passing
     * card data to the API, this function can be called to get the
     * data from the associated card object in the format that the
     * API requires.
     *
     * @return array
     */
    protected function getCardData()
    {
        $this->getCard()->validate();

        $data = array();
        $data['number'] = $this->getCard()->getNumber();
        $data['exp_month'] = $this->getCard()->getExpiryMonth();
        $data['exp_year'] = $this->getCard()->getExpiryYear();
        $data['cvc'] = $this->getCard()->getCvv();
        $data['name'] = $this->getCard()->getName();
        $data['address_line1'] = $this->getCard()->getAddress1();
        $data['address_line2'] = $this->getCard()->getAddress2();
        $data['address_city'] = $this->getCard()->getCity();
        $data['address_zip'] = $this->getCard()->getPostcode();
        $data['address_state'] = $this->getCard()->getState();
        $data['address_country'] = $this->getCard()->getCountry();

        return $data;
    }
}
