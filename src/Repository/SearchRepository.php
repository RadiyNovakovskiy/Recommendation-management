<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

class searchRepository implements searchRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }

    public function getAll(): array
    {
        $query = $this->entityManager->createQuery();
        return $query->getResult();
    }
}