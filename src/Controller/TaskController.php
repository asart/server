<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskController
 *
 * @Route("/tasks")
 *
 * @package App\Controller
 */
class TaskController extends Controller
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="task_index")
     * @Method("GET")
     *
     * @param $_format
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($_format = 'html')
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        return $this->render('task/index.' . $_format . '.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/view/{id}", name="task_view")
     * @Method("GET")
     *
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Task $task)
    {
        return $this->render('task/view.html.twig', ['task' => $task]);
    }

    /**
     * @Route("/add", name="task_add")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_view', ['id' => $task->getId()]);
        }

        return $this->render('task/add.html.twig', ['form' => $form->createView()]);
    }
}