<?php
namespace Schond\Service;

use Doctrine\ORM\EntityNotFoundException;
use Schond\Entity\CondominioMorador;
use Schond\Entity\Morador;
use Schond\Repository\MoradorRepository;

class MoradorService
{
    private $moradorRepository;

    private $condominioMoradorService;

    public function __construct(MoradorRepository $moradorRepository, CondominioMoradorService $condominioMoradorService)
    {
        $this->moradorRepository = $moradorRepository;
        $this->condominioMoradorService = $condominioMoradorService;
    }

    public function adicionar(array $dados)
    {
        $telefoneSecundario = isset($dados['telefoneSecundario']) ? $dados['telefoneSecundario'] : null;

        $senha = password_hash($dados['senha'], PASSWORD_BCRYPT);
        $morador = new Morador();
        $morador->setNome($dados['nome']);
        $morador->setTelefonePrimario($dados['telefonePrimario']);
        $morador->setTelefoneSecundario($telefoneSecundario);
        $morador->setEmail($dados['email']);
        $morador->setCriadoEm(new \DateTime());
        $morador->setAtualizadoEm(new \DateTime());
        $morador->setAtivo(true);
        $morador->setSenha($senha);
        $morador = $this->moradorRepository->salvar($morador);

        $condominioMorador = [
            'moradorId' => $morador->getId(),
            'condominioId' => $dados['condominioId'],
            'unidadeHabitacional' => $dados['unidadeHabitacional']
        ];

        $this->condominioMoradorService->adicionar($condominioMorador);

        return $morador;
    }

    public function atualizar(int $id, $dados)
    {
        $morador = $this->moradorRepository->buscarPorId($id);

        $telefoneSecundario = isset($dados['telefoneSecundario']) ? $dados['telefoneSecundario'] : null;

        $morador->setNome($dados['nome']);
        $morador->setTelefonePrimario($dados['telefonePrimario']);
        $morador->setTelefoneSecundario($telefoneSecundario);
        $morador->setAtualizadoEm(new \DateTime());
        return $this->moradorRepository->salvar($morador);
    }

    public function desativar(int $id)
    {
        $morador = $this->moradorRepository->buscarPorId($id);
        $morador->setAtivo(false);
        $morador->setToken(null);
        $morador->setAtualizadoEm(new \DateTime());
        return $this->moradorRepository->salvar($morador);
    }

    public function buscarPorId(int $id)
    {
        $morador = $this->moradorRepository->buscarPorId($id);
        if (is_null($morador)) {
            throw new EntityNotFoundException();
        }
        return $morador;
    }
}
