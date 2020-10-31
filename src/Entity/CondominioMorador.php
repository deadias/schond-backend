<?php

namespace Schond\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Schond\Repository\CondominioMoradorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CondominioMoradorRepository::class)
 */
class CondominioMorador extends EntityBasica
{
    use BaseEntity;

    /**
     * @var Condominio $condominio
     * @ORM\ManyToOne(targetEntity="Condominio", inversedBy="condominiosMoradores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condominio;

    /**
     * @var Morador $morador
     * @ORM\ManyToOne(targetEntity="Morador", inversedBy="condominiosMoradores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $morador;

    /**
     * @ORM\Column(type="integer")
     */
    private $unidadeHabitacional;

    /**
     * @ORM\OneToMany(targetEntity=Agendamento::class, mappedBy="condominioMorador")
     */
    private $agendamentos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    public function __construct()
    {
        $this->agendamentos = new ArrayCollection();
    }

    /**
     * @return Condominio
     */
    public function getCondominio()
    {
        return $this->condominio;
    }

    /**
     * @param mixed $condominio
     */
    public function setCondominio($condominio): void
    {
        $this->condominio = $condominio;
    }

    /**
     * @return Morador
     */
    public function getMorador()
    {
        return $this->morador;
    }

    /**
     * @param mixed $morador
     */
    public function setMorador($morador): void
    {
        $this->morador = $morador;
    }

    /**
     * @return mixed
     */
    public function getUnidadeHabitacional()
    {
        return $this->unidadeHabitacional;
    }

    /**
     * @param mixed $unidadeHabitacional
     */
    public function setUnidadeHabitacional($unidadeHabitacional): void
    {
        $this->unidadeHabitacional = $unidadeHabitacional;
    }

    /**
     * @return Collection|Agendamento[]
     */
    public function getAgendamentos(): Collection
    {
        return $this->agendamentos;
    }

    public function addAgendamento(Agendamento $agendamento): self
    {
        if (!$this->agendamentos->contains($agendamento)) {
            $this->agendamentos[] = $agendamento;
            $agendamento->setCondominioMorador($this);
        }

        return $this;
    }

    public function removeAgendamento(Agendamento $agendamento): self
    {
        if ($this->agendamentos->contains($agendamento)) {
            $this->agendamentos->removeElement($agendamento);
            if ($agendamento->getCondominioMorador() === $this) {
                $agendamento->setCondominioMorador(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin): void
    {
        $this->admin = $admin;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'condominio' => $this->getCondominio()->toArray(),
            'morador' => $this->morador->toArray(),
            'unidadeHabitacional' => $this->getUnidadeHabitacional(),
            'atualizadoEm' => $this->getAtualizadoEm(),
            'admin' => $this->admin
        ];
    }
}
