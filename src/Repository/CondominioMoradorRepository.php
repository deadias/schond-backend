<?php

namespace Schond\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Schond\Entity\CondominioMorador;
use Schond\Entity\Morador;
use Doctrine\Persistence\ManagerRegistry;

class CondominioMoradorRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CondominioMorador::class);
    }

    public function buscarMoradorPorId(int $id)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('m')
            ->from(Morador::class, 'm')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
