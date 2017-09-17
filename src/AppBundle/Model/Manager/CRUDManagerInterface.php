<?php

namespace AppBundle\Model\Manager;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Form;

/**
 * Interface CRUDManagerInterface
 *
 * @package AppBundle\Manager\Model
 */
interface CRUDManagerInterface
{
    /**
     * getManager
     *
     * @return EntityRepository
     */
//    public function getRepository();

    /**
     * getFilterQueryBuilder
     *
     * @return QueryBuilder
     */
    public function getFilterQueryBuilder();

    /**
     * addFilterConditions
     *
     * @param Form         $filterForm
     * @param QueryBuilder $filterBuilder
     *
     * @return QueryBuilder
     */
    public function addFilterConditions(Form $filterForm, QueryBuilder $filterBuilder);
}
