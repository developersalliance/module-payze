<?php

declare(strict_types=1);

namespace DevAll\Payze\Service;

use DevAll\Payze\Api\Data\OrderInterface;
use DevAll\Payze\Api\Data\OrderInterfaceFactory;
use DevAll\Payze\Api\OrderRepositoryInterface;
use DevAll\Payze\Helper\Formatter;
use DevAll\Payze\Model\OrderFactory;

class OrderService
{
    use Formatter;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderFactory $orderFactory
     * @param PayzeOrderDetails $payzeOrderDetails
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderFactory $orderFactory,
        private readonly PayzeOrderDetails $payzeOrderDetails,
    ) {}

    /**
     * Generate a new Payze order
     *
     * @param int $orderId
     * @param string $transactionId
     * @return OrderInterface
     */
    public function create(
        int $orderId,
        string $transactionId,
    ): OrderInterface {
        $apiDetails = $this->payzeOrderDetails->get($transactionId);
        $details = [];
        $details['order_id'] = $orderId;

        \array_walk($apiDetails, function ($key, $value) use ($details) {
            $details[$this->camelToUnderscore($key)] = $value;
        });

        $pzOrder = $this->orderFactory->create([
            'data' => $details
        ])->getDataModel();

        return $this->orderRepository->save($pzOrder);
    }
}