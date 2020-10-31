<?php

namespace Schond\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Schond\Entity\Morador;
use Doctrine\Persistence\ManagerRegistry;

class MoradorRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Morador::class);
    }

    public function buscarPorEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }
}
