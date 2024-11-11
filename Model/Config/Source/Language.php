<?php

declare(strict_types=1);

namespace DevAll\Payze\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Language implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'en', 'label' => __('English')],
            ['value' => 'ge', 'label' => __('Georgian')],
            ['value' => 'uz', 'label' => __('Uzbek')],
        ];
    }
}