<?php

declare(strict_types=1);

namespace DevAll\Payze\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Payment\Gateway\Config\Config;

class ConfigProvider implements ConfigProviderInterface
{
    public const CODE = 'payze';
    public const XML_PATH_ACTIVE = 'active';
    public const XML_PATH_TITLE = 'title';
    public const XML_PATH_LANGUAGE = 'language';
    public const XML_PATH_API_KEY = 'api_key';
    public const XML_PATH_API_SECRET = 'api_secret';

    /**
     * @param Config $config
     */
    public function __construct(private readonly Config $config) {}

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        if (!$this->config->getValue(self::XML_PATH_ACTIVE)) {
            return [];
        }

        return [
            'payment' => [
                self::CODE => [
                    'isActive' => $this->config->getValue(self::XML_PATH_ACTIVE),
                    'title' => $this->config->getValue(self::XML_PATH_TITLE)
                ]
            ]
        ];
    }

    /**
     * Get API secret
     *
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->config->getValue(self::XML_PATH_API_SECRET) ?? '';
    }

    /**
     * Get API key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->config->getValue(self::XML_PATH_API_KEY) ?? '';
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->config->getValue(self::XML_PATH_LANGUAGE);
    }
}