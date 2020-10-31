<?php

namespace Schond\Controller;

use Schond\Service\AutenticadorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ControllerBasico extends AbstractController
{
    private $autenticadorService;

    public function __construct(AutenticadorService $autenticadorService)
    {
        $this->autenticadorService = $autenticadorService;
    }

    public function getAutenticadorService(): AutenticadorService
    {
        return $this->autenticadorService;
    }

    protected function verificarAcesso(Request $request)
    {
        $id = $request->headers->get('id');
        $token = $request->headers->get('Authorization');

        if (is_null($id) || is_null($token)) {
            throw new AccessDeniedHttpException('Acesso Negado!');
        }

        $this->autenticadorService->verificarAcesso($id, $token);
    }
}