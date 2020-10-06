<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Message;
use AppBundle\Repository\MessageRepository;

class MessageController extends Controller
{
  /**
   * @Route("/create/message")
   */
  public function createAction(Request $request)
  {
    $message = new Message();
    $message->setDate(new \DateTime("now"));
    $form = $this->createFormBuilder($message)
      ->add('name', TextType::class, ['attr' => ['placeholder' => '請輸入暱稱', 'class' => 'form-control']])
      ->add('text', TextareaType::class, ['attr' => ['placeholder' => '請輸入內容', 'class' => 'form-control']])
      ->add('save', SubmitType::class, array('label' => '新留言'))
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted()) {

      $message = $form->getData();

      $em = $this->getDoctrine()->getManager();
      $em->persist($message);
      $em->flush();

      $this->addFlash(
        'notice',
        '新增留言'
      );
      return $this->redirect('/');
    }
    return $this->render(
      'message/add.html.twig',
      array('form' => $form->createView())
    );
  }
  /**
   * @Route("/{page}",name="homepage")
   */
  public function showAction($page = 1)
  {
    $messageRepository = $this->getDoctrine()
      ->getRepository(Message::class);

    //顯示select總筆數
    $data_nums = $messageRepository->getAmountOfMessages();
    //每頁顯示的數量
    $num = 5;
    //取得不小於值的下一個整數
    $pages = ceil($data_nums / $num);
    // 分頁
    $messages = $messageRepository->paginaMessage($page);
    return $this->render(
      'message/show.html.twig',
      array('messages' => $messages, 'pages' => $pages)
    );
  }
  /**
   * @Route("/delete/message")
   */
  public function deleteAction(Request $request)
  {
    $id = $request->get('id');
    $em = $this->getDoctrine()->getManager();
    $message = $em->getRepository('AppBundle:Message')->find($id);

    if (!$message) {
      throw $this->createNotFoundException(
        '找不到留言 id: ' . $id
      );
    }

    $em->remove($message);
    $em->flush();

    $this->addFlash(
      'notice',
      '刪除留言'
    );
    return $this->redirect('/');
  }
  /**
   * @Route("/update/message")
   */
  public function updateAction(Request $request)
  {
    $id = (int)$request->get('id');
    $em = $this->getDoctrine()->getManager();
    $message = $em->getRepository('AppBundle:Message')->find($id);
    if (!$message) {
      throw $this->createNotFoundException(
        '找不到留言 id: ' . $id
      );
    }

    $form = $this->createFormBuilder($message)
      ->add('name', TextType::class)
      ->add('text', TextareaType::class)
      ->add('save', SubmitType::class, array('label' => '更新'))
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted()) {

      $message = $form->getData();
      $message->setDate(new \DateTime("now"));
      $em = $this->getDoctrine()->getManager();
      $em->persist($message);
      $em->flush();
      $this->addFlash(
        'notice',
        '修改留言'
      );
      return $this->redirect('/');
    }
    return $this->render(
      'message/edit.html.twig',
      array('form' => $form->createView(), 'message' => $message)
    );
  }
  /**
   * @Route("/bulk-create/message")
   */
  public function bulkcreateAction(Request $request)
  {
    $form = $this->createFormBuilder()
      ->add('count', IntegerType::class)
      ->add('name', TextType::class, ['attr' => ['placeholder' => '請輸入暱稱', 'class' => 'form-control']])
      ->add('text', TextareaType::class, ['attr' => ['placeholder' => '請輸入內容', 'class' => 'form-control']])
      ->add('save', SubmitType::class, array('label' => '新留言'))
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      $em = $this->getDoctrine()->getManager();
      $count = $form->get('count')->getData();
      $name = $form->get('name')->getData();
      $text = $form->get('text')->getData();
      for ($i = 0; $i < $count; $i++) {
        $message = new Message();
        $message->setName($name);
        $message->setText($text);
        $message->setDate(new \DateTime("now"));
        $em->persist($message);
        $em->flush();
        $em->clear();
      }
      return $this->redirect('/');
    }
    return $this->render(
      'message/bulk_add.html.twig',
      array('form' => $form->createView())
    );
  }
  /**
   * @Route("/bulk-edit/message")
   */
  public function bulkeditAction(Request $request)
  {
    $form = $this->createFormBuilder()
      ->add('count', IntegerType::class)
      ->add('name', TextType::class, ['attr' => ['placeholder' => '請輸入暱稱', 'class' => 'form-control']])
      ->add('text', TextareaType::class, ['attr' => ['placeholder' => '請輸入內容', 'class' => 'form-control']])
      ->add('save', SubmitType::class, array('label' => '修改留言'))
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      //設定batchsize大小
      $batchSize = 50;
      $em = $this->getDoctrine()->getManager();
      $count = $form->get('count')->getData();
      $name = $form->get('name')->getData();
      $text = $form->get('text')->getData();
      $iterableResult = $this->getDoctrine()
        ->getRepository(Message::class)
        ->getCount($count);

      foreach ($iterableResult as $row) {
        $message = $row[0];
        $message->setName($name);
        $message->setText($text);
        $message->setDate(new \DateTime("now"));
        $em->persist($message);
        if (($count % $batchSize) === 0) {
          $em->flush();
          $em->clear();
        }
        $count++;
      }
      $em->flush();
      $em->clear();
      return $this->redirect('/');
    }
    return $this->render(
      'message/bulk_edit.html.twig',
      array('form' => $form->createView())
    );
  }
  /**
   * @Route("/bulk-del/message")
   */
  public function bulkdelAction(Request $request)
  {
    $form = $this->createFormBuilder()
      ->add('count', IntegerType::class)
      ->add('save', SubmitType::class, array('label' => '刪除留言'))
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      //設定batchsize大小
      $batchSize = 50;
      $em = $this->getDoctrine()->getManager();
      $count = $form->get('count')->getData();
      $iterableResult = $this->getDoctrine()
        ->getRepository(Message::class)
        ->getCount($count);
      foreach ($iterableResult as $row) {
        $message = $row[0];
        $em->remove($message);
        if (($count % $batchSize) === 0) {
          $em->flush();
          $em->clear();
        }
        $count++;
      }
      $em->flush();
      $em->clear();
      return $this->redirect('/');
    }
    return $this->render(
      'message/bulk_del.html.twig',
      array('form' => $form->createView())
    );
  }
}
