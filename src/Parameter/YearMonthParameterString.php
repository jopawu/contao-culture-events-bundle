<?php

namespace Jopawu\ContaoCultureEventsBundle\Parameter;

class YearMonthParameterString
{
    /**
     * @param string $yearMonth
     * @return array|null
     */
    public static function split(string $yearMonth): ?array
    {
        $yearMonthArray = explode('-', $yearMonth);

        foreach($yearMonthArray as $key => $val)
        {
            $yearMonthArray[$key] = (int)$val;
        }

        return $yearMonthArray;
    }
}
