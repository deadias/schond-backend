<?php
namespace Schond\Service;

use Doctrine\ORM\EntityNotFoundException;
use Schond\Entity\Condominio;
use Schond\Repository\CondominioRepository;

class CondominioService
{
    private $condominioRepository;

    public function __construct(CondominioRepository $condominioRepository)
    {
        $this->condominioRepository = $condominioRepository;
    }

    public function adicionar(array $dados)
    {
        $condominio = new Condominio();
        $condominio->setNome($dados['nome']);
        $condominio->setQtdUnidadesHabitacionais($dados['qtdUnidadesHabitacionais']);
        $condominio->setCriadoEm(new \DateTime());
        $condominio->setAtualizadoEm(new \DateTime());
        $condominio->setAtivo(true);
        return $this->condominioRepository->salvar($condominio);
    }

    public function atualizar(int $id, array $dados)
    {
        $condominio = $this->condominioRepository->buscarPorId($id);
        $condominio->setNome($dados['nome']);
        $condominio->setQtdUnidadesHabitacionais($dados['qtdUnidadesHabitacionais']);
        $condominio->setAtualizadoEm(new \DateTime());
        return $this->condominioRepository->salvar($condominio);
    }

    public function desativar(int $id)
    {
        $condominio = $this->condominioRepository->buscarPorId($id);
        $condominio->setAtivo(false);
        $condominio->setAtualizadoEm(new \DateTime());
        return $this->condominioRepository->salvar($condominio);
    }

    public function buscarPorId(int $id)
    {
        $condominio = $this->condominioRepository->find($id);
        if (is_null($condominio)) {
            throw new EntityNotFoundException("Condomínio não localizado");
        }
        return $condominio;
    }

    public function buscarCondominios()
    {
        return $this->condominioRepository->getTodosAtivos();
    }
}
