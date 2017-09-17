<?php
/**
 * Created by PhpStorm.
 * User: giorgio
 * Date: 14/9/17
 * Time: 16:19
 */

namespace AppBundle\Util;


/**
 * Class Util
 * @package AppBundle\Util
 */
class Util
{
    /**
     * Get all Ids of the entities
     *
     * @param $entities
     * @return array
     */
    static public function getIdsOfEntities($entities)
    {
        $ids = [];
        foreach ($entities as $entity) {
            $ids[] = $entity->getId();
        }
        return $ids;
    }
}