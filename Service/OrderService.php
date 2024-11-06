<?php

declare(strict_types=1);

namespace DevAll\Payze\Service;

use DevAll\Payze\Api\Data\OrderInterface;
use DevAll\Payze\Api\Data\OrderInterfaceFactory;
use DevAll\Payze\Api\OrderRepositoryInterface;

class OrderService
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderInterfaceFactory $orderInterfaceFactory
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderInterfaceFactory $orderInterfaceFactory
    ) {}

    /**
     * Generate a new Payze order
     *
     * @param int $orderId
     * @param string $transactionId
     * @param string $paymentUrl
     * @return OrderInterface
     */
    public function create(
        int $orderId,
        string $transactionId,
        string $paymentUrl
    ): OrderInterface {
        $pzOrder = $this->orderInterfaceFactory->create([
            'data' => [
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'payment_url' => $paymentUrl,
            ]
        ]);

        return $this->orderRepository->save($pzOrder);
    }
}