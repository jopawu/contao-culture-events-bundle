<?php

namespace Jopawu\ContaoCultureEventsBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Database;
use Contao\DataContainer;

/**
 * @author      Björn Heyser <info@bjoernheyser.de>
 */
#[AsCallback(table: 'tl_culture_events', target: 'config.onsubmit')]
class CultureEventsAfterUpdateCallback
{
    public function __invoke(DataContainer $dc)
    {
        $db = Database::getInstance();

        $query = "
            UPDATE tl_culture_events
            SET eventYear = YEAR( FROM_UNIXTIME(startDate) )
            WHERE id = ?
        ";

        $result = $db->prepare($query)->execute($dc->id);
    }
}
