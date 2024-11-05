<?php

declare(strict_types=1);

namespace DevAll\Payze\Controller\Payment;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Phrase;
use Psr\Log\LoggerInterface;

class Redirect implements HttpGetActionInterface
{
    /**
     * @param Session $checkoutSession
     * @param LoggerInterface $logger
     * @param RedirectFactory $redirectFactory
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        private readonly Session $checkoutSession,
        private readonly LoggerInterface $logger,
        private readonly RedirectFactory $redirectFactory,
        private readonly MessageManagerInterface $messageManager,
    ) {}

    /**
     * @return ResultInterface|ResponseInterface|ResultRedirect
     */
    public function execute(): ResultInterface|ResponseInterface|ResultRedirect
    {
        try {
            $order = $this->checkoutSession->getLastRealOrder();
            $orderId = $order->getRealOrderId();

            if (!$orderId) {
                throw new LocalizedException(__('No order id found.'));
            }

            $payment = $order->getPayment();
            $redirectUrl = $payment->getAdditionalInformation()['payze_authorize']['payment_url'];

            return $this->redirectFactory->create()
                ->setUrl($redirectUrl);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());

            return $this->redirectToCheckoutCart(__('Something went wrong while processing the order.'));
        }
    }

    /**
     * Return to cart with error message
     *
     * @param Phrase $message
     * @return ResultRedirect
     */
    private function redirectToCheckoutCart(Phrase $message): ResultRedirect
    {
        $this->checkoutSession->restoreQuote();

        $this->messageManager->addWarningMessage($message);

        return $this->redirectFactory->create()
            ->setPath('checkout/cart');
    }
}