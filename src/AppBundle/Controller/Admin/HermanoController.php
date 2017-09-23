<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Hermano;
use AppBundle\Form\Type\DatePickerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hermano controller.
 *
 * @Route("admin/hermano")
 */
class HermanoController extends Controller
{
    /**
     * Lists all hermano entities.
     *
     * @Route("/", name="admin_hermano_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filterBuilder = $em->getRepository('AppBundle:Hermano')
            ->createQueryBuilder('h');

        $filterForm = $this->createFilterForm();

        if ($request->query->has($filterForm->getName())) {
            // manually bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            // id
            if ($filterForm->getData()['id']) {
                $filterBuilder
                    ->andWhere('h.id = :id')
                    ->setParameter('id', $filterForm->getData()['id']);
            }
            // created
            if ($filterForm->getData()['created']) {
                $filterBuilder
                    ->andWhere('h.created like :created')
                    ->setParameter('created', '%'.$filterForm->getData()['created']->format('Y-m-d').'%');
            }
            // updated
            if ($filterForm->getData()['updated']) {
                $filterBuilder
                    ->andWhere('h.updated like :updated')
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

        return $this->render('admin/hermano/index.html.twig', array(
            'pagination'  => $pagination,
            'filter_form'  => $filterForm->createView(),
        ));
    }

    /**
     * Creates a form to filter the Hermano list
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createFilterForm()
    {
        $formName = 'filter';
        $defaultData = [];
        $options = [
            'action' => $this->generateUrl('admin_hermano_index'),
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
     * Creates a new hermano entity.
     *
     * @Route("/new", name="admin_hermano_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $hermano = new Hermano();
        $form = $this->createForm('AppBundle\Form\HermanoType', $hermano);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hermano);
            $em->flush();

            $this->addFlash('success', 'The hermano has been created correctly');

            return $this->redirectTo($request, $hermano);
        }

        return $this->render('admin/hermano/new.html.twig', array(
            'hermano' => $hermano,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a hermano entity.
     *
     * @Route("/{id}", name="admin_hermano_show")
     * @Method("GET")
     */
    public function showAction(Hermano $hermano)
    {
        $deleteForm = $this->createDeleteForm($hermano);

        return $this->render('admin/hermano/show.html.twig', array(
            'hermano' => $hermano,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing hermano entity.
     *
     * @Route("/{id}/edit", name="admin_hermano_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Hermano $hermano)
    {
        $deleteForm = $this->createDeleteForm($hermano);
        $editForm = $this->createForm('AppBundle\Form\HermanoType', $hermano);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The hermano has been modified correctly');

            return $this->redirectTo($request, $hermano);
        }

        return $this->render('admin/hermano/edit.html.twig', array(
            'hermano' => $hermano,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a hermano entity.
     *
     * @Route("/{id}/delete", name="admin_hermano_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Hermano $hermano)
    {
        $form = $this->createDeleteForm($hermano);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hermano);
            $em->flush();

            $this->addFlash('success', 'The hermano has been removed correctly');

            return $this->redirectTo($request, $hermano);
        }

        return $this->render('admin/hermano/delete.html.twig', array(
            'hermano' => $hermano,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a hermano entity.
     *
     * @param Hermano $hermano The hermano entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hermano $hermano)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_hermano_delete', array('id' => $hermano->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function redirectTo(Request $request, $entity = null)
    {
        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->generateUrl('admin_hermano_index');
        }

        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->generateUrl('admin_hermano_index');
        }

        if (null !== $request->get('btn_delete')) {
            $url = $this->generateUrl('admin_hermano_index');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $url = $this->generateUrl('admin_hermano_new');
        }

        if (!$url) {
            $url = $this->generateUrl('admin_hermano_edit', ['id' => $entity->getId()]);
        }

        return new RedirectResponse($url);
    }
}
