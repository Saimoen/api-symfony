<?php
namespace App\Service;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;

class TodoService
{
    private TodoRepository $todoRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TodoRepository $todoRepository, EntityManagerInterface $entityManager)
    {
        $this->todoRepository = $todoRepository;
        $this->entityManager = $entityManager;
    }

    public function createTodo(array $data): Todo
    {
        $todo = new Todo();
        $todo->setTitle($data['title']);
        $todo->setDescriptionLongue($data['descriptionLongue'] ?? null);
        $todo->setResume($data['resume'] ?? null);
        $todo->setDueAt(isset($data['dueAt']) ? new \DateTime($data['dueAt']) : null);
        $todo->setCreatedAt(new \DateTime());
        $todo->setUpdatedAt(new \DateTime());
        $todo->setDone($data['done'] ?? false);

        $this->todoRepository->save($todo, true);

        return $todo;
    }

    public function updateTodo(Todo $todo, array $data): Todo
    {
        if (isset($data['title'])) {
            $todo->setTitle($data['title']);
        }

        if (isset($data['descriptionLongue'])) {
            $todo->setDescriptionLongue($data['descriptionLongue']);
        }

        if (isset($data['resume'])) {
            $todo->setResume($data['resume']);
        }

        if (isset($data['dueAt'])) {
            $todo->setDueAt(new \DateTime($data['dueAt']));
        }

        if (isset($data['createdAt'])) {
            $todo->setCreatedAt(new \DateTime($data['createdAt']));
        }

        if (isset($data['updatedAt'])) {
            $todo->setUpdatedAt(new \DateTime($data['updatedAt']));
        }

        if (isset($data['done'])) {
            $todo->setDone($data['done']);
        }

        $this->entityManager->persist($todo);
        $this->entityManager->flush();

        return $todo;
    }

    public function deleteTodo(Todo $todo): void
    {
        $this->entityManager->remove($todo);
        $this->entityManager->flush();
    }

    public function filterAndSortTodos($titleFilter = '', $descriptionFilter = '', $sortField = 'createdAt', $sortOrder = 'asc')
    {
        $queryBuilder = $this->todoRepository->createQueryBuilder('t');

        if (!empty($titleFilter)) {
            $queryBuilder->andWhere('t.title LIKE :title')
                ->setParameter('title', '%' . $titleFilter . '%');
        }

        if (!empty($descriptionFilter)) {
            $queryBuilder->andWhere('t.descriptionLongue LIKE :description')
                ->setParameter('description', '%' . $descriptionFilter . '%');
        }

        $queryBuilder->orderBy('t.' . $sortField, $sortOrder);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findTodoById(int $id)
    {
        return $this->todoRepository->find($id);
    }
}
