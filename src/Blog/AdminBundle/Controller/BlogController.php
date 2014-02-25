<?php

namespace Blog\AdminBundle\Controller;

use Blog\AdminBundle\Form\NewBlogFormType;
use Blog\BlogBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_admin.blog.index")
     * @Template()
     */
    public function indexAction()
    {
        $blogRepository = $this->getBlogRepository();
        $blogs = $blogRepository->findAll();

        return array('blogs' => $blogs);
    }

    /**
     * @Route("/new", name="blog_admin.blog.new")
     * @Template()
     * @Method({"GET|POST"})
     */
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(new NewBlogFormType(), $blog);

        if($request->getMethod() == "POST") {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getEntityManager()->persist($blog);
                $this->getEntityManager()->flush();

                return $this->redirect($this->generateUrl('blog_admin.blog.index'));
            }
        }

        return array(
            'form' => $form->createView()
        );
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
