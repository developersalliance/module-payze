<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Request;

use DevAll\Payze\Model\Ui\ConfigProvider;
use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class AuthorizeDataBuilder implements BuilderInterface
{
    public const WEBHOOK_GATEWAY = 'payze/payment/webhook';
    public const RESULT_REDIRECT_GATEWAY = 'payze/payment/result';

    /**
     * @param SubjectReader $subjectReader
     * @param UrlInterface $urlBuilder
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
        private readonly UrlInterface $urlBuilder,
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
                'language' => $this->configProvider->getLanguage(),
                'hooks' => [
                    'webhookGateway' => $this->urlBuilder->getUrl(self::WEBHOOK_GATEWAY),
                    'successRedirectGateway' => $this->urlBuilder->getUrl(self::RESULT_REDIRECT_GATEWAY),
                    'errorRedirectGateway' => $this->urlBuilder->getUrl(self::RESULT_REDIRECT_GATEWAY)
                ],
                'cardPayment' => [
                    'preauthorize' => true
                ]
            ],
        ];
    }
}