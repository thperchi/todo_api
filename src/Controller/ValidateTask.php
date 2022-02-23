<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/task/{id}/validate")
 */
class ValidateTaskController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Task $data)
    {
        if ($data->getIsDone() == false) $data->setIsDone(true);
        else $data->setIsDone(false);
        $this->em->persist($data);
        $this->em->flush();
    }
}
