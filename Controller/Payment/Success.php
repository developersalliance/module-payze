<?php

declare(strict_types=1);

namespace DevAll\Payze\Controller\Payment;

use DevAll\Payze\Service\OrderTransactionService;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Psr\Log\LoggerInterface;

class Success implements HttpGetActionInterface
{
    /**
     * @param RequestInterface $request
     * @param CheckoutSession $checkoutSession
     * @param RedirectFactory $redirectFactory
     * @param MessageManagerInterface $messageManager
     * @param LoggerInterface $logger
     * @param OrderTransactionService $orderTransactionService
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly CheckoutSession $checkoutSession,
        private readonly RedirectFactory $redirectFactory,
        private readonly MessageManagerInterface $messageManager,
        private readonly LoggerInterface $logger,
        private readonly OrderTransactionService $orderTransactionService
    ) {}

    /**
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $transactionId = $this->request->getParam('payment_transaction_id');

        try {
            $this->orderTransactionService->process($transactionId);

            return $this->redirectFactory->create()->setPath('checkout/onepage/success');
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->checkoutSession->restoreQuote();
            $this->messageManager->addWarningMessage(__('Something went wrong while processing the order.'));

            return $this->redirectFactory->create()->setPath('checkout/cart');
        }
    }
}