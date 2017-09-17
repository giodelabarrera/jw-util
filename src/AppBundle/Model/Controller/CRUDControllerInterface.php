<?php

namespace AppBundle\Model\Controller;

use AppBundle\Model\Manager\Manager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
interface CRUDControllerInterface
{
    /**
     * getManager
     *
     * @return Manager
     */
    public function getManager();

    /**
     * indexAction
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request);

    /**
     * createFilterForm
     *
     * @param array $oldData
     *
     * @return Form
     */
    public function createFilterForm(array $oldData);

    /**
     * newAction
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request);

    /**
     * showAction
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function showAction(Request $request, $id);

    /**
     * editAction
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id);

    /**
     * deleteAction
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, $id);
}
