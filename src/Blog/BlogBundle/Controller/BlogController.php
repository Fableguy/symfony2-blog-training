<?php

namespace Blog\BlogBundle\Controller;

use Blog\AccountBundle\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_blog.blog.index")
     * @Template()
     */
    public function indexAction()
    {
        $blogRepository = $this->getBlogRepository();
        $blogItems = $blogRepository->findAll();

        return array('blogItems' => $blogItems);
    }

    /**
     * @return \Blog\BlogBundle\Repository\BlogRepository
     */
    private function getBlogRepository()
    {
        return $this->getEntityManager()->getRepository('BlogBlogBundle:Blog');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
