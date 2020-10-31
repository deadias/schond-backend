<?php

namespace Schond\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Schond\Repository\MoradorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoradorRepository::class)
 */
class Morador extends EntityBasica
{
    use BaseEntity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $telefonePrimario;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $telefoneSecundario;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var CondominioMorador $condominiosMoradores
     * @ORM\OneToMany(targetEntity="CondominioMorador", mappedBy="morador", fetch="EXTRA_LAZY")
     */
    private $condominiosMoradores;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $senha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getTelefonePrimario(): ?string
    {
        return $this->telefonePrimario;
    }

    public function setTelefonePrimario(string $telefonePrimario): self
    {
        $this->telefonePrimario = $telefonePrimario;

        return $this;
    }

    public function getTelefoneSecundario(): ?string
    {
        return $this->telefoneSecundario;
    }

    public function setTelefoneSecundario(?string $telefoneSecundario): self
    {
        $this->telefoneSecundario = $telefoneSecundario;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCondominiosMoradores()
    {
        return $this->condominiosMoradores;
    }

    /**
     * @param $condominiosMoradores
     */
    public function setCondominiosMoradores($condominiosMoradores): void
    {
        $this->condominiosMoradores = $condominiosMoradores;
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): self
    {
        $this->senha = $senha;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'email' => $this->getEmail(),
            'telefonePrimario' => $this->getTelefonePrimario(),
            'telefoneSecundario' => $this->getTelefoneSecundario(),
            'atualizadoEm' => $this->getAtualizadoEm()->format('d/m/Y H:i:s')
        ];
    }
}
