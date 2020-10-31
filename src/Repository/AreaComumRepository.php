<?php

namespace Schond\Repository;

use Schond\Entity\AreaComum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AreaComumRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AreaComum::class);
    }

    public function buscarPorCondominio($id)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('ac')
            ->from(AreaComum::class, 'ac')
            ->join('ac.condominio', 'c')
            ->where('c.id = :id')
            ->andWhere('ac.ativo = :ativo')
            ->setParameter('id', $id)
            ->setParameter('ativo', true)
            ->orderBy('ac.nome')
            ->getQuery()
            ->getResult();
    }
}
