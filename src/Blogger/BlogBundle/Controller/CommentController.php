<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;

/**
* Comment controler
*/
class CommentController extends Controller
{
    public function formAction($post_id)
    {
        $post = $this->getPost($post_id);


        $comment = new Comment();
        $comment->setPost($post);
        $form = $this->createForm(new CommentType(), $comment);

        return $this->render('BlogBundle:Comment:form.html.twig', array(
            'form' => $form->createView(),
            'comment' => $comment
        ));
    }

    public function createAction($post_id)
    {
        $comment = new Comment();
        $comment->setPost($this->getPost($post_id));
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $man = $this->getDoctrine()->getManager();
            $man->persist($comment);
            $man->flush();
            return $this->redirect($this->generateUrl('BlogBundle_post', array('id' => $comment->getPost()->getId())) . '#comment-' . $comment->getId());
        }

        return $this->render('BlogBundle:Comment:create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }

    public function getPost($post_id)
    {
        $man = $this->getDoctrine()->getManager();

        $post = $man->getRepository('BlogBundle:Post')->find($post_id);

        if (!$post) {
            throw $this->createNotFoundException('No post');
        }

        return $post;
    }
}
