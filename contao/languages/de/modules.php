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

use Jopawu\ContaoCultureEventsBundle\Controller\FrontendModule\CultureEventsListingController;
use Jopawu\ContaoCultureEventsBundle\Controller\FrontendModule\CultureEventsTripsController;
use Jopawu\ContaoCultureEventsBundle\Controller\FrontendModule\CultureEventsArchiveController;

/**
 * Backend modules
 */
$GLOBALS['TL_LANG']['MOD']['culture_events_module'] = 'Kulturveranstaltungen';
$GLOBALS['TL_LANG']['MOD']['culture_events_collection'] = ['Verwalten', 'Verwaltung der Kulturveranstaltungen.'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['culture_events_module'] = 'Kulturveranstaltungen';
$GLOBALS['TL_LANG']['FMD'][CultureEventsListingController::TYPE] = ['Veröffentlichte Kulturveranstaltungen', 'Auflistung aller veröffentlichten Kulturveranstaltungen.'];
$GLOBALS['TL_LANG']['FMD'][CultureEventsTripsController::TYPE] = ['Reise-Kulturveranstaltungen', 'Auflistung aller Reise-Kulturveranstaltungen.'];
$GLOBALS['TL_LANG']['FMD'][CultureEventsArchiveController::TYPE] = ['Archivierte Kulturveranstaltungen', 'Auflistung aller archivierten Kulturveranstaltungen.'];

