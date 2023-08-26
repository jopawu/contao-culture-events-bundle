<?php

namespace Jopawu\ContaoCultureEventsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

/**
 * @author      Björn Heyser <info@bjoernheyser.de>
 */
#[AsHook('printArticleAsPdf')]
class AfterNavigationItemPreparedListener
{
    /**
     * @param array $navigationItem
     * @return array
     */
    public function __invoke(array $navigationItem) : array
    {
        return $navigationItem;
    }
}