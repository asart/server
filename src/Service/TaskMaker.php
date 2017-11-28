<?php

namespace App\Service;

use App\Common\DirectoryTrait;
use Doctrine\ORM\EntityManager;

class TaskMaker
{
    use DirectoryTrait;
    
    private $rootTestPath;
    private $entityManager;

    /**
     * TaskMaker constructor.
     * @param $rootDir
     * @param EntityManager $entityManager
     */
    public function __construct($rootDir, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->rootTestPath = $rootDir . '/../var/tests';
    }

    /**
     * @param $task
     * @return \App\Entity\Task
     */
    public function add($task)
    {
        /** @var $task \App\Entity\Task */ 
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $testPath = $this->rootTestPath . '/' . $task->getId();
        $this->mkDirIfNotExist($testPath);

        $_test = "<?php\ndeclare(strict_types=1);\nuse PHPUnit\\Framework\\TestCase;\nclass TaskTest extends TestCase\n{\n\t%s\n}";

        file_put_contents($testPath . '/TaskTest.php', sprintf($_test, $task->getTest()), LOCK_EX);
        
        return $task;
    }
}