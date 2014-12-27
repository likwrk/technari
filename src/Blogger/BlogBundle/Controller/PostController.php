<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Post;

/**
* Blog controller
*/
class PostController extends Controller
{
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('BlogBundle:Post')->find($id);
        $comments = $em->getRepository('BlogBundle:Comment')->getCommentsForPost($post->getId());

        if (!$post) {
            throw  $this->createNotFoundException('Unable to find Blog post.');
        }

        return $this->render('BlogBundle:Post:show.html.twig', array(
            'post' => $post,
            'comments' => $comments
        ));
    }
}
