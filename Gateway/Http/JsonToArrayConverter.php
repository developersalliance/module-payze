<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Http;

use Magento\Payment\Gateway\Http\ConverterInterface;

class JsonToArrayConverter implements ConverterInterface
{
    /**
     * Converts gateway response to ENV structure
     *
     * @param mixed $response
     * @return array
     * @throws \InvalidArgumentException
     */
    public function convert($response): array
    {
        $response = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Unable to unserialize value.');
        }
        return $response;
    }
}