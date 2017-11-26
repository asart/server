<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{
    const NUM_ITEMS = 10;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * Название задачи
     * @ORM\Column(type="string", length=100)
     */
    public $title;

    /**
     * Описание задачи
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * Заготовка для решения задачи
     * @ORM\Column(type="text")
     */
    public $template;

    /**
     * Тесты
     * @ORM\Column(type="text")
     */
    public $test;
}