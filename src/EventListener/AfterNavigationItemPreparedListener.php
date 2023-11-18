<?php

namespace Jopawu\ContaoCultureEventsBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Translation\Translator;
use Contao\Database;
use Contao\Date;
use Contao\FrontendTemplate;
use Contao\System;
use Doctrine\DBAL\Connection;
use Jopawu\ContaoCultureEventsBundle\Contao\RelevantLayouts;
use Jopawu\ContaoCultureEventsBundle\Controller\FrontendModule\CultureEventsListingController;
use Jopawu\ContaoCultureEventsBundle\Parameter\YearMonthParameterDetermination;
use Jopawu\ContaoCultureEventsBundle\Model\CultureEventsModel;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author      Björn Heyser <info@bjoernheyser.de>
 */
#[AsHook('afterNavigationItemPrepared')]
class AfterNavigationItemPreparedListener
{
    /**
     * @var array|null
     */
    protected $relevantLayouts = null;

    /**
     * @var array
     */
    protected $publishingMonths = [];

    /**
     * @var string|null
     */
    protected $monthParameter = null;

    /**
     * @return AfterNavigationItemPreparedListener
     */
    public function __construct()
    {
        $this->ensureRelevantLayoutsDetermined();

        $this->publishingMonths = CultureEventsModel::findPublishingMonths();

        $this->monthParameter = YearMonthParameterDetermination::getParameter(
            $this->publishingMonths
        );
    }

    /**
     * @param FrontendTemplate $tpl
     * @param TranslatorInterface $lng
     * @param array $navigationItem
     * @return array
     */
    public function __invoke(FrontendTemplate $tpl, array $navigationItem) : array
    {
        if( !$this->relevantLayouts->isRelevant($navigationItem['layout']) )
        {
            return $navigationItem;
        }

        if( !$navigationItem['isActive'] )
        {
            return $navigationItem;
        }

        if( !count($this->publishingMonths) )
        {
            return $navigationItem;
        }

        $navigationItem['isActive'] = false;

        $navigationItem['class'] = str_replace(
            'active', '', $navigationItem['class']
        );

        return $this->addFilteringSubnavigation($tpl, $navigationItem);
    }

    /**
     * @param array $navigationItem
     * @param array $publishingMonths
     * @return array
     */
    protected function addFilteringSubnavigation(FrontendTemplate $tpl, array $navigationItem): array
    {
        /* @var TranslatorInterface $lng */
        $lng = System::getContainer()->get('translator');

        $items = [];

        foreach($this->publishingMonths as $pubMonth)
        {
            [$year, $month] = explode('-', $pubMonth);

            $items[] = [
                'title' => $navigationItem['title'],
                'pageTitle' => $navigationItem['pageTitle'],
                'description' => $navigationItem['description'],
                'nofollow' => $navigationItem['nofollow'],

                'link' => $lng->trans('MONTHS.' . (int)($month-1), [], 'contao_default') . " {$year}",
                'href' => $navigationItem['href'] . "?month={$pubMonth}",
                'target' => '',

                'class' => $pubMonth == $this->monthParameter ? 'active' : 'sibling',
                'isActive' => $pubMonth == $this->monthParameter,
                'isTrail' => true
            ];
        }

        $tpl->items = $items;

        $navigationItem['subitems'] = $tpl->parse() . $navigationItem['subitems'];

        return $navigationItem;
    }

    protected function ensureRelevantLayoutsDetermined()
    {
        if( $this->relevantLayouts === null )
        {
            $this->relevantLayouts = new RelevantLayouts();
        }
    }
}
