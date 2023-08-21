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
$GLOBALS['TL_LANG']['MOD']['culture_events_module'] = 'Culture Events';
$GLOBALS['TL_LANG']['MOD']['culture_events_collection'] = ['Manage', 'Management of culture events.'];

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['culture_events_module'] = 'Culture Events';
$GLOBALS['TL_LANG']['FMD'][CultureEventsListingController::TYPE] = ['Published Events Listing', 'Lists all published culture events.'];

