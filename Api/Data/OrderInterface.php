<?php

declare(strict_types=1);

namespace DevAll\Payze\Api\Data;

interface OrderInterface
{
    public const TABLE_NAME = 'payze_payment_order';

    public const ID = 'id';
    public const ORDER_ID = 'order_id';
    public const TRANSACTION_ID = 'transaction_id';
    public const PAYMENT_URL = 'payment_url';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * Get order id
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Set order id
     *
     * @param int $orderId
     * @return OrderInterface
     */
    public function setOrderId(int $orderId): OrderInterface;

    /**
     * Get transaction id
     *
     * @return string
     */
    public function getTransactionId(): string;

    /**
     * Get transaction id
     *
     * @param string $transactionId
     * @return OrderInterface
     */
    public function setTransactionId(string $transactionId): OrderInterface;

    /**
     * Get payment url
     *
     * @return string
     */
    public function getPaymentUrl(): string;

    /**
     * Set payment url
     *
     * @param string $paymentUrl
     * @return OrderInterface
     */
    public function setPaymentUrl(string $paymentUrl): OrderInterface;

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set created at
     *
     * @param string|null $createdAt
     * @return OrderInterface
     */
    public function setCreatedAt(?string $createdAt): OrderInterface;

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set updated at
     *
     * @param string|null $updatedAt
     * @return OrderInterface
     */
    public function setUpdatedAt(?string $updatedAt): OrderInterface;

}