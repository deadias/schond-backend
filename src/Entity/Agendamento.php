<?php

namespace Schond\Entity;

use Schond\Repository\AgendamentoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgendamentoRepository::class)
 */
class Agendamento extends EntityBasica
{
    use BaseEntity;

    /**
     * @var CondominioMorador $condominioMorador
     * @ORM\ManyToOne(targetEntity=CondominioMorador::class, inversedBy="AreaComum")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condominioMorador;

    /**
     * @var AreaComum $areaComum
     * @ORM\ManyToOne(targetEntity=AreaComum::class, inversedBy="agendamentos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $areaComum;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data;

    /**
     * @ORM\Column(type="integer")
     */
    private $duracao;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $noShow;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPessoas;

    /**
     * @return CondominioMorador|null
     */
    public function getCondominioMorador(): ?CondominioMorador
    {
        return $this->condominioMorador;
    }

    public function setCondominioMorador(?CondominioMorador $condominioMorador): self
    {
        $this->condominioMorador = $condominioMorador;

        return $this;
    }

    /**
     * @return AreaComum|null
     */
    public function getAreaComum(): ?AreaComum
    {
        return $this->areaComum;
    }

    public function setAreaComum(?AreaComum $areaComum): self
    {
        $this->areaComum = $areaComum;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDuracao(): ?int
    {
        return $this->duracao;
    }

    public function setDuracao(int $duracao): self
    {
        $this->duracao = $duracao;

        return $this;
    }

    public function getNoShow(): ?bool
    {
        return $this->noShow;
    }

    public function setNoShow(?bool $noShow): self
    {
        $this->noShow = $noShow;

        return $this;
    }

    public function getTotalPessoas(): ?int
    {
        return $this->totalPessoas;
    }

    public function setTotalPessoas(int $totalPessoas): self
    {
        $this->totalPessoas = $totalPessoas;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'condominioMorador' => $this->getCondominioMorador()->toArray(),
            'areaComum' => $this->getAreaComum()->toArray(),
            'totalPessoas' => $this->getTotalPessoas(),
            'duracao' => $this->getDuracao(),
            'data' => $this->getData()->format('d/m/Y H:i:s')
        ];
    }
}
