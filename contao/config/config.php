<?php

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
use Jopawu\ContaoCultureEventsBundle\Model\CultureEventsModel;

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['culture_events_module'] = [
    CultureEventsListingController::TYPE => CultureEventsListingController::class,
    CultureEventsTripsController::TYPE => CultureEventsTripsController::class,
    CultureEventsArchiveController::TYPE => CultureEventsArchiveController::class
];

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['culture_events_module']['culture_events_collection'] = array(
    'tables' => array('tl_culture_events')
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_culture_events'] = CultureEventsModel::class;

/**
 * CSS Files
 */
$GLOBALS['TL_CSS'][] = 'bundles/jopawucontaocultureevents/css/styles.css';
