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
    public const PAYMENT_TYPE = 'payment_type';
    public const PAYMENT_SOURCE = 'payment_source';
    public const CARD_MASK = 'card_mask';
    public const CARD_EXPIRATION = 'card_expiration';
    public const STATUS = 'status';
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
     * @return string|null
     */
    public function getPaymentUrl(): ?string;

    /**
     * Set payment url
     *
     * @param ?string $paymentUrl
     * @return OrderInterface
     */
    public function setPaymentUrl(?string $paymentUrl): OrderInterface;

    /**
     * Get payment type
     *
     * @return ?string
     */
    public function getPaymentType(): ?string;

    /**
     * Set payment type
     *
     * @param ?string $paymentType
     * @return OrderInterface
     */
    public function setPaymentType(?string $paymentType): OrderInterface;

    /**
     * Get payment source
     *
     * @return ?string
     */
    public function getPaymentSource(): ?string;

    /**
     * Set payment source
     *
     * @param ?string $paymentSource
     * @return OrderInterface
     */
    public function setPaymentSource(?string $paymentSource): OrderInterface;

    /**
     * Get card mask
     *
     * @return ?string
     */
    public function getCardMask(): ?string;

    /**
     * Set card mask
     *
     * @param ?string $cardMask
     * @return OrderInterface
     */
    public function setCardMask(?string $cardMask): OrderInterface;

    /**
     * Get card expiration
     *
     * @return ?string
     */
    public function getCardExpiration(): ?string;

    /**
     * Set card expiration
     *
     * @param ?string $cardExpiration
     * @return OrderInterface
     */
    public function setCardExpiration(?string $cardExpiration): OrderInterface;

    /**
     * Get status
     *
     * @return ?string
     */
    public function getStatus(): ?string;

    /**
     * Set status
     *
     * @param ?string $status
     * @return OrderInterface
     */
    public function setStatus(?string $status): OrderInterface;

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