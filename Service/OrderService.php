<?php

declare(strict_types=1);

namespace DevAll\Payze\Service;

use DevAll\Payze\Api\Data\OrderInterface;
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
        try {
            $apiDetails = $this->payzeOrderDetails->get($transactionId);
        } catch (\Throwable $e) {
            $apiDetails = [];
            $apiDetails['transaction_id'] = $transactionId;
        }

        $apiDetails['order_id'] = $orderId;
        unset($apiDetails['id']);

        $pzOrder = $this->orderFactory->create([
            'data' => $apiDetails
        ])->getDataModel();

        return $this->orderRepository->save($pzOrder);
    }
}