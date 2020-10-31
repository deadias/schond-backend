<?php

namespace Schond\Controller;

use Schond\Service\AgendamentoService;
use Schond\Service\AutenticadorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="agendamento_")
 */
class AgendamentoController extends ControllerBasico
{
    private $agendamentoService;

    public function __construct(AutenticadorService $autenticadorService, AgendamentoService $agendamentoService)
    {
        parent::__construct($autenticadorService);
        $this->agendamentoService = $agendamentoService;
    }

    /**
     * @Route("/agendamento", name="adicionar", methods={"POST"})
     */
    public function adicionar(Request $request)
    {
        $this->verificarAcesso($request);
        $dados = $request->request->all();
        $agendamento = $this->agendamentoService->adicionar($dados);
        return $this->json($agendamento->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/agendamento/{id}", name="cancelar", methods={"DELETE"})
     */
    public function cancelar(int $id, Request $request)
    {
        $this->verificarAcesso($request);
        $agendamento = $this->agendamentoService->cancelar($id);
        $resposta = [
            'Agendamento cancelado',
            'id' => $agendamento->getId(),
            'atualizadoEm' => $agendamento->getAtualizadoEm()
        ];
        return $this->json($resposta);
    }

    /**
     * @Route("/agendamento/{id}", name="buscarPorId", methods={"GET"})
     */
    public function buscarPorId($id, Request $request)
    {
        $this->verificarAcesso($request);
        $agendamento = $this->agendamentoService->buscarPorId($id);
        return $this->json($agendamento->toArray());
    }

    /**
     * @Route("/agendamentos/{id}", name="buscarPorAreaComum", methods={"GET"})
     */
    public function buscarPorAreaComum($id, Request $request)
    {
        $this->verificarAcesso($request);
        $retorno = $this->agendamentoService->buscarPorAreaComum($id);

        $agendamentos = [];

        foreach ($retorno as $agendamento) {
            $agendamentos[] = $agendamento->toArray();
        }

        return $this->json($agendamentos);
    }

    /**
     * @Route("/agendamentos/{condominioId}/{moradorId}", name="buscarCondominioMorador", methods={"GET"})
     */
    public function buscarPorCondominioMorador($condominioId, $moradorId, Request $request)
    {
        $this->verificarAcesso($request);
        $retorno = $this->agendamentoService->buscarPorCondominioMorador($condominioId, $moradorId);

        $agendamentos = [];

        foreach ($retorno as $agendamento) {
            $agendamentos[] = $agendamento->toArray();
        }

        return $this->json($agendamentos);
    }
}
