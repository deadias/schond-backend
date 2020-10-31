<?php

namespace Schond\Entity;

use Schond\Repository\CondominioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CondominioRepository::class)
 */
class Condominio extends EntityBasica
{
    use BaseEntity;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     */
    private $nome;

    /**
     * @ORM\Column(type="integer")
     */
    private $qtdUnidadesHabitacionais;

    /**
     * @ORM\OneToMany(targetEntity=AreaComum::class, mappedBy="condominio")
     */
    private $areasComuns;

    /**
     * @ORM\OneToMany(targetEntity="CondominioMorador", mappedBy="condominio", fetch="EXTRA_LAZY")
     */
    private $condominiosMoradores;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ativo;

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getQtdUnidadesHabitacionais(): ?int
    {
        return $this->qtdUnidadesHabitacionais;
    }

    public function setQtdUnidadesHabitacionais(int $qtdUnidadesHabitacionais): self
    {
        $this->qtdUnidadesHabitacionais = $qtdUnidadesHabitacionais;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAreasComuns()
    {
        return $this->areasComuns;
    }

    /**
     * @param mixed $areasComuns
     */
    public function setAreasComuns($areasComuns): void
    {
        $this->areasComuns = $areasComuns;
    }

    /**
     * @return mixed
     */
    public function getCondominiosMoradores()
    {
        return $this->condominiosMoradores;
    }

    /**
     * @param mixed $condominiosMoradores
     */
    public function setCondominiosMoradores($condominiosMoradores): void
    {
        $this->condominiosMoradores = $condominiosMoradores;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'qtdUnidadesHabitacionais' => $this->qtdUnidadesHabitacionais,
            'atualizadoEm' => $this->getAtualizadoEm()->format('d/m/Y H:i:s')
        ];
    }
}
