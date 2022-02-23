<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api/task/{id}/validate", name="validate_task")
 */
class ValidateTaskController extends AbstractController
{
    protected $em;
    protected $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function __invoke(Task $data)
    {
        if ($data->getList()->getUser() == $this->security->getUser()) {
            if ($data->getIsDone() == false) $data->setIsDone(true);
            else $data->setIsDone(false);
            $this->em->persist($data);
            $this->em->flush();
        } else throw new AccessDeniedException();

        return new JsonResponse(array(
            'id' => $data->getId(),
            'name' => $data->getName(),
            'is_done' => $data->getIsDone()
        ));
    }
}
