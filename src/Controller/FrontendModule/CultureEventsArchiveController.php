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

namespace Jopawu\ContaoCultureEventsBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Jopawu\ContaoCultureEventsBundle\Model\CultureEventsModel;
use Jopawu\ContaoCultureEventsBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsFrontendModule(category: 'culture_events_module', template: 'mod_culture_events_archive')]
class CultureEventsArchiveController extends AbstractFrontendModuleController
{
    public const TYPE = 'culture_events_archive';

    protected ?PageModel $page;

    /**
     * This method extends the parent __invoke method,
     * its usage is usually not necessary.
     */
    public function __invoke(Request $request, ModuleModel $model, string $section, array $classes = null, PageModel $page = null): Response
    {
        // Get the page model
        $this->page = $page;

        $scopeMatcher = $this->container->get('contao.routing.scope_matcher');

        if ($this->page instanceof PageModel && $scopeMatcher->isFrontendRequest($request)) {
            $this->page->loadDetails();
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    /**
     * Lazyload services.
     */
    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();

        $services['contao.framework'] = ContaoFramework::class;
        $services['database_connection'] = Connection::class;
        $services['contao.routing.scope_matcher'] = ScopeMatcher::class;
        $services['security.helper'] = Security::class;
        $services['translator'] = TranslatorInterface::class;

        return $services;
    }

    /**
     * @param Template $template
     * @param ModuleModel $model
     * @param Request $request
     * @return Response
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        return $this->buildEventArchiveResponse($template, $model, $request);
    }

    /**
     * @param Template $template
     * @param ModuleModel $model
     * @param Request $request
     * @return Response
     */
    protected function buildEventArchiveResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        /* @var Translator $translator */
        $mylng = Translator::getInstance();

        $template->archiveTitle = $this->getPageModel()->title;

        $events = CultureEventsModel::findArchived();

        if( $events === null )
        {
            $template->archiveNoItems = $mylng->get('cultureEventsArchiveNotItemsContent');
            return $template->getResponse();
        }

        $years = [];

        foreach($events as $event)
        {
            $year = date('Y', $event->startDate);

            if( !isset($years[$year]) )
            {
                $years[$year] = [];
            }

            $years[$year][] = $event;
        }

        foreach($years as $year => $events)
        {
            usort($events, [self::class, 'eventSortCallBack']);
            $years[$year] = $events; // !!!
        }

        krsort($years);

        $template->archiveItems = $years;

        return $template->getResponse();
    }

    /**
     * @param CultureEventsModel $eventA
     * @param CultureEventsModel $eventB
     * @return int
     */
    public static function eventSortCallBack(CultureEventsModel $eventA, CultureEventsModel $eventB) : int
    {
        $startA = $eventA->startTime ? $eventA->startDate + $eventA->startTime : $eventA->startDate;
        $startB = $eventB->startTime ? $eventB->startDate + $eventB->startTime : $eventB->startDate;

        if($startA > $startB)
        {
            return -1;
        }

        if($startA < $startB)
        {
            return 1;
        }

        return 0;
    }
}
