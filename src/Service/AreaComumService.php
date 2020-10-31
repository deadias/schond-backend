<?php
namespace Schond\Service;

use Doctrine\ORM\EntityNotFoundException;
use http\Exception\InvalidArgumentException;
use Schond\Entity\AreaComum;
use Schond\Repository\AreaComumRepository;

class AreaComumService
{
    private $areaComumRepository;

    private $condominioService;

    public function __construct(AreaComumRepository $areaComumRepository, CondominioService $condominioService)
    {
        $this->areaComumRepository = $areaComumRepository;
        $this->condominioService = $condominioService;
    }

    public function adicionar(array $dados)
    {
        $condominio = $this->condominioService->buscarPorId($dados['condominioId']);

        $areaComum = new AreaComum();
        $areaComum->setNome($dados['nome']);
        $areaComum->setCapacidade($dados['capacidade']);
        $areaComum->setLimitacao($dados['limitacao']);
        $areaComum->setCondominio($condominio);
        $areaComum->setAtivo(true);
        $areaComum->setCriadoEm(new \DateTime());
        $areaComum->setAtualizadoEm(new \DateTime());

        return $this->areaComumRepository->salvar($areaComum);
    }

    public function atualizar(int $id, $dados)
    {
        $areaComum = $this->areaComumRepository->buscarPorId($id);

        $condominio = $this->condominioService->buscarPorId($dados['condominioId']);
        if (is_null($condominio)) {
            throw new InvalidArgumentException("Condomínio Inválido");
        }

        $areaComum->setNome($dados['nome']);
        $areaComum->setCapacidade($dados['capacidade']);
        $areaComum->setLimitacao($dados['limitacao']);
        $areaComum->setCondominio($condominio);
        $areaComum->setAtualizadoEm(new \DateTime());

        return $this->areaComumRepository->salvar($areaComum);
    }

    public function desativar(int $id)
    {
        $areaComum = $this->areaComumRepository->buscarPorId($id);
        $areaComum->setAtivo(false);
        $areaComum->setAtualizadoEm(new \DateTime());
        return $this->areaComumRepository->salvar($areaComum);
    }

    public function buscarPorId(int $id)
    {
        $areaComum = $this->areaComumRepository->buscarPorId($id);
        if (is_null($areaComum)) {
            throw new EntityNotFoundException("Área comum não localizada");
        }
        return $areaComum;
    }

    public function buscarPorCondominio($id)
    {
        return $this->areaComumRepository->buscarPorCondominio($id);
    }
}
