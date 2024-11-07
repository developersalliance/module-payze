<?php

declare(strict_types=1);

namespace DevAll\Payze\Model\Data;

use DevAll\Payze\Api\Data\OrderInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Order extends AbstractExtensibleModel implements OrderInterface
{
    /**
     * @inheritDoc
     */
    public function getOrderId(): int
    {
        return (int) $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId(int $orderId): OrderInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getTransactionId(): string
    {
        return $this->getData(self::TRANSACTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTransactionId(string $transactionId): OrderInterface
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
    }

    /**
     * @inheritDoc
     */
    public function getPaymentUrl(): ?string
    {
        return $this->getData(self::PAYMENT_URL);
    }

    /**
     * @inheritDoc
     */
    public function setPaymentUrl(?string $paymentUrl): OrderInterface
    {
        return $this->setData(self::PAYMENT_URL, $paymentUrl);
    }

    /**
     * @inheritDoc
     */
    public function getPaymentType(): ?string
    {
        return $this->getData(self::PAYMENT_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setPaymentType(?string $paymentType): OrderInterface
    {
        return $this->setData(self::PAYMENT_TYPE, $paymentType);
    }

    /**
     * @inheritDoc
     */
    public function getPaymentSource(): ?string
    {
        return $this->getData(self::PAYMENT_SOURCE);
    }

    /**
     * @inheritDoc
     */
    public function setPaymentSource(?string $paymentSource): OrderInterface
    {
        return $this->setData(self::PAYMENT_SOURCE, $paymentSource);
    }

    /**
     * @inheritDoc
     */
    public function getCardMask(): ?string
    {
        return $this->getData(self::CARD_MASK);
    }

    /**
     * @inheritDoc
     */
    public function setCardMask(?string $cardMask): OrderInterface
    {
        return $this->setData(self::CARD_MASK, $cardMask);
    }

    /**
     * @inheritDoc
     */
    public function getCardExpiration(): ?string
    {
        return $this->getData(self::CARD_EXPIRATION);
    }

    /**
     * @inheritDoc
     */
    public function setCardExpiration(?string $cardExpiration): OrderInterface
    {
        return $this->setData(self::CARD_EXPIRATION, $cardExpiration);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(?string $status): OrderInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(?string $createdAt): OrderInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(?string $updatedAt): OrderInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}