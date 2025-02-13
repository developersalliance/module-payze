<?php

declare(strict_types=1);

namespace DevAll\Payze\Gateway\Request;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class CaptureDataBuilder implements BuilderInterface
{
    /**
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        private readonly SubjectReader $subjectReader,
    ) {}

    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        return [
            'payment' => [
                'amount' => $this->subjectReader->readAmount($buildSubject),
                'transactionId' => $paymentDO->getPayment()->getLastTransId()
            ],
        ];
    }
}