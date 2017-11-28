<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="solve")
 */
class Solve
{
    const STATUS_SOLVED = 1;
    const STATUS_UNSOLVED = 0;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="solves")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;
    
    /**
     * @var string
     *
     * Отправленный код
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var int
     *
     * Отправленный код
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param mixed $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}