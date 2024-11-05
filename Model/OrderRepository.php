<?php

declare(strict_types=1);

namespace DevAll\Payze\Model;

use DevAll\Payze\Api\Data\OrderInterface;
use DevAll\Payze\Model\ResourceModel\Order as OrderResource;
use DevAll\Payze\Api\OrderRepositoryInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @param OrderResource $orderResource
     * @param OrderFactory $orderFactory
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        private readonly OrderResource $orderResource,
        private readonly OrderFactory $orderFactory,
        private readonly ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {}

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException
     */
    public function getByTransactionId(string $transactionId): OrderInterface
    {
        /** @var \DevAll\Payze\Model\Order $pzOrder */
        $pzOrder = $this->orderFactory->create();
        $this->orderResource->load($pzOrder, $transactionId, 'transaction_id');

        if (!$pzOrder->getId()) {
            throw new NoSuchEntityException(__('Order with transaction ID %1 not found', $transactionId));
        }

        return $pzOrder->getDataModel();
    }

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException
     */
    public function getByOrderId(int $orderId): OrderInterface
    {
        /** @var \DevAll\Payze\Model\Order $pzOrder */
        $pzOrder = $this->orderFactory->create();
        $this->orderResource->load($pzOrder, $orderId, 'order_id');

        if (!$pzOrder->getId()) {
            throw new NoSuchEntityException(__('Order with ID %1 not found', $orderId));
        }

        return $pzOrder->getDataModel();
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(OrderInterface $order): OrderInterface
    {
        $orderData = $this->extensibleDataObjectConverter->toNestedArray(
            $order,
            [],
            OrderInterface::class
        );

        /** @var \DevAll\Payze\Model\Order $pzOrder */
        $pzOrder = $this->orderFactory->create()->setData($orderData);
        try {
            $this->orderResource->save($pzOrder);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the order: %1', $e->getMessage())
            );
        }

        return $pzOrder->getDataModel();
    }
}