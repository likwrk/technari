<?php

namespace Site\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
* Blog controller
*/
class PageController extends Controller
{
    public function indexAction($slug)
    {
        $rep = $this->getDoctrine()->getManager()->getRepository('SiteBundle:Category');
        $currentCategory = $rep->findBySlug($slug);

        if (!$currentCategory) {
            throw  $this->createNotFoundException('Страница не найдена.');
        }

        $categories = $rep->getCategories($currentCategory->getId(), 'sidebar');

        $man = $this->getDoctrine()->getManager();
        $posts = $man->getRepository('BlogBundle:Post')->getLatestPosts();

        return $this->render('SiteBundle:Page:index.html.twig', array(
            'categories' => $categories,
            'pagefullwidth' => true,
            'posts' => $posts,
            'title' => $currentCategory->getTitle(),
            'currentCategory' => $currentCategory
        ));
    }

    public function subcatAction($parent_slug, $slug)
    {
        $rep = $this->getDoctrine()->getManager()->getRepository('SiteBundle:Category');
        $currentCategory = $rep->findBySlug($slug);
        $parentCategory = $rep->findBySlug($parent_slug);

        if (!$currentCategory || !$parentCategory) {
            throw  $this->createNotFoundException('Страница не найдена.');
        }

        $categories = $rep->getCategories($currentCategory->getId(), 'sidebar');

        return $this->render('SiteBundle:Page:subcat.html.twig', array(
            'categories' => $categories,
            'parentCategory' => $parentCategory,
            'title' => $currentCategory->getTitle(),
            'currentCategory' => $currentCategory
        ));

    }

    public function aboutAction()
    {
        return $this->render('BlogBundle:Page:about.html.twig');
    }

    public function contactAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add('blogger-notice', 'Сообщение отправлено от ');

                return $this->redirect($this->generateUrl('BlogBundle_contact'));
            }
        }

        return $this->render('BlogBundle:Page:contact.html.twig', array('form' => $form->createView()));
    }
}
