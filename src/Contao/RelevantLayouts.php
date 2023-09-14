<?php

namespace Jopawu\ContaoCultureEventsBundle\Contao;

use Contao\Database;

/**
 * @author      Björn Heyser <info@bjoernheyser.de>
 */
class RelevantLayouts
{
    /**
     * @var array
     */
    protected $layouts = [];

    /**
     * @return RelevantLayouts
     */
    public function __construct()
    {
        $moduleIds = $this->getListingFrontendModuleIds();
        $this->layouts = $this->getRelevantLayoutIds($moduleIds);
    }

    /**
     * @return array
     */
    protected function getListingFrontendModuleIds()
    {
        $db = Database::getInstance();

        $query = "SELECT id FROM tl_module WHERE type = ?";
        $result = $db->prepare($query)->execute('culture_events_listing');

        $moduleIds = [];
        while( $row = $result->fetchAssoc() )
        {
            $moduleIds[] = $row['id'];
        }

        return $moduleIds;
    }

    /**
     * @param array $relevantModuleIds
     * @return array
     */
    protected function getRelevantLayoutIds(array $relevantModuleIds) : array
    {
        $db = Database::getInstance();

        $query = "SELECT id, modules FROM tl_layout";
        $result = $db->prepare($query)->execute();

        $layoutIds = [];
        while( $row = $result->fetchAssoc() )
        {
            $involvedModuleIds = $this->fetchLayoutsEnabledModuleIds(
                unserialize($row['modules'])
            );

            $moduleIntersection = array_intersect(
                $involvedModuleIds, $relevantModuleIds
            );

            if( !count($moduleIntersection) )
            {
                continue;
            }

            $layoutIds[] = $row['id'];
        }

        return $layoutIds;
   }

    /**
     * @param array $layoutModules
     * @return array
     */
   protected function fetchLayoutsEnabledModuleIds(array $layoutModules): array
   {
       $moduleIds = [];

       foreach($layoutModules as $module)
       {
           if( !(bool)$module['enable'] )
           {
               continue;
           }

           $moduleIds[] = (int)$module['mod'];
       }

       return $moduleIds;
   }

    /**
     * @param int $layoutId
     * @return bool
     */
    public function isRelevant(int $layoutId): bool
    {
        return in_array($layoutId, $this->layouts);
    }
}
