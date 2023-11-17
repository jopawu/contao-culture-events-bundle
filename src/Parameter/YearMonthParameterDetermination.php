<?php

namespace Jopawu\ContaoCultureEventsBundle\Parameter;

class YearMonthParameterDetermination
{
    /**
     * @var int
     */
    protected $current;

    /**
     * @return YearMonthParameterFallbackDetermination
     */
    public function __construct()
    {
        $this->current = date('Ym');
    }

    /**
     * @param array $availableYearMonths
     * @return string|null
     */
    public function getFallback(array $availableYearMonths): ?string
    {
        foreach($availableYearMonths as $yearMonth)
        {
            $available = str_replace('-', '', $yearMonth);

            if( $available != $this->current )
            {
                continue;
            }

            return $yearMonth;
        }

        $lastYearMonth = null;

        foreach(array_reverse($availableYearMonths) as $yearMonth)
        {
            $available = str_replace('-', '', $yearMonth);

            if( $available <= $this->current )
            {
                return $yearMonth;
            }

            $lastYearMonth = $yearMonth;
        }

        return $lastYearMonth;
    }

    /**
     * @param string $parameter
     * @return bool
     */
    protected static function isYearMonthParameter(string $parameter): bool
    {
        return (bool)preg_match('/^\d{4}-\d{2}$/', $parameter);
    }

    /**
     * @param array $publishingMonths
     * @return string|null
     */
    public static function getParameter(array $publishingMonths) : ?string
    {
        if( isset($_GET['month']) && self::isYearMonthParameter($_GET['month']))
        {
            return $_GET['month'];
        }

        $monthParamDetermination = new self();

        return $monthParamDetermination->getFallback($publishingMonths);
    }
}
