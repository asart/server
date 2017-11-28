<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskSolveType;
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
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $rootTestPath = $this->container->getParameter('kernel.root_dir') . '/../var/tests';
            if (!is_dir($rootTestPath))
                mkdir($rootTestPath, 0777, true);

            $testPath = $rootTestPath . '/' . $task->getId();
            if (!is_dir($testPath))
                mkdir($testPath, 0777, true);

            $_test = "<?php\ndeclare(strict_types=1);\nuse PHPUnit\\Framework\\TestCase;\nclass TaskTest extends TestCase\n{\n\t%s\n}";

            file_put_contents($testPath . '/TaskTest.php', sprintf($_test, $task->getTest()), LOCK_EX);

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
            $data = $form->getData();
            $rootTestPath = $this->container->getParameter('kernel.root_dir') . '/../var/tests';
            $testPath = $rootTestPath . '/' . $task->getId();
            
            if (!is_dir($testPath))
                mkdir($testPath, 0777, true);

            $_solve = "<?php\n%s\n?>";
            file_put_contents($testPath . '/TaskSolve.php', sprintf($_solve, $data['solve']), LOCK_EX);
            $result = shell_exec('phpunit --bootstrap ' . $testPath . '/TaskSolve.php ' . $testPath . '/TaskTest');
            return $this->render('task/solve.html.twig', ['result' => $result]);
        }
        return $this->render('task/solve.html.twig', ['result' => 'FAILURES!']);
    }
}