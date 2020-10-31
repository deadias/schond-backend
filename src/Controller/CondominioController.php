<?php

namespace Schond\Controller;

use Schond\Service\AutenticadorService;
use Schond\Service\CondominioService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="condominio_")
 */
class CondominioController extends ControllerBasico
{
    private $condominioService;

    public function __construct(AutenticadorService $autenticadorService, CondominioService $condominioService)
    {
        parent::__construct($autenticadorService);
        $this->condominioService = $condominioService;
    }

    /**
     * @Route("/condominio", name="adicionar", methods={"POST"})
     */
    public function adicionar(Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $condominio = $this->condominioService->adicionar($dados);
        return $this->json($condominio->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/condominio/{id}", name="atualizar", methods={"POST"})
     */
    public function atualizar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $condominio = $this->condominioService->atualizar($id, $dados);
        return $this->json($condominio->toArray());
    }

    /**
     * @Route("/condominio/{id}", name="desativar", methods={"DELETE"})
     */
    public function desativar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $condominio = $this->condominioService->desativar($id);
        $resposta = [
            'Condominio Desativado',
            'id' => $condominio->getId(),
            'atualizadoEm' => $condominio->getAtualizadoEm()
        ];
        return $this->json($resposta);
    }

    /**
     * @Route("/condominio/{id}", name="buscarPorId", methods={"GET"})
     */
    public function buscarPorId($id, Request $request)
    {
        $this->verificarAcesso($request);
        $condominio = $this->condominioService->buscarPorId($id);
        return $this->json($condominio->toArray());
    }

    /**
     * @Route("/condominios", name="buscarTodos", methods={"GET"})
     */
    public function buscarCondominios(Request $request)
    {
        $this->verificarAcesso($request);

        $condominios = $this->condominioService->buscarCondominios();
        $conds = [];

        foreach ($condominios as $c) {
            $conds[] = $c->toArray();
        }

        return $this->json($conds);
    }
}
