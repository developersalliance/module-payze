<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Response;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class PaymentDetailsHandler implements HandlerInterface
{
    /**
     * PaymentDetailsHandler Constructor
     *
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
    ) {}

    /**
     * @inheritdoc
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        /** @var OrderPaymentInterface $payment */
        $payment = $paymentDO->getPayment();
        $payment->setLastTransId($response['data']['payment']['transactionId']);
        $payment->setAdditionalInformation('transaction_id', $response['data']['payment']['transactionId']);
        $payment->setAdditionalInformation('payment_url', $response['data']['payment']['paymentUrl']);
    }
}
