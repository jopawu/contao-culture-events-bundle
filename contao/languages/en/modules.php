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

/**
 * Backend modules
 */
$GLOBALS['TL_LANG']['MOD']['culture_events_module'] = 'Culture Event Contents';
$GLOBALS['TL_LANG']['MOD']['culture_events_collection'] = ['Manage Events', 'Provides a manager to maintain events.'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['culture_events_module'] = 'Culture Event Contents';
$GLOBALS['TL_LANG']['FMD'][CultureEventsListingController::TYPE] = ['Events Listing', 'Lists a collection of events.'];

