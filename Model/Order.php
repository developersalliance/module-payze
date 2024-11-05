<?php

declare(strict_types=1);

namespace DevAll\Payze\Model;

use DevAll\Payze\Api\Data\OrderInterface as PZOrderInterface;
use DevAll\Payze\Api\Data\OrderInterfaceFactory as PZOrderInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Order extends AbstractModel
{
    public const CACHE_TAG = 'payze_order';

    /**
     * @var string
     */
    protected $_eventPrefix = 'payze_order';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DataObjectHelper $dataObjectHelper
     * @param PZOrderInterfaceFactory $pzOrderDataFactory
     * @param ResourceModel\Order|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly PZOrderInterfaceFactory $pzOrderDataFactory,
        ResourceModel\Order $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Order::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Retrieve PZ order model with PZ Order data
     *
     * @return PZOrderInterface
     */
    public function getDataModel(): PZOrderInterface
    {
        $pzOrderData = $this->getData();

        $pzOrderDataObject = $this->pzOrderDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $pzOrderDataObject,
            $pzOrderData,
            PZOrderInterface::class
        );

        return $pzOrderDataObject;
    }

}