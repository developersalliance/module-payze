<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Request;

use DevAll\Payze\Model\Ui\ConfigProvider;
use Magento\Payment\Gateway\Request\BuilderInterface;

class ApiDataBuilder implements BuilderInterface
{
    /**
     * @param ConfigProvider $configProvider
     * @param string $method
     * @param string $payzeApiUri
     */
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly string $method,
        private readonly string $payzeApiUri
    ) {}

    /**
     * @inheritDoc
     */
    public function build(array $buildSubject): array
    {
        return [
            'api' => [
                'uri' => $this->payzeApiUri,
                'method' => $this->method,
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