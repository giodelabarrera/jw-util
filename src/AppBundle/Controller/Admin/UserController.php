<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\Type\DatePickerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("admin/user")
 */
class UserController extends Controller
{
    /**
     * @return \AppBundle\Manager\UserManager|object
     */
    public function getManager()
    {
        return $this->get('app.user_manager');
    }

    /**
     * Lists all user entities.
     *
     * @Route("/", name="admin_user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filterBuilder = $em->getRepository('AppBundle:User')
            ->createQueryBuilder('u');

        $filterForm = $this->createFilterForm();

        if ($request->query->has($filterForm->getName())) {
            // manually bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            // id
            if ($filterForm->getData()['id']) {
                $filterBuilder
                    ->andWhere('u.id = :id')
                    ->setParameter('id', $filterForm->getData()['id']);
            }
            // created
            if ($filterForm->getData()['created']) {
                $filterBuilder
                    ->andWhere('u.created like :created')
                    ->setParameter('created', '%'.$filterForm->getData()['created']->format('Y-m-d').'%');
            }
            // updated
            if ($filterForm->getData()['updated']) {
                $filterBuilder
                    ->andWhere('u.updated like :updated')
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

        return $this->render('admin/user/index.html.twig', array(
            'pagination'  => $pagination,
            'filter_form'  => $filterForm->createView(),
        ));
    }

    /**
     * Creates a form to filter the User list
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createFilterForm()
    {
        $formName = 'filter';
        $defaultData = [];
        $options = [
            'action' => $this->generateUrl('admin_user_index'),
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
     * Creates a new user entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user, [
            'validation_groups' => ['Default', 'registration'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getManager()->save($user);

            $this->addFlash('success', 'The user has been created correctly');

            return $this->redirectTo($request, $user);
        }

        return $this->render('admin/user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="admin_user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('admin/user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $user->setRoles(['ROLE_SUPER_ADMIN']);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The user has been modified correctly');

            return $this->redirectTo($request, $user);
        }

        return $this->render('admin/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}/delete", name="admin_user_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'The user has been removed correctly');

            return $this->redirectTo($request, $user);
        }

        return $this->render('admin/user/delete.html.twig', array(
            'user' => $user,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param null $entity
     * @return RedirectResponse
     */
    private function redirectTo(Request $request, $entity = null)
    {
        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->generateUrl('admin_user_index');
        }

        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->generateUrl('admin_user_index');
        }

        if (null !== $request->get('btn_delete')) {
            $url = $this->generateUrl('admin_user_index');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $url = $this->generateUrl('admin_user_new');
        }

        if (!$url) {
            $url = $this->generateUrl('admin_user_edit', ['id' => $entity->getId()]);
        }

        return new RedirectResponse($url);
    }

    /**
     * Simula sesión
     *
     * @Route("/switch-to/{id}", name="admin_user_switch_to")
     * @Method({"GET"})
     */
    public function simulateSessionAction(User $user)
    {
        $this->getManager()->login($user);

        return $this->redirectToRoute('admin_dashboard');
    }
}
