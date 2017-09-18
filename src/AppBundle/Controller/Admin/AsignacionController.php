<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Asignacion;
use AppBundle\Form\Type\DatePickerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Asignacion controller.
 *
 * @Route("admin/asignacion")
 */
class AsignacionController extends Controller
{
    /**
     * Lists all asignacion entities.
     *
     * @Route("/", name="admin_asignacion_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filterBuilder = $em->getRepository('AppBundle:Asignacion')
            ->createQueryBuilder('a');

        $filterForm = $this->createFilterForm();

        if ($request->query->has($filterForm->getName())) {
            // manually bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            // id
            if ($filterForm->getData()['id']) {
                $filterBuilder
                    ->andWhere('a.id = :id')
                    ->setParameter('id', $filterForm->getData()['id']);
            }
            // created
            if ($filterForm->getData()['created']) {
                $filterBuilder
                    ->andWhere('a.created like :created')
                    ->setParameter('created', '%'.$filterForm->getData()['created']->format('Y-m-d').'%');
            }
            // updated
            if ($filterForm->getData()['updated']) {
                $filterBuilder
                    ->andWhere('a.updated like :updated')
                    ->setParameter('updated', '%'.$filterForm->getData()['updated']->format('Y-m-d').'%');
            }
        }

        $query = $filterBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->container->getParameter('knp_paginator.limit_page')
        );

        return $this->render('admin/asignacion/index.html.twig', array(
            'pagination'  => $pagination,
            'filter_form'  => $filterForm->createView(),
        ));
    }

    /**
     * Creates a form to filter the Asignacion list
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createFilterForm()
    {
        $formName = 'filter';
        $defaultData = [];
        $options = [
            'action' => $this->generateUrl('admin_asignacion_index'),
            'method' => 'GET',
            'csrf_protection'   => false,
            'validation_groups' => ['filtering'],
            'required' => false,
        ];

        $formBuilder = $this->get('form.factory')
            ->createNamedBuilder($formName, FormType::class, $defaultData, $options)
            ->add('id', null, [
                'label' => 'ID'
            ])
        ;
        return $formBuilder
            ->add('id', NumberType::class)
            ->add('created', DatePickerType::class, [])
            ->add('updated', DatePickerType::class, [])
            ->getForm()
            ;
    }

    /**
     * Creates a new asignacion entity.
     *
     * @Route("/new", name="admin_asignacion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $asignacion = new Asignacion();
        $form = $this->createForm('AppBundle\Form\AsignacionType', $asignacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($asignacion);
            $em->flush();

            $this->addFlash('success', 'The asignacion has been created correctly');

            return $this->redirectTo($request, $asignacion);
        }

        return $this->render('admin/asignacion/new.html.twig', array(
            'asignacion' => $asignacion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a asignacion entity.
     *
     * @Route("/{id}", name="admin_asignacion_show")
     * @Method("GET")
     */
    public function showAction(Asignacion $asignacion)
    {
        $deleteForm = $this->createDeleteForm($asignacion);

        return $this->render('admin/asignacion/show.html.twig', array(
            'asignacion' => $asignacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing asignacion entity.
     *
     * @Route("/{id}/edit", name="admin_asignacion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Asignacion $asignacion)
    {
        $deleteForm = $this->createDeleteForm($asignacion);
        $editForm = $this->createForm('AppBundle\Form\AsignacionType', $asignacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The asignacion has been modified correctly');

            return $this->redirectTo($request, $asignacion);
        }

        return $this->render('admin/asignacion/edit.html.twig', array(
            'asignacion' => $asignacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a asignacion entity.
     *
     * @Route("/{id}/delete", name="admin_asignacion_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Asignacion $asignacion)
    {
        $form = $this->createDeleteForm($asignacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($asignacion);
            $em->flush();

            $this->addFlash('success', 'The asignacion has been removed correctly');

            return $this->redirectTo($request, $asignacion);
        }

        return $this->render('admin/asignacion/delete.html.twig', array(
            'asignacion' => $asignacion,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a asignacion entity.
     *
     * @param Asignacion $asignacion The asignacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Asignacion $asignacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_asignacion_delete', array('id' => $asignacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function redirectTo(Request $request, $entity = null)
    {
        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->generateUrl('admin_asignacion_index');
        }

        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->generateUrl('admin_asignacion_index');
        }

        if (null !== $request->get('btn_delete')) {
            $url = $this->generateUrl('admin_asignacion_index');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $url = $this->generateUrl('admin_asignacion_new');
        }

        if (!$url) {
            $url = $this->generateUrl('admin_asignacion_edit', ['id' => $entity->getId()]);
        }

        return new RedirectResponse($url);
    }
}
