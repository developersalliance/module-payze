<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Response;

use DevAll\Payze\Api\OrderRepositoryInterface;
use DevAll\Payze\Helper\Formatter;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class RefundDataHandler implements HandlerInterface
{
    use Formatter;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    /**
     * @inheritdoc
     */
    public function handle(array $handlingSubject, array $response): void
    {
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        /** @var OrderPaymentInterface $payment */
        $payment = $paymentDO->getPayment();
        $order = $payment->getOrder();

        $payzeOrder = $this->orderRepository->getByOrderId((int) $order->getId());
        $payzeOrder->setStatus('Refunded');
        $this->orderRepository->save($payzeOrder);

        $payment->setIsTransactionClosed(true);
    }
}