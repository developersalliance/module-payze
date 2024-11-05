<?php

namespace DevAll\Payze\Gateway\Request;

use DevAll\Payze\Model\Ui\ConfigProvider;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class CaptureDataBuilder implements BuilderInterface
{
    public const PAYZE_API_URI = 'https://payze.io/v2/api/payment/capture';
    public const METHOD = 'PUT';

    /**
     * @param SubjectReader $subjectReader
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
        private readonly ConfigProvider $configProvider,
    ) {}

    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        return [
            'payment' => [
                'amount' => $this->subjectReader->readAmount($buildSubject),
                'transactionId' => $paymentDO->getPayment()->getLastTransId()
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