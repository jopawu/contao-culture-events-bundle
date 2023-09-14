<?php

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
use Jopawu\ContaoCultureEventsBundle\Model\CultureEventsModel;
use Jopawu\ContaoCultureEventsBundle\Parameter\YearMonthParameterString;
use Jopawu\ContaoCultureEventsBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;


#[AsFrontendModule(category: 'culture_events_module', template: 'mod_culture_events_trips')]
class CultureEventsTripsController extends AbstractFrontendModuleController
{
    public const TYPE = 'culture_events_trips';

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

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
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

        $template->listingTitle = $this->getPageModel()->title;

        $events = CultureEventsModel::findPublishedTrips();
        $items = [];

        foreach ($events as $event)
        {
            $items[] = $event;
        }

        $template->listingItems = $items;

        return $template->getResponse();
    }
}
