<?php

declare(strict_types=1);

/*
 * This file is part of Contao Culture Events.
 *
 * (c) Björn Heyser 2023 <bh@bjoernheyser.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/jopawu/contao-culture-events-bundle
 */

namespace Jopawu\ContaoCultureEventsBundle\Model;

use Contao\Database;
use Contao\Date;
use Contao\Model;
use Contao\Model\Collection;

class CultureEventsModel extends Model
{
    /**
     * @var string
     */
    protected static $strTable = 'tl_culture_events';

    /**
     * @var Collection|null
     */
    protected static $publishedEventsCache = null;

    /**
     * @var Collection|null
     */
    protected static $archivedEventsCache = null;

    /**
     * @return Collection|null
     */
    public static function findPublished(): ?Collection
    {
        if( self::$publishedEventsCache === null )
        {
            $nowTS = time();

            self::$publishedEventsCache = self::findBy(
                ['publishingDate <= ?', 'archiveDate > ?'], [$nowTS, $nowTS]
            );
        }

        return self::$publishedEventsCache;
    }

    /**
     * @return Collection|null
     */
    public static function findArchived(): ?Collection
    {
        if( self::$archivedEventsCache === null )
        {
            $nowTS = time();

            self::$archivedEventsCache = self::findBy(
                ['archiveDate <= ?'], [$nowTS]
            );
        }

        return self::$archivedEventsCache;
    }

    /**
     * @return array
     */
    public static function findPublishedTrips(): array
    {
         $events = [];

        foreach(self::findPublished() as $event)
        {
            if( !$event->isCultureTrip )
            {
                continue;
            }

            $events[] = $event;
        }

        return $events;
    }

    /**
     * @return array
     */
    public static function findYearMonthPublished(int $year, int $month): array
    {
        $monthBeginTS = mktime(0,0,0, $month, 1, $year);
        $monthEndTS = strtotime(date('Y-m-d', $monthBeginTS).' +1 month');

        $events = [];

        foreach(self::findPublished() as $event)
        {
            if( $event->startDate < $monthBeginTS )
            {
                if( !$event->endDate )
                {
                    continue;
                }

                if( $event->endDate < $monthBeginTS )
                {
                    continue;
                }
            }
            elseif( $event->startDate >= $monthEndTS )
            {
                continue;
            }

            $events[] = $event;
        }

        return $events;
    }

    /**
     * @return array
     */
    public static function findPublishingMonths(): array
    {
        $months = [];

        foreach(self::findPublished() as $event)
        {
            /* @var self $event */

            $startMonth = date('Y-m', $event->startDate);
            $months[$startMonth] = $startMonth;

            if( $event->endDate !== null )
            {
                $endMonth = date('Y-m', $event->endDate);
                $months[$endMonth] = $endMonth;
            }
        }

        ksort($months);

        return $months;
    }
}
