<?php

namespace Schond\Entity;

use Schond\Repository\AgendamentoRepository;
use Doctrine\ORM\Mapping as ORM;

trait BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime $criadoEm
     * @ORM\Column(type="datetime")
     */
    private $criadoEm;

    /**
     * @var \DateTime $atualizadoEm
     * @ORM\Column(type="datetime")
     */
    private $atualizadoEm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ativo;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCriadoEm()
    {
        return $this->criadoEm;
    }

    /**
     * @param mixed $criadoEm
     */
    public function setCriadoEm($criadoEm): void
    {
        $this->criadoEm = $criadoEm;
    }

    /**
     * @return \DateTime
     */
    public function getAtualizadoEm()
    {
        return $this->atualizadoEm;
    }

    /**
     * @param mixed $atualizadoEm
     */
    public function setAtualizadoEm($atualizadoEm): void
    {
        $this->atualizadoEm = $atualizadoEm;
    }

    /**
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo): void
    {
        $this->ativo = $ativo;
    }
}
