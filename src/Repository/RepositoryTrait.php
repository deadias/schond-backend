<?php

namespace Schond\Repository;

use Schond\Entity\EntityBasica;

trait RepositoryTrait
{
    public function salvar(EntityBasica $entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->refresh($entity);
        return $entity;
    }

    public function buscarPorId(int $id)
    {
        return $this->find($id);
    }
}
