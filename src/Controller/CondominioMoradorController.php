<?php

namespace Schond\Controller;

use Schond\Service\AutenticadorService;
use Schond\Service\CondominioMoradorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="condominiomorador_")
 */
class CondominioMoradorController extends ControllerBasico
{
    private $condominioMoradorService;

    public function __construct(AutenticadorService $autenticadorService, CondominioMoradorService $condominioMoradorService)
    {
        parent::__construct($autenticadorService);
        $this->condominioMoradorService = $condominioMoradorService;
    }

    /**
     * @Route("/condominio-morador", name="adicionar", methods={"POST"})
     */
    public function adicionar(Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $condominioMorador = $this->condominioMoradorService->adicionar($dados);
        return $this->json($condominioMorador->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/condominio-morador/{id}", name="atualizar", methods={"PUT"})
     */
    public function atualizar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $condominioMorador = $this->condominioMoradorService->atualizar($id, $dados);
        return $this->json(['id' => $condominioMorador->getId(), 'atualizadoEm' => $condominioMorador->getAtualizadoEm()]);
    }

    /**
     * @Route("/condominio-morador/{id}", name="desativar", methods={"DELETE"})
     */
    public function desativar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $condominioMorador = $this->condominioMoradorService->desativar($id);
        $resposta = ['Condominio/Morador desativado', 'id' => $condominioMorador->getId(), 'atualizadoEm' => $condominioMorador->getAtualizadoEm()];
        return $this->json($resposta);
    }

    /**
     * @Route("/condominio-morador/{id}", name="buscarPorId", methods={"GET"})
     */
    public function buscarPorId(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $condominioMorador = $this->condominioMoradorService->buscarPorId($id);
        return $this->json($condominioMorador->toArray());
    }
}
