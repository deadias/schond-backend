<?php

namespace Schond\Repository;

use Schond\Entity\Condominio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CondominioRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Condominio::class);
    }

    public function getTodosAtivos()
    {
        return $this->findBy(['ativo' => true]);
    }
}
