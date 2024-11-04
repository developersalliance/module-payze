<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Http\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\ClientFactory;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ConverterException;
use Magento\Payment\Gateway\Http\ConverterInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

class Guzzle implements ClientInterface
{
    /**
     * @param ClientFactory $clientFactory
     * @param Logger $logger
     * @param ConverterInterface $converter
     */
    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly Logger $logger,
        private readonly ConverterInterface $converter
    ) {
    }

    /**
     * @inheritdoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $log = [
            'request' => $transferObject->getBody(),
            'request_uri' => $transferObject->getUri()
        ];
        $result = [];

        /** @var GuzzleClient $client */
        $client = $this->clientFactory->create();

        $options = [
            'headers' => $transferObject->getHeaders(),
            'body' => json_encode($transferObject->getBody()), // assuming JSON payloads
        ];

        if ($transferObject->getMethod() === 'GET') {
            $options['query'] = $transferObject->getBody();
        }

        try {
            $response = $client->request(
                $transferObject->getMethod(),
                $transferObject->getUri(),
                $options
            );

            $body = (string) $response->getBody();
            $result = $this->converter->convert($body);
            $log['response'] = $result;
        } catch (RequestException $e) {
            throw new ClientException(__($e->getMessage()));
        } catch (ConverterException $e) {
            throw $e;
        } finally {
            $this->logger->debug($log);
        }

        return $result;
    }
}
