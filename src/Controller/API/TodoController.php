<?php

// src/Controller/API/TodoController.php
namespace App\Controller\API;

use App\Repository\TodoRepository;
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
    public function index(Request $request, TodoRepository $todoRepository): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $todo = $todoRepository->findAll();
        return $this->json($todo);
    }

    #[Route("/read/todo/{id}", methods: ["GET"])]
    public function getTaskById(int $id): \Symfony\Component\HttpFoundation\JsonResponse
    {
        // Récupérer le Todo par ID
        $todo = $this->todoService->findTodoById($id);

        // Vérifier si le Todo existe
        if (!$todo) {
            return $this->json(['error' => 'La tâche n\'existe pas'], Response::HTTP_NOT_FOUND);
        }

        // Retourner les données du Todo sous forme de JSON
        return $this->json($todo);
    }

    #[Route("/filter/todo", methods: ["GET"])]
    public function filterTodos(
        Request        $request,
        TodoRepository $todoRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $titleFilter = $request->query->get('title', '');
        $descriptionFilter = $request->query->get('description', '');

        $queryBuilder = $todoRepository->createQueryBuilder('t');

        if (!empty($titleFilter)) {
            $queryBuilder->andWhere('t.title LIKE :title')
                ->setParameter('title', '%' . $titleFilter . '%');
        }

        if (!empty($descriptionFilter)) {
            $queryBuilder->andWhere('t.descriptionLongue LIKE :description')
                ->setParameter('description', '%' . $descriptionFilter . '%');
        }

        $todos = $queryBuilder->getQuery()->getResult();

        return $this->json($todos);
    }

    #[Route("/sort/todo", methods: ["GET"])]
    public function sortTodos(
        Request        $request,
        TodoRepository $todoRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $sortOrder = $request->query->get('sortOrder', 'asc'); // Par défaut, tri croissant

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return $this->json(['error' => 'Invalid sortOrder value'], Response::HTTP_BAD_REQUEST);
        }

        $queryBuilder = $todoRepository->createQueryBuilder('t');

        $queryBuilder->orderBy('t.dueAt', $sortOrder);

        $todos = $queryBuilder->getQuery()->getResult();

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
