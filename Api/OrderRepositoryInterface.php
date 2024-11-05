<?php

declare(strict_types=1);

namespace DevAll\Payze\Api;

use DevAll\Payze\Api\Data\OrderInterface;

interface OrderRepositoryInterface
{
    /**
     * Get order by transaction id
     *
     * @param string $transactionId
     * @return OrderInterface
     */
    public function getByTransactionId(string $transactionId): OrderInterface;

    /**
     * Get order by order id
     *
     * @param int $orderId
     * @return OrderInterface
     */
    public function getByOrderId(int $orderId): OrderInterface;

    /**
     * Save order
     *
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function save(OrderInterface $order): OrderInterface;
}