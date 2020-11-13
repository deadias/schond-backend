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

    /**
     * Efetuar o login do usuário
     * @var array $dados
     */
    public function login(array $dados)
    {
        $morador = $this->moradorRepository->buscarPorEmail($dados['email']);

        //Verificando a senha do usuário
        if (!password_verify($dados['senha'], $morador->getSenha())) {
            throw new AccessDeniedHttpException('Credenciais Inválidas');
        }

        //Gerando um token de acesso
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

    /**
     * Efetuar o logout do usuário
     * @var int $id
     */
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

    /**
     * Verificar se o usuário está autenticado
     * @var $id
     * @var string $token
     */
    public function verificarAcesso(int $id, string $token)
    {
        $morador = $this->moradorRepository->buscarPorId($id);

        if (is_null($morador) || $token !== $morador->getToken()) {
            throw new AccessDeniedHttpException('Acesso Negado!');
        }

        return true;
    }
}