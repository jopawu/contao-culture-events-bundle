<?php

namespace Jopawu\ContaoCultureEventsBundle\Parameter;

class YearMonthParameterString
{
    /**
     * @param string $yearMonth
     * @return array
     */
    public static function split(string $yearMonth): array
    {
        return explode('-', $yearMonth);
    }
}
