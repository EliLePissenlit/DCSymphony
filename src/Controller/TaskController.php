<?php

namespace App\Controller;

use App\Entity\Task;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController
{
    #[Route('/tasks',name: 'create_task',methods: ['POST'])]
    //création
    public function createTask(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$this->isValidData($data, ['title', 'description', 'status'])) {
            return $this->errorResponse('Invalid data', 400);
        }

        $task = (new Task())
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setStatus($data['status']);

        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->successResponse($task, 201);
    }

    #[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    //mise à jour
    public function updateTask(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->errorResponse('Task not found', 404);
        }

        $data = json_decode($request->getContent(), true);
        $task->setTitle($data['title'] ?? $task->getTitle())
            ->setDescription($data['description'] ?? $task->getDescription())
            ->setStatus($data['status'] ?? $task->getStatus());

        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->successResponse($task);
    }

    #[Route('/tasks/{id}', name: 'delete_task', methods: ['DELETE'])]

    //suppresion
    public function deleteTask(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->errorResponse('Task not found', 404);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task deleted successfully'], 200);
    }

    private function isValidData(array $data, array $requiredFields): bool
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }

    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse(['error' => $message], $statusCode);
    }

    private function validationErrorResponse($errors): JsonResponse
    {
        $errorMessages = array_map(fn($error) => $error->getMessage(), iterator_to_array($errors));
        return new JsonResponse(['errors' => $errorMessages], 400);
    }

    private function successResponse(Task $task, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'createdAt' => $task->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $task->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ], $statusCode);
    }
}
