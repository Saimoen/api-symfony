<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todo>
 *
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    public function save(Todo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Todo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Exemple de méthode personnalisée pour trouver des liens par description
    public function findByDescription(string $description): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.description LIKE :desc')
            ->setParameter('desc', '%' . $description . '%')
            ->getQuery()
            ->getResult();
    }
}
