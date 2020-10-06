<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Reply;

class ReplyController extends Controller
{
    /**
     * @Route("/create/reply")
     */
    public function createReplyAction(Request $request)
    {
        $mid = $request->get('mid');
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('AppBundle:Message')->find($mid);
        $reply = new Reply();
        $reply->setMessage($message);
        $reply->setDate(new \DateTime("now"));
        $form = $this->createFormBuilder($reply)
            ->add('name', TextType::class)
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => '新留言'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $reply = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($reply);
            $em->flush();
            return $this->redirect('/');
        }
        return $this->render(
            'reply/add.html.twig',
            array('form' => $form->createView(), 'message' => $message)
        );
    }
    /**
     * @Route("/show/reply",name="show-reply")
     */
    public function showAction(Request $request)
    {
        $mid = $request->get('mid');
        $message = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
            ->find($mid);
        $replys = $message->getReplys();
        return $this->render(
            'reply/show.html.twig',
            array('message' => $message, 'replys' => $replys)
        );
    }
    /**
     * @Route("/delete/reply")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $reply = $em->getRepository('AppBundle:Reply')->find($id);
        if (!$reply) {
            throw $this->createNotFoundException(
                '找不到回覆 id: ' . $id
            );
        }
        $em->remove($reply);
        $em->flush();
        return $this->redirect('/');
    }
    /**
     * @Route("/update/reply")
     */
    public function updateAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $reply = $em->getRepository('AppBundle:Reply')->find($id);
        if (!$reply) {
            throw $this->createNotFoundException(
                '找不到回覆 id: ' . $id
            );
        }

        $form = $this->createFormBuilder($reply)
            ->add('name', TextType::class)
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Update'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $reply = $form->getData();
            $reply->setDate(new \DateTime("now"));
            $em->flush();
            return $this->redirect('/');
        }
        return $this->render(
            'reply/edit.html.twig',
            array('form' => $form->createView(), 'reply' => $reply)
        );
    }
}
