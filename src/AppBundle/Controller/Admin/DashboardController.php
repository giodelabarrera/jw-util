<?php
/**
 * Created by PhpStorm.
 * User: giorgio
 * Date: 20/7/17
 * Time: 12:46
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DashboardController
 * @package AppBundle\Controller\Admin
 *
 * @Route("admin")
 */
class DashboardController extends Controller
{

    /**
     * @Route("/", name="admin_index")
     * @Route("/dashboard", name="admin_dashboard")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->render('admin/dashboard/index.html.twig', [
        ]);
    }
}