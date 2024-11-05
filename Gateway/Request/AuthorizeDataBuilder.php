<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Request;

use DevAll\Payze\Model\Ui\ConfigProvider;
use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class AuthorizeDataBuilder implements BuilderInterface
{
    public const PAYZE_API_URI = 'https://payze.io/v2/api/payment/';
    public const METHOD = 'PUT';

    public const WEBHOOK_GATEWAY = 'payze/payment/webhook';
    public const SUCCESS_REDIRECT_GATEWAY = 'payze/payment/success';
    public const ERROR_REDIRECT_GATEWAY = 'payze/payment/error';

    /**
     * @param SubjectReader $subjectReader
     * @param ConfigProvider $configProvider
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
        private readonly ConfigProvider $configProvider,
        private readonly UrlInterface $urlBuilder
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
                    'webhookGateway' => $this->urlBuilder->getUrl(self::WEBHOOK_GATEWAY),
                    'successRedirectGateway' => $this->urlBuilder->getUrl(self::SUCCESS_REDIRECT_GATEWAY),
                    'errorRedirectGateway' => $this->urlBuilder->getUrl(self::ERROR_REDIRECT_GATEWAY)
                ],
                'cardPayment' => [
                    'preauthorize' => true
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