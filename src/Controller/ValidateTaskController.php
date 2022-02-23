<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/task/{id}/validate", name="validate_task")
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

        return new JsonResponse(array(
            'id' => $data->getId(),
            'name' => $data->getName(),
            'is_done' => $data->getIsDone()
        ));
    }
}
