<?php

namespace Schond\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Schond\Repository\AreaComumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AreaComumRepository::class)
 */
class AreaComum extends EntityBasica
{
    use BaseEntity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacidade;

    /**
     * @ORM\Column(type="integer")
     */
    private $limitacao;

    /**
     * @var Condominio
     * @ORM\ManyToOne(targetEntity=Condominio::class, inversedBy="areasComuns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condominio;

    /**
     * @ORM\OneToMany(targetEntity=Agendamento::class, mappedBy="areaComum")
     */
    private $agendamentos;

    public function __construct()
    {
        $this->agendamentos = new ArrayCollection();
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCapacidade(): ?int
    {
        return $this->capacidade;
    }

    public function setCapacidade(int $capacidade): self
    {
        $this->capacidade = $capacidade;

        return $this;
    }

    public function getLimitacao(): ?int
    {
        return $this->limitacao;
    }

    public function setLimitacao(int $limitacao): self
    {
        $this->limitacao = $limitacao;

        return $this;
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
            $agendamento->setAreaComum($this);
        }

        return $this;
    }

    public function removeAgendamento(Agendamento $agendamento): self
    {
        if ($this->agendamentos->contains($agendamento)) {
            $this->agendamentos->removeElement($agendamento);
            if ($agendamento->getAreaComum() === $this) {
                $agendamento->setAreaComum(null);
            }
        }

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'capacidade' => $this->getCapacidade(),
            'limitacao' => $this->getLimitacao(),
            'condominioId' => $this->getCondominio()->getId(),
            'atualizadoEm' => $this->getAtualizadoEm()->format('d/m/Y H:i:s')
        ];
    }
}
