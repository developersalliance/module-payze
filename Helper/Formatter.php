<?php

declare(strict_types=1);

namespace DevAll\Payze\Helper;

trait Formatter
{
    /**
     * Turn camel case string to underscore
     *
     * @param string $string
     * @return string
     */
    public function camelToUnderscore(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}