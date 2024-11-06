<?php

declare(strict_types=1);

namespace DevAll\Payze\Controller\Payment;

use DevAll\Payze\Service\OrderTransactionService;
use Exception;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Webhook implements HttpPostActionInterface
{
    /**
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     * @param LoggerInterface $logger
     * @param OrderTransactionService $orderTransactionService
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly JsonFactory $resultJsonFactory,
        private readonly LoggerInterface $logger,
        private readonly OrderTransactionService $orderTransactionService
    ) {}

    /**
     * Action to process webhook
     *
     * @return Json
     */
    public function execute(): Json
    {
        $resultPage = $this->resultJsonFactory->create();
        $orderReference = $this->request->getParam('PaymentId');
        $paymentStatus = $this->request->getParam('PaymentStatus');

        if (!$orderReference) {
            $resultPage->setHttpResponseCode(404);

            return $resultPage;
        }

        try {
            if ($paymentStatus !== 'Refunded') {
                throw new LocalizedException(__('Webhook operation not supported'));
            }

            $this->orderTransactionService->processRefund($orderReference);

            $resultPage->setHttpResponseCode(200);
            $resultPage->setData([]);

            return $resultPage;
        } catch (NoSuchEntityException $e) {
            $resultPage->setData(['message' => __('No such entity')]);
            $resultPage->setHttpResponseCode(400);

            return $resultPage;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $resultPage->setHttpResponseCode(500);

            return $resultPage;
        }
    }
}