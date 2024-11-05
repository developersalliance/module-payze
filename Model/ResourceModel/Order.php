<?php

declare(strict_types=1);

namespace DevAll\Payze\Model\ResourceModel;

use DevAll\Payze\Api\Data\OrderInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Order extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(OrderInterface::TABLE_NAME, OrderInterface::ID);
    }
}