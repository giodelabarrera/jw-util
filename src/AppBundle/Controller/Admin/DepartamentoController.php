<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Departamento;
use AppBundle\Form\Type\DatePickerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Departamento controller.
 *
 * @Route("admin/departamento")
 */
class DepartamentoController extends Controller
{
    /**
     * Lists all departamento entities.
     *
     * @Route("/", name="admin_departamento_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filterBuilder = $em->getRepository('AppBundle:Departamento')
            ->createQueryBuilder('d');

        $filterForm = $this->createFilterForm();

        if ($request->query->has($filterForm->getName())) {
            // manually bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            // id
            if ($filterForm->getData()['id']) {
                $filterBuilder
                    ->andWhere('d.id = :id')
                    ->setParameter('id', $filterForm->getData()['id']);
            }
            // created
            if ($filterForm->getData()['created']) {
                $filterBuilder
                    ->andWhere('d.created like :created')
                    ->setParameter('created', '%'.$filterForm->getData()['created']->format('Y-m-d').'%');
            }
            // updated
            if ($filterForm->getData()['updated']) {
                $filterBuilder
                    ->andWhere('d.updated like :updated')
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

        return $this->render('admin/departamento/index.html.twig', array(
            'pagination'  => $pagination,
            'filter_form'  => $filterForm->createView(),
        ));
    }

    /**
     * Creates a form to filter the Departamento list
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createFilterForm()
    {
        $formName = 'filter';
        $defaultData = [];
        $options = [
            'action' => $this->generateUrl('admin_departamento_index'),
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
     * Creates a new departamento entity.
     *
     * @Route("/new", name="admin_departamento_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $departamento = new Departamento();
        $form = $this->createForm('AppBundle\Form\DepartamentoType', $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($departamento);
            $em->flush();

            $this->addFlash('success', 'The departamento has been created correctly');

            return $this->redirectTo($request, $departamento);
        }

        return $this->render('admin/departamento/new.html.twig', array(
            'departamento' => $departamento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a departamento entity.
     *
     * @Route("/{id}", name="admin_departamento_show")
     * @Method("GET")
     */
    public function showAction(Departamento $departamento)
    {
        $deleteForm = $this->createDeleteForm($departamento);

        return $this->render('admin/departamento/show.html.twig', array(
            'departamento' => $departamento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing departamento entity.
     *
     * @Route("/{id}/edit", name="admin_departamento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Departamento $departamento)
    {
        $deleteForm = $this->createDeleteForm($departamento);
        $editForm = $this->createForm('AppBundle\Form\DepartamentoType', $departamento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The departamento has been modified correctly');

            return $this->redirectTo($request, $departamento);
        }

        return $this->render('admin/departamento/edit.html.twig', array(
            'departamento' => $departamento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a departamento entity.
     *
     * @Route("/{id}/delete", name="admin_departamento_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Departamento $departamento)
    {
        $form = $this->createDeleteForm($departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($departamento);
            $em->flush();

            $this->addFlash('success', 'The departamento has been removed correctly');

            return $this->redirectTo($request, $departamento);
        }

        return $this->render('admin/departamento/delete.html.twig', array(
            'departamento' => $departamento,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a departamento entity.
     *
     * @param Departamento $departamento The departamento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Departamento $departamento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_departamento_delete', array('id' => $departamento->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function redirectTo(Request $request, $entity = null)
    {
        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->generateUrl('admin_departamento_index');
        }

        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->generateUrl('admin_departamento_index');
        }

        if (null !== $request->get('btn_delete')) {
            $url = $this->generateUrl('admin_departamento_index');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $url = $this->generateUrl('admin_departamento_new');
        }

        if (!$url) {
            $url = $this->generateUrl('admin_departamento_edit', ['id' => $entity->getId()]);
        }

        return new RedirectResponse($url);
    }
}
