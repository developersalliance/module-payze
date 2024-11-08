<?php

declare(strict_types=1);

namespace DevAll\Payze\Service;

use DevAll\Payze\Helper\Formatter;
use DevAll\Payze\Model\Ui\ConfigProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class PayzeOrderDetails
{
    use Formatter;

    public const PAYZE_API_URL = 'https://payze.io/v2/api/payment/query/token-based';

    /**
     * @param Client $client
     * @param ConfigProvider $configProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Client $client,
        private readonly ConfigProvider $configProvider,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Get Payze order details
     *
     * @param string $transactionId
     * @return array
     * @throws GuzzleException
     */
    public function get(string $transactionId): array
    {
        $query = \sprintf('?$filter=transactionId eq \'%s\'', $transactionId);
        $result = [];

        $response = $this->client->get(
            self::PAYZE_API_URL . $query,
            [
                'headers' => [
                    'Authorization' => \sprintf(
                        '%s:%s',
                        $this->configProvider->getApiKey(),
                        $this->configProvider->getApiSecret()
                    ),
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        $response = json_decode($response->getBody()->getContents(), true);

        foreach($response['data']['value'][0] as $key => $value) {
            $result[$this->camelToUnderscore($key)] = $value;
        }

        return $result;
    }
}