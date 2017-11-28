<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{
    const NUM_ITEMS = 10;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * Название задачи
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * Описание задачи
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     *
     * Заготовка для решения задачи
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $template;

    /**
     * @var string
     *
     * Тесты
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $test;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $createdAt;


    /**
     * @ORM\OneToMany(targetEntity="Solve", mappedBy="task")
     */
    private $solves;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->solves = new ArrayCollection();
    }

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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param string $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getSolves()
    {
        return $this->solves;
    }

    //
    /**
     * @return float
     */
    public function getPercents()
    {
        $arr = $this->getSolves()->toArray();
        $solved = count(array_filter($arr, function ($solve) {
            /** @var $solve Solve */
            return $solve->getStatus() == Solve::STATUS_SOLVED;
        }));
        
        return round($solved / count($arr) * 100, 2);
    }
}