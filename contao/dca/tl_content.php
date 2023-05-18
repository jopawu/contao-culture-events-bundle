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

use Jopawu\ContaoCultureEventsBundle\Controller\ContentElement\CultureEventPresentationController;

/**
 * Content elements
 */
$GLOBALS['TL_DCA']['tl_content']['palettes'][CultureEventPresentationController::TYPE] = '{type_legend},type,headline;{text_legend},text;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
