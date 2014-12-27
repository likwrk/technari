<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

/**
* Blog controller
*/
class PageController extends Controller
{
    public function indexAction()
    {
        $man = $this->getDoctrine()->getManager();
        $posts = $man->getRepository('BlogBundle:Post')->getLatestPosts();

        return $this->render('BlogBundle:Page:index.html.twig', array( 'posts' => $posts, 'pagefullwidth' => true ));
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
