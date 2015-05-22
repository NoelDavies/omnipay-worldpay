<?php

namespace Omnipay\WorldPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\WorldPay\Message\CompletePurchaseRequest;
use Omnipay\WorldPay\Message\PurchaseRequest;

/**
 * WorldPay Gateway
 *
 * @link https://online.worldpay.com/docs
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'WorldPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'serviceKey' => 'T_S_9d79755c-bcc6-4205-8488-2a3b8771e777',
            'clientKey' => 'T_C_0a1d72b4-39ec-4bc3-b558-35860dda8197'
        );
    }

    public function getServiceKey()
    {
        return $this->getParameter('serviceKey');
    }

    public function setServiceKey($value)
    {
        return $this->setParameter('serviceKey', $value);
    }

    public function getClientKey()
    {
        return $this->getParameter('clientKey');
    }

    public function setClientKey($value)
    {
        return $this->setParameter('clientKey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\WorldPay\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\WorldPay\Message\CompletePurchaseRequest', $parameters);
    }

}