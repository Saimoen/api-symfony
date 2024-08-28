<?php

// src/Controller/API/TodoController.php
namespace App\Controller\API;

use App\Entity\Todo;
use App\Service\TodoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    private TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    #[Route("/read/todo", methods: ["GET"])]
    public function index(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $titleFilter = $request->query->get('title', '');
        $descriptionFilter = $request->query->get('description', '');
        $sortField = $request->query->get('sortField', 'createdAt');
        $sortOrder = $request->query->get('sortOrder', 'asc');

        $todos = $this->todoService->filterAndSortTodos($titleFilter, $descriptionFilter, $sortField, $sortOrder);

        return $this->json($todos);
    }

    #[Route("/create/todo", methods: ["POST"])]
    public function create(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['title'])) {
            return $this->json(['error' => 'Le titre est obligatoire !'], Response::HTTP_BAD_REQUEST);
        }

        $todo = $this->todoService->createTodo($data);

        return $this->json($todo, Response::HTTP_CREATED);
    }

    #[Route("/put/todo/{id}", methods: ["PUT"])]
    public function updateTodo(int $id, Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $todo = $this->todoService->findTodoById($id);

        if (!$todo) {
            return $this->json(['error' => 'Todo not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $todo = $this->todoService->updateTodo($todo, $data);

        return $this->json($todo, Response::HTTP_OK);
    }

    #[Route("/delete/todo/{id}", methods: ["DELETE"])]
    public function deleteTodo(int $id): Response
    {
        $todo = $this->todoService->findTodoById($id);

        if (!$todo) {
            return new Response('Todo not found', Response::HTTP_NOT_FOUND);
        }

        $this->todoService->deleteTodo($todo);

        return new Response('Todo deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
