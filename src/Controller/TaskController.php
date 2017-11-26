<?php

namespace App\Controller;


use App\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}