<?php

namespace Jopawu\ContaoCultureEventsBundle\Translation;

use Contao\System;

/**
 * @author      Björn Heyser <info@bjoernheyser.de>
 */
class Translator
{
    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @return Translator
     */
    public function __construct()
    {
        System::loadLanguageFile('tl_culture_events');
        $this->translations = $GLOBALS['TL_LANG']['tl_culture_events'];
    }

    /**
     * @param string $langvar
     * @param array $parameters
     * @return string
     */
    public function get(string $langvar, array $parameters = []): string
    {
        if( !isset($this->translations[$langvar]) )
        {
            return "-{$langvar}-";
        }

        if( !count($parameters) )
        {
            return $this->translations[$langvar];
        }

        return vsprintf($this->translations[$langvar], $parameters);
    }

    /**
     * @return Translator
     */
    public static function getInstance(): Translator
    {
        return new self();
    }
}
