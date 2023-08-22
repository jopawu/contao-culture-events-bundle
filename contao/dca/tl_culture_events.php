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

use Contao\Backend;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Input;

/**
 * Table tl_culture_events
 */

// Database Table Configuration

$GLOBALS['TL_DCA']['tl_culture_events']['config'] = [

    'dataContainer'    => DC_Table::class,
    'enableVersioning' => true,

    'sql' => [
        'keys' => ['id' => 'primary']
    ]

];

// Database Fields Configuration

$GLOBALS['TL_DCA']['tl_culture_events']['fields'] = [

    'id'        => ['sql' => "int(10) unsigned NOT NULL auto_increment"],
    'tstamp'    => ['sql' => "int(10) unsigned NOT NULL default '0'"],

    'scheduleMode' => [

        'sql'       => "varchar(255) NOT NULL default ''",
        'inputType' => 'select',
        'reference' => &$GLOBALS['TL_LANG']['tl_culture_events'],

        'options' => [
            'singleDay',
            'multiDay',
            'dateTimes'
        ],

        'eval' => [
            'submitOnChange' => true,
            'mandatory' => true,
            'includeBlankOption' => true,
            'tl_class' => 'w50'
        ]

    ],

    'startDate' => [

        'sql'       => "int(10) unsigned NULL",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_DAY_DESC,

        'eval'      => [
            'mandatory' => true,
            'datepicker' => true,
            'rgxp' => 'date',
            'maxlength' => 10,
            'tl_class' => 'w50'
        ]

    ],

    'startTime' => [

        'sql'       => "int(10) NULL",
        'inputType' => 'text',

        'eval'      => [
            'mandatory' => true,
            'rgxp' => 'time',
            'maxlength' => 5,
            'tl_class' => 'w50'
        ]

    ],

    'endDate' => [

        'sql'       => "int(10) unsigned NULL",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_DAY_DESC,

        'eval'      => [
            'mandatory' => true,
            'datepicker' => true,
            'rgxp' => 'date',
            'maxlength' => 10,
            'tl_class' => 'w50'
        ]

    ],

    'endTime' => [

        'sql'       => "int(10) NULL",
        'inputType' => 'text',

        'eval'      => [
            'mandatory' => true,
            'rgxp' => 'time',
            'maxlength' => 5,
            'tl_class' => 'w50'
        ]

    ],

    'title' => [

        'sql'       => "varchar(255) NOT NULL default ''",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_INITIAL_LETTERS_ASC,

        'eval'      => [
            'mandatory' => true,
            'maxlength' => 255,
            'tl_class' => 'w100'
        ]

    ],

    'subtitle' => [

        'sql'       => "varchar(255) NOT NULL default ''",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_INITIAL_LETTERS_ASC,

        'eval'      => [
            'maxlength' => 255,
            'tl_class' => 'w100'
        ]

    ],

    'description' => [

        'sql'       => 'text NULL',
        'inputType' => 'textarea',
        'search'    => true,

        'eval'      => [
            'rte' => 'tinyMCE',
            'tl_class' => 'clr'
        ]

    ],

    'summary' => [

        'sql'       => "varchar(255) NOT NULL default ''",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_INITIAL_LETTERS_ASC,

        'eval'      => [
            'maxlength' => 255,
            'tl_class' => 'w100'
        ]

    ],

    'publishingDate' => [

        'sql'       => "int(10) unsigned NULL",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_DAY_DESC,

        'eval'      => [
            'mandatory' => true,
            'datepicker' => true,
            'rgxp' => 'date',
            'maxlength' => 10,
            'tl_class' => 'w50'
        ]

    ],

    'archiveDate' => [

        'sql'       => "int(10) unsigned NULL",
        'inputType' => 'text',
        'search'    => true,
        'filter'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_DAY_DESC,

        'eval'      => [
            'mandatory' => true,
            'datepicker' => true,
            'rgxp' => 'date',
            'maxlength' => 10,
            'tl_class' => 'w50'
        ]

    ]

];

// Field (Sub-)Palettes Configuration

$GLOBALS['TL_DCA']['tl_culture_events']['palettes'] = [

    '__selector__' => ['scheduleMode'],
    'default'      => '{scheduleLegend},scheduleMode;{contentLegend},title,subtitle,description,summary;{presentationLegend},publishingDate,archiveDate'

];

$GLOBALS['TL_DCA']['tl_culture_events']['subpalettes'] = [

    'scheduleMode_singleDay' => 'startDate',
    'scheduleMode_multiDay' => 'startDate,endDate',
    'scheduleMode_dateTimes' => 'startDate,startTime,endDate,endTime'

];

// Backend Listing Configuration

$GLOBALS['TL_DCA']['tl_culture_events']['list'] = [
    'sorting'           => [
        'mode'        => DataContainer::MODE_SORTABLE,
        'fields'      => ['startDate'],
        'flag'        => DataContainer::SORT_DAY_DESC,
        'panelLayout' => 'filter;sort,search,limit'
    ],
    'label'             => [
        'fields' => ['title'],
        'format' => '%s',
    ],
    'global_operations' => [
        'all' => [
            'href'       => 'act=select',
            'class'      => 'header_edit_all',
            'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
        ]
    ],
    'operations'        => [
        'edit'   => [
            'href'  => 'act=edit',
            'icon'  => 'edit.svg'
        ],
        'copy'   => [
            'href'  => 'act=copy',
            'icon'  => 'copy.svg'
        ],
        'delete' => [
            'href'       => 'act=delete',
            'icon'       => 'delete.svg',
            'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
        ],
        'show'   => [
            'href'       => 'act=show',
            'icon'       => 'show.svg',
            'attributes' => 'style="margin-right:3px"'
        ],
    ]
];
