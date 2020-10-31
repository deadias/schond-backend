<?php

namespace Schond\Controller;

use Schond\Service\AreaComumService;
use Schond\Service\AutenticadorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="areacomum_")
 */
class AreaComumController extends ControllerBasico
{
    private $areaComumService;

    public function __construct(AutenticadorService $autenticadorService, AreaComumService $areaComumService)
    {
        parent::__construct($autenticadorService);
        $this->areaComumService = $areaComumService;
    }

    /**
     * @Route("/area-comum", name="adicionar", methods={"POST"})
     */
    public function adicionar(Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $areaComum = $this->areaComumService->adicionar($dados);
        return $this->json($areaComum->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/area-comum/{id}", name="atualizar", methods={"POST"})
     */
    public function atualizar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $areaComum = $this->areaComumService->atualizar($id, $dados);
        return $this->json($areaComum->toArray());
    }

    /**
     * @Route("/area-comum/{id}", name="desativar", methods={"DELETE"})
     */
    public function desativar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $areaComum = $this->areaComumService->desativar($id);
        $resposta = [
            'Area Comum Desativado',
            'id' => $areaComum->getId(),
            'atualizadoEm' => $areaComum->getAtualizadoEm()
        ];
        return $this->json($resposta);
    }

    /**
     * @Route("/area-comum/{id}", name="buscarPorId", methods={"GET"})
     */
    public function buscarPorId($id, Request $request)
    {
        $this->verificarAcesso($request);
        $areaComum = $this->areaComumService->buscarPorId($id);
        return $this->json($areaComum->toArray());
    }

    /**
     * @Route("/areas-comuns/{id}", name="buscarPorCondominio", methods={"GET"})
     */
    public function buscarPorCondominio(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $areasComuns = $this->areaComumService->buscarPorCondominio($id);

        $areas = [];

        foreach ($areasComuns as $area) {
            $areas[] = $area->toArray();
        }

        return $this->json($areas);
    }
}
