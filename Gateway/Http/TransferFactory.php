<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class TransferFactory implements TransferFactoryInterface
{
    /**
     * @param TransferBuilder $transferBuilder
     */
    public function __construct(
        private readonly TransferBuilder $transferBuilder
    ) {}

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request)
    {
        return $this->transferBuilder
            ->setBody($request['payment'])
            ->setMethod($request['api']['method'])
            ->setUri($request['api']['uri'])
            ->setHeaders($request['api']['headers'])
            ->build();
    }
}
