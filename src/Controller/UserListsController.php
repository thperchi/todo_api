<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users/{id}/lists", name="user_lists")
 */
class UserListsController extends AbstractController
{
    public function __invoke(User $data)
    {
        $lists = $data->getLists();
        foreach($lists as $l) {
            foreach ($l->getTasks() as $t) {
                $arrayTasks[] = array(
                    'id' => $t->getId(),
                    'name' => $t->getName(),
                    'is_done' => $t->getIsDone()
                );
            }
            $arrayLists[] = array(
                'id' => $l->getId(),
                'name' => $l->getName(),
                'due_date' => $l->getDueDate(),
                'tasks' => $arrayTasks
            );
       }
       return new JsonResponse($arrayLists);
    }
}
