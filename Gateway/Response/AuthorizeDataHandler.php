<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Response;

use DevAll\Payze\Helper\Formatter;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class AuthorizeDataHandler implements HandlerInterface
{
    use Formatter;

    /**
     * PaymentDetailsHandler Constructor
     *
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        private readonly SubjectReader $subjectReader
    ) {}

    /**
     * @inheritdoc
     */
    public function handle(array $handlingSubject, array $response): void
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        /** @var OrderPaymentInterface $payment */
        $payment = $paymentDO->getPayment();
        $payzeInfo = [];
        $payment->setLastTransId($response['data']['payment']['transactionId']);
        foreach ($response['data']['payment'] as $key => $value) {
            if (is_string($value)) {
                $payzeInfo[$key] = $this->camelToUnderscore($value);
            } else {
                $payzeInfo[$key] = $value;
            }
        }

        $payment->setAdditionalInformation('payze_authorize', $payzeInfo);
    }
}
