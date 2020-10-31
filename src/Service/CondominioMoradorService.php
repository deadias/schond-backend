<?php
namespace Schond\Service;

use Doctrine\ORM\EntityNotFoundException;
use Schond\Entity\CondominioMorador;
use Schond\Exception\SchondException;
use Schond\Repository\CondominioMoradorRepository;

class CondominioMoradorService
{
    private $condominioMoradorRepository;

    private $condominioService;

    public function __construct(
        CondominioMoradorRepository $condominioMoradorRepository,
        CondominioService $condominioService
    ) {
        $this->condominioMoradorRepository = $condominioMoradorRepository;
        $this->condominioService = $condominioService;
    }

    public function adicionar(array $dados)
    {
        $morador = $this->condominioMoradorRepository->buscarMoradorPorId($dados['moradorId']);
        $condominio = $this->condominioService->buscarPorId($dados['condominioId']);

        $condominioMorador = new CondominioMorador();
        $condominioMorador->setMorador($morador);
        $condominioMorador->setCondominio($condominio);
        $condominioMorador->setUnidadeHabitacional($dados['unidadeHabitacional']);
        $condominioMorador->setCriadoEm(new \DateTime());
        $condominioMorador->setAtualizadoEm(new \DateTime());
        $condominioMorador->setAtivo(true);
        return $this->condominioMoradorRepository->salvar($condominioMorador);
    }

    public function atualizar(int $id, $dados)
    {
        /** @var CondominioMorador $condominioMorador */
        $condominioMorador = $this->buscarPorId($id);

        if (!$condominioMorador->getAgendamentos()->isEmpty()) {
            throw new SchondException('Existe agendamento para esta entidade. Atualização negada');
        }

        $condominio = $this->condominioService->buscarPorId($dados['condominioId']);

        $condominioMorador->setCondominio($condominio);
        $condominioMorador->setUnidadeHabitacional($dados['unidadeHabitacional']);
        $condominioMorador->setAtualizadoEm(new \DateTime());
        return $this->condominioMoradorRepository->salvar($condominioMorador);
    }

    public function desativar(int $id)
    {
        $condominioMorador = $this->condominioMoradorRepository->buscarPorId($id);
        $condominioMorador->setAtivo(false);
        $condominioMorador->setAtualizadoEm(new \DateTime());
        return $this->condominioMoradorRepository->salvar($condominioMorador);
    }

    public function buscarPorId(int $id)
    {
        $condominioMorador = $this->condominioMoradorRepository->buscarPorId($id);
        if (is_null($condominioMorador)) {
            throw new EntityNotFoundException("Relação Condomínio/Morador não encontrada");
        }
        return $condominioMorador;
    }
}
