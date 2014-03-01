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
        $user = $this->getAccount();

        return $this->render('BlogAdminBundle:Admin:index.html.twig', array('name' => $user->getUsername()));
    }

    /**
     * @return \Blog\AccountBundle\Entity\Account
     */
    private function getAccount()
    {
        return $this->getUser();
    }
}
