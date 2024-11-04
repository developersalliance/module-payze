<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Request;

use DevAll\Payze\Model\Ui\ConfigProvider;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class PaymentDataBuilder implements BuilderInterface
{
    public const PAYZE_API_URI = 'https://payze.io/v2/api/payment/';
    public const METHOD = 'PUT';


    /**
     * @param SubjectReader $subjectReader
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
        private readonly ConfigProvider $configProvider
    ) {}

    /**
     * @inheritDoc
     */
    public function build(array $buildSubject): array
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        $order = $paymentDO->getOrder();

        return [
            'payment' => [
                'source' => 'Card',
                'amount' => $this->subjectReader->readAmount($buildSubject),
                'currency' => $order->getCurrencyCode(),
                'language' => 'EN',
                'hooks' => [
                    'webhookGateway' => 'https://dev.magento/payze/gateway/webhook',
                    'successRedirectGateway' => 'https://dev.magento/payze/gateway/success',
                    'errorRedirectGateway' => 'https://dev.magento/payze/gateway/error'
                ]
            ],
            'api' => [
                'uri' => self::PAYZE_API_URI,
                'method' => self::METHOD,
                'headers' => [
                    'Authorization' => \sprintf(
                        '%s:%s',
                        $this->configProvider->getApiKey(),
                        $this->configProvider->getApiSecret()
                    ),
                    'Content-Type' => 'application/json'
                ]
            ]
        ];
    }
}