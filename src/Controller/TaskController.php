<?php

namespace App\Controller;

use App\Entity\Solve;
use App\Entity\Task;
use App\Form\TaskSolveType;
use App\Form\TaskType;
use App\Service\SolveMaker;
use App\Service\TaskMaker;
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
    private $taskMaker;
    private $solveMaker;

    /**
     * TaskController constructor.
     * @param TaskMaker $taskMaker
     * @param SolveMaker $solveMaker
     */
    public function __construct(
        TaskMaker $taskMaker,
        SolveMaker $solveMaker
    )
    {
        $this->taskMaker = $taskMaker;
        $this->solveMaker = $solveMaker;
    }

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
        $form = $this->createForm(TaskSolveType::class, ['solve' => $task->getTemplate()], [
            'action' => $this->generateUrl('task_solve', ['id' => $task->getId()]),
            'method' => 'POST',
        ]);
        return $this->render('task/view.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
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
            $task = $this->taskMaker->add($form->getData());
            return $this->redirectToRoute('task_view', ['id' => $task->getId()]);
        }

        return $this->render('task/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/solve/{id}", name="task_solve")
     * @Method("POST")
     *
     * @param Task $task
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function solveAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskSolveType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->solveMaker->add($task, $form->getData());
            return $this->json([
                'status' => $result['status'],
                'output' => $result['output']
            ]);
        }
        return $this->json([
            'status' => Solve::UNSOLVED,
            'output' => ['FAILURES!']
        ]);
    }
}