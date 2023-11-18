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
use Contao\Date;
use Contao\FrontendUser;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\System;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Jopawu\ContaoCultureEventsBundle\Parameter\YearMonthParameterDetermination;
use Jopawu\ContaoCultureEventsBundle\Parameter\YearMonthParameterString;
use Jopawu\ContaoCultureEventsBundle\Model\CultureEventsModel;
use Jopawu\ContaoCultureEventsBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsFrontendModule(category: 'culture_events_module', template: 'mod_culture_events_listing')]
class CultureEventsListingController extends AbstractFrontendModuleController
{
    public const TYPE = 'culture_events_listing';

    /**
     * @var PageModel|null
     */
    protected ?PageModel $page;

    /**
     * @var array
     */
    protected $publishingMonths = [];

    /**
     * @var string|null
     */
    protected $monthParameter = null;

    /**
     * @param Request $request
     * @param ModuleModel $model
     * @param string $section
     * @param array|null $classes
     * @param PageModel|null $page
     * @return Response
     */
    public function __invoke(Request $request, ModuleModel $model, string $section, array $classes = null, PageModel $page = null): Response
    {
        $this->page = $page;

        $scopeMatcher = $this->container->get('contao.routing.scope_matcher');

        if ($this->page instanceof PageModel && $scopeMatcher->isFrontendRequest($request))
        {
            $this->page->loadDetails();

            $this->publishingMonths = CultureEventsModel::findPublishingMonths();
            $this->monthParameter = YearMonthParameterDetermination::getParameter($this->publishingMonths);

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
        if($this->monthParameter === null)
        {
            /* @var Translator $translator */
            $mylng = Translator::getInstance();

            $template->listingTitle = $this->getPageModel()->title;
            $template->listingNotItems = $mylng->get('cultureEventsListingNotItemsContent');

            return $template->getResponse();
        }

        return $this->buildEventListingResponse($template, $model, $request);
    }

    /**
     * @param Template $template
     * @param ModuleModel $model
     * @param Request $request
     * @return Response
     */
    protected function buildEventListingResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        /* @var Translator $translator */
        $translator = System::getContainer()->get('translator');

        /* @var Translator $translator */
        $mylng = Translator::getInstance();

        [$year, $month] = YearMonthParameterString::split($this->monthParameter);

        $titleMonth = $translator->trans('MONTHS.'.(int)($month-1), [], 'contao_default');
        $template->listingTitle = $mylng->get('cultureEventsListingHeader', [$titleMonth, $year]);

        $events = CultureEventsModel::findYearMonthPublished($year, $month);
        $items = [];

        foreach($events as $event)
        {
            $event->startWeekday = $translator->trans(
                'DAYS.'.date('w', $event->startDate),
                [],
                'contao_default'
            );

            $event->startDate = date('d.m.Y', $event->startDate);

            if( $event->startTime )
            {
                $event->startTime = date('H:i', $event->startTime);
            }

            if( $event->endDate )
            {
                $event->endWeekday = $translator->trans(
                    'DAYS.'.date('w', $event->endDate),
                    [],
                    'contao_default'
                );

                $event->endDate = date('d.m.Y', $event->endDate);

                $event->endSeparator = $mylng->get('cultureEventsStartEndSeparator');
            }

            if( $event->endTime )
            {
                $event->endTime = date('H:i', $event->endTime);
            }

            $items[] = $event;
        }

        $template->listingItems = $items;

        return $template->getResponse();
    }

    // -----------------------------------------------------------------------------------------------------------------

    protected function getResponseExample(Template $template, ModuleModel $model, Request $request): Response
    {
        $userFirstname = 'DUDEi';
        $user = $this->container->get('security.helper')->getUser();

        // Get the logged in frontend user... if there is one
        if ($user instanceof FrontendUser) {
            $userFirstname = $user->firstname;
        }

        /** @var Session $session */
        $session = $request->getSession();
        $bag = $session->getBag('contao_frontend');
        $bag->set('foo', 'bar');

        /** @var Date $dateAdapter */
        $dateAdapter = $this->container->get('contao.framework')->getAdapter(Date::class);

        $intWeekday = $dateAdapter->parse('w');
        $translator = $this->container->get('translator');
        $strWeekday = $translator->trans('DAYS.'.$intWeekday, [], 'contao_default');

        $arrGuests = [];

        // Get the database connection
        $db = $this->container->get('database_connection');

        /** @var Result $stmt */
        $stmt = $db->executeQuery('SELECT * FROM tl_member WHERE gender = ? ORDER BY lastname', ['female']);

        while (false !== ($row = $stmt->fetchAssociative())) {
            $arrGuests[] = $row['firstname'];
        }

        $template->helloTitle = sprintf(
            'Hi %s, and welcome to the "Hello World Module". Today is %s.',
            $userFirstname,
            $strWeekday,
        );

        $template->helloText = '';

        if (!empty($arrGuests)) {
            $template->helloText = 'Our guests today are: '.implode(', ', $arrGuests);
        }

        return $template->getResponse();
    }
}
