<?php
namespace Schond\Service;

use Doctrine\ORM\EntityNotFoundException;
use Schond\Repository\MoradorRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AutenticadorService
{
    private $moradorRepository;

    public function __construct(MoradorRepository $moradorRepository)
    {
        $this->moradorRepository = $moradorRepository;
    }

    public function login(array $dados)
    {
        $morador = $this->moradorRepository->buscarPorEmail($dados['email']);

        if (!password_verify($dados['senha'], $morador->getSenha())) {
            throw new AccessDeniedHttpException('Credenciais Inválidas');
        }

        $token = $token = password_hash(random_bytes(32), PASSWORD_BCRYPT);
        $morador->setToken($token);
        $this->moradorRepository->salvar($morador);

        return [
            'id' => $morador->getId(),
            'nome' => $morador->getNome(),
            'email' => $morador->getEmail(),
            'token' => $morador->getToken(),
            'condominioMoradorId' => $morador->getCondominiosMoradores()->first()->getId(),
            'admin' => $morador->getCondominiosMoradores()->first()->isAdmin() ? true : false
        ];
    }

    public function logout(int $id)
    {
        $morador = $this->moradorRepository->buscarPorId($id);

        if (is_null($morador)) {
            throw new EntityNotFoundException('Usuário não localizado');
        }

        $morador->setToken(null);
        $this->moradorRepository->salvar($morador);

        return ['Good Bye'];
    }

    public function verificarAcesso(int $id, string $token)
    {
        $morador = $this->moradorRepository->buscarPorId($id);

        if (is_null($morador) || $token !== $morador->getToken()) {
            throw new AccessDeniedHttpException('Acesso Negado!');
        }

        return true;
    }
}