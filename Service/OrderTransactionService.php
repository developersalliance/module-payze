<?php

declare(strict_types=1);

namespace DevAll\Payze\Service;

use DevAll\Payze\Api\OrderRepositoryInterface as PZOrderRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Model\OrderRepository;
use Psr\Log\LoggerInterface;

class OrderTransactionService
{
    /**
     * @param PZOrderRepositoryInterface $pzOrderRepository
     * @param OrderRepository $orderRepository
     * @param LoggerInterface $logger
     * @param PayzeOrderDetails $payzeOrderDetails
     */
    public function __construct(
        private readonly PZOrderRepositoryInterface $pzOrderRepository,
        private readonly OrderRepository $orderRepository,
        private readonly LoggerInterface $logger,
        private readonly PayzeOrderDetails $payzeOrderDetails
    ) {}

    /**
     * Process order transaction
     *
     * @param string $transactionId
     * @return void
     * @throws LocalizedException|\GuzzleHttp\Exception\GuzzleException
     */
    public function process(string $transactionId): void
    {
        $pzOrder = $this->pzOrderRepository->getByTransactionId($transactionId);
        $order = $this->orderRepository->get($pzOrder->getOrderId());

        if ($order->getState() === Order::STATE_CLOSED) {
            $this->logger->info('Don\'t need to update order because state is already closed '. $transactionId);
            return;
        }

        /** @var Payment $payment */
        $payment = $order->getPayment();

        if ($payment->getTransactionId() && $order->getState() === Order::STATE_PROCESSING) {
            $this->logger->info('Don\'t need to update order because state is already in progress '. $transactionId);
            return;
        }

        $this->capture($payment, $order, $transactionId);

        try {
            $pzOrderDetails = $this->payzeOrderDetails->get($transactionId);
        } catch (\Throwable $e) {
            $this->logger->critical($e->getMessage());
            throw new NoSuchEntityException(
                __('Payze Order with ID "%1" does not exist.', $transactionId)
            );
        }

        $this->updatePayzeOrder($pzOrderDetails, $transactionId);
    }

    /**
     * Refund order transaction
     *
     * @param string $transactionId
     * @param float $amount
     * @return void
     * @throws AlreadyExistsException
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function processRefund(string $transactionId, float $amount): void
    {
        $pzOrder = $this->pzOrderRepository->getByTransactionId($transactionId);
        $order = $this->orderRepository->get($pzOrder->getOrderId());

        if ($order->getState() === Order::STATE_CLOSED) {
            $this->logger->info('Don\'t need to update order because state is already closed '. $transactionId);
            return;
        }

        /** @var Payment $payment */
        $payment = $order->getPayment();

        $this->refund($payment, $order, $transactionId, $amount);
    }

    /**
     * Refund payment and change order state to processing
     *
     * @param Payment $payment
     * @param OrderInterface $order
     * @param string $transactionId
     * @param float $amount
     * @return void
     * @throws AlreadyExistsException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function refund(
        Payment $payment,
        OrderInterface $order,
        string $transactionId,
        float $amount
    ): void {
        $payment->setTransactionId($transactionId);
        $payment->setCurrencyCode($order->getBaseCurrencyCode());
        $payment->setNotificationResult(true);
        $payment->registerRefundNotification($amount);
        $payment->setIsTransactionClosed(true);
        $order->setState(Order::STATE_CLOSED);

        if (Order::STATE_CLOSED === $order->getState()) {
            $order->addCommentToStatusHistory(__('Order refunded by Payze.'), Order::STATE_CLOSED);
        }

        $this->orderRepository->save($order);
    }

    /**
     * Capture payment and change order state to processing
     *
     * @param Payment $payment
     * @param OrderInterface $order
     * @param string $transactionId
     * @throws AlreadyExistsException
     * @throws InputException
     * @throws NoSuchEntityException|LocalizedException
     */
    private function capture(Payment $payment, OrderInterface $order, string $transactionId): void
    {
        $payment->setTransactionId($transactionId);
        $payment->setCurrencyCode($order->getBaseCurrencyCode());
        $payment->setNotificationResult(true);
        $payment->capture();
        $payment->setIsTransactionClosed(true);
        $order->setState(Order::STATE_PROCESSING);

        if (Order::STATE_PROCESSING === $order->getState()) {
            $order->addCommentToStatusHistory(__('Order processed by Payze.'), Order::STATE_PROCESSING);
        }

        $this->orderRepository->save($order);
    }

    /**
     * Update Payze order
     *
     * @param array $pzOrderDetails
     * @param string $transactionId
     * @return void
     */
    private function updatePayzeOrder(array $pzOrderDetails, string $transactionId): void
    {
        $payzeOrder = $this->pzOrderRepository->getByTransactionId($transactionId);

        $payzeOrder->setStatus($pzOrderDetails['status']);
        $payzeOrder->setPaymentSource($pzOrderDetails['source']);
        $payzeOrder->setPaymentType($pzOrderDetails['type']);
        $payzeOrder->setCardMask($pzOrderDetails['card_payment']['cardMask']);
        $payzeOrder->setCardExpiration($pzOrderDetails['card_payment']['cardExpiration']);

        $this->pzOrderRepository->save($payzeOrder);
    }
}