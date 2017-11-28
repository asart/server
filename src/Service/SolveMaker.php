<?php

namespace App\Service;

use App\Common\DirectoryTrait;
use App\Entity\Task;
use Doctrine\ORM\EntityManager;

class SolveMaker
{
    use DirectoryTrait;
    
    private $rootTestPath;
    private $entityManager;
    private $phpResultReader;

    /**
     * SolveMaker constructor.
     * @param $rootDir
     * @param EntityManager $entityManager
     * @param PhpResultReader $phpResultReader
     */
    public function __construct(
        $rootDir,
        EntityManager $entityManager,
        PhpResultReader $phpResultReader
    )
    {
        $this->rootTestPath = $rootDir . '/../var/tests';
        $this->entityManager = $entityManager;
        $this->phpResultReader = $phpResultReader;
    }

    /**
     * @param Task $task
     * @param array $solve
     * @return string
     */
    public function add(Task $task, $solve)
    {
        $testPath = $this->rootTestPath . '/' . $task->getId();
        $this->mkDirIfNotExist($testPath);

        $_solve = "<?php\n%s\n?>";
        file_put_contents($testPath . '/TaskSolve.php', sprintf($_solve, $solve['solve']), LOCK_EX);
        exec('phpunit --bootstrap ' . $testPath . '/TaskSolve.php ' . $testPath . '/TaskTest', $result);
        return $this->phpResultReader->read($result);
    }
}