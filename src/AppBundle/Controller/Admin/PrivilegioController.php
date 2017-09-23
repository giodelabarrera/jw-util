<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Privilegio;
use AppBundle\Form\Type\DatePickerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Privilegio controller.
 *
 * @Route("admin/privilegio")
 */
class PrivilegioController extends Controller
{
    /**
     * Lists all privilegio entities.
     *
     * @Route("/", name="admin_privilegio_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filterBuilder = $em->getRepository('AppBundle:Privilegio')
            ->createQueryBuilder('p');

        $filterForm = $this->createFilterForm();

        if ($request->query->has($filterForm->getName())) {
            // manually bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            // id
            if ($filterForm->getData()['id']) {
                $filterBuilder
                    ->andWhere('p.id = :id')
                    ->setParameter('id', $filterForm->getData()['id']);
            }
            // created
            if ($filterForm->getData()['created']) {
                $filterBuilder
                    ->andWhere('p.created like :created')
                    ->setParameter('created', '%'.$filterForm->getData()['created']->format('Y-m-d').'%');
            }
            // updated
            if ($filterForm->getData()['updated']) {
                $filterBuilder
                    ->andWhere('p.updated like :updated')
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

        return $this->render('admin/privilegio/index.html.twig', array(
            'pagination'  => $pagination,
            'filter_form'  => $filterForm->createView(),
        ));
    }

    /**
     * Creates a form to filter the Privilegio list
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createFilterForm()
    {
        $formName = 'filter';
        $defaultData = [];
        $options = [
            'action' => $this->generateUrl('admin_privilegio_index'),
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
     * Creates a new privilegio entity.
     *
     * @Route("/new", name="admin_privilegio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $privilegio = new Privilegio();
        $form = $this->createForm('AppBundle\Form\PrivilegioType', $privilegio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($privilegio);
            $em->flush();

            $this->addFlash('success', 'The privilegio has been created correctly');

            return $this->redirectTo($request, $privilegio);
        }

        return $this->render('admin/privilegio/new.html.twig', array(
            'privilegio' => $privilegio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a privilegio entity.
     *
     * @Route("/{id}", name="admin_privilegio_show")
     * @Method("GET")
     */
    public function showAction(Privilegio $privilegio)
    {
        $deleteForm = $this->createDeleteForm($privilegio);

        return $this->render('admin/privilegio/show.html.twig', array(
            'privilegio' => $privilegio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing privilegio entity.
     *
     * @Route("/{id}/edit", name="admin_privilegio_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Privilegio $privilegio)
    {
        $deleteForm = $this->createDeleteForm($privilegio);
        $editForm = $this->createForm('AppBundle\Form\PrivilegioType', $privilegio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The privilegio has been modified correctly');

            return $this->redirectTo($request, $privilegio);
        }

        return $this->render('admin/privilegio/edit.html.twig', array(
            'privilegio' => $privilegio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a privilegio entity.
     *
     * @Route("/{id}/delete", name="admin_privilegio_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Privilegio $privilegio)
    {
        $form = $this->createDeleteForm($privilegio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($privilegio);
            $em->flush();

            $this->addFlash('success', 'The privilegio has been removed correctly');

            return $this->redirectTo($request, $privilegio);
        }

        return $this->render('admin/privilegio/delete.html.twig', array(
            'privilegio' => $privilegio,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a privilegio entity.
     *
     * @param Privilegio $privilegio The privilegio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Privilegio $privilegio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_privilegio_delete', array('id' => $privilegio->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function redirectTo(Request $request, $entity = null)
    {
        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->generateUrl('admin_privilegio_index');
        }

        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->generateUrl('admin_privilegio_index');
        }

        if (null !== $request->get('btn_delete')) {
            $url = $this->generateUrl('admin_privilegio_index');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $url = $this->generateUrl('admin_privilegio_new');
        }

        if (!$url) {
            $url = $this->generateUrl('admin_privilegio_edit', ['id' => $entity->getId()]);
        }

        return new RedirectResponse($url);
    }
}
