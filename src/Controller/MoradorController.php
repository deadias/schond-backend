<?php

namespace Schond\Controller;

use Schond\Service\AutenticadorService;
use Schond\Service\MoradorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="morador_")
 */
class MoradorController extends ControllerBasico
{
    private $moradorService;

    public function __construct(AutenticadorService $autenticadorService, MoradorService $moradorService)
    {
        parent::__construct($autenticadorService);
        $this->moradorService = $moradorService;
    }

    /**
     * @Route("/morador", name="adicionar", methods={"POST"})
     */
    public function adicionar(Request $request)
    {
        $dados = $request->request->all();
        $morador = $this->moradorService->adicionar($dados);
        return $this->json($morador->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/morador/{id}", name="atualizar", methods={"PUT"})
     */
    public function atualizar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $morador = $this->moradorService->atualizar($id, $dados);
        return $this->json($morador->toArray());
    }

    /**
     * @Route("/morador/{id}", name="desativar", methods={"DELETE"})
     */
    public function desativar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $morador = $this->moradorService->desativar($id);
        $resposta = ['Morador desativado', 'id' => $morador->getId(), 'atualizadoEm' => $morador->getAtualizadoEm()];
        return $this->json($resposta);
    }

    /**
     * @Route("/morador/{id}", name="buscarPorId", methods={"GET"})
     */
    public function buscarPorId(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $morador = $this->moradorService->buscarPorId($id);
        return $this->json($morador->toArray());
    }
}
