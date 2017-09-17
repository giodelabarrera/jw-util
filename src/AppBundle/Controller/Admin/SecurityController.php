<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class SecurityController
 *
 * @Route("admin")
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="admin_security_login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/login_check", name="admin_security_login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/logout", name="admin_security_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * rememberPasswordAction
     *
     * @Route("/remember-password", name="admin_security_remember_password")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rememberPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('app.user_manager');

        $form = $this->createRememberPasswordForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Checks if the email exists
            $user = $em->getRepository('AppBundle:User')->findOneBy([
                'email' => $form->getData()['email'],
            ]);

            if (!$user) {
                $form->get('email')->addError(new FormError('The email not exists'));
            }

            if ($form->isSubmitted() && $form->isValid()) {

                // Creates new password and sends the email
                $plainPassword = $userManager->generateRandomPlainPassword();
                $user->setPlainPassword($plainPassword);
                $userManager->save($user);

                $message = \Swift_Message::newInstance()
                    ->setSubject('['.$this->getParameter('app_name').'] Nova contrassenya d\'accés')
                    ->setFrom($this->getParameter('app_email'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'admin/security/emails/remember_password.html.twig',
                            [
                                'password' => $plainPassword,
                            ]
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);

                return $this->render('admin/security/remember_password_sent.html.twig', []);
            }
        }

        return $this->render('admin/security/remember_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * createRememberPasswordForm
     *
     *
     * @return \Symfony\Component\Form\Form
     */
    public function createRememberPasswordForm()
    {
        return $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->getForm();
    }
}
