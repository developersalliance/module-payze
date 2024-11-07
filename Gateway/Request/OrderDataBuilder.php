<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Request;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class OrderDataBuilder implements BuilderInterface
{
    /**
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
    ) {}

    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        $order = $paymentDO->getOrder();

        return [
            'metadata' => [
                'channel' => 'WEB',
                'order' => [
                    'orderId' => $order->getOrderIncrementId(),
                    'billingAddress' => [
                        'firstName' => $order->getBillingAddress()->getFirstname(),
                        'lastName' => $order->getBillingAddress()->getLastname(),
                        'phoneNumber' => $order->getBillingAddress()->getTelephone(),
                        'line1' => $order->getBillingAddress()->getStreetLine1(),
                        'line2' => $order->getBillingAddress()->getStreetLine2(),
                        'city' => $order->getBillingAddress()->getCity(),
                        'country' => $order->getBillingAddress()->getCountryId(),
                        'postalCode' => $order->getBillingAddress()->getPostcode()
                    ],
                    'shippingAddress' => [
                        'firstName' => $order->getShippingAddress()->getFirstname(),
                        'lastName' => $order->getShippingAddress()->getLastname(),
                        'phoneNumber' => $order->getShippingAddress()->getTelephone(),
                        'line1' => $order->getShippingAddress()->getStreetLine1(),
                        'line2' => $order->getShippingAddress()->getStreetLine2(),
                        'city' => $order->getShippingAddress()->getCity(),
                        'country' => $order->getShippingAddress()->getCountryId(),
                        'postalCode' => $order->getShippingAddress()->getPostcode()
                    ],
                ]
            ]
        ];
    }
}