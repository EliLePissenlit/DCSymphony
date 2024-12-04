<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskCController extends AbstractController
{
    #[Route('/task/c', name: 'app_task_c')]
    public function index(): Response
    {
        return $this->render('task_c/index.html.twig', [
            'controller_name' => 'TaskCController',
        ]);
    }
}
