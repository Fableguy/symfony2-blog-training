<?php

namespace Blog\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="blog_admin.admin.index")
     */
    public function indexAction()
    {
        return $this->render('BlogAdminBundle:Admin:index.html.twig', array('name' => 'matthijs'));
    }
}
