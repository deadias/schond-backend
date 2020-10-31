<?php
namespace Schond\Service;

use Doctrine\ORM\EntityNotFoundException;
use http\Exception\InvalidArgumentException;
use Schond\Entity\Agendamento;
use Schond\Exception\AgendamentoException;
use Schond\Repository\AgendamentoRepository;

class AgendamentoService
{
    private $agendamentoRepository;

    private $condominioMoradorService;

    private $areaComumService;

    public function __construct(
        AgendamentoRepository $agendamentoRepository,
        CondominioMoradorService $condominioMoradorService,
        AreaComumService $areaComumService
    ) {
        $this->agendamentoRepository = $agendamentoRepository;
        $this->condominioMoradorService = $condominioMoradorService;
        $this->areaComumService = $areaComumService;
    }

    public function adicionar(array $dados)
    {
        $condominioMorador = $this->condominioMoradorService->buscarPorId($dados['condominioMoradorId']);
        $areaComum = $this->areaComumService->buscarPorId($dados['areaComumId']);

        $agendamento = $this->agendamentoRepository->consultarAgendamento(
            $condominioMorador, $areaComum, $dados['data']
        );

        if (!is_null($agendamento)) {
            throw new AgendamentoException(
                'Já existe um agendamento para a UH na área comum e período: ' . $agendamento->getData()->format('d/m/Y H:i:s')
            );
        }

        $totalAgendados = $this->agendamentoRepository->getLotacaoPorCondominioAreaData(
            $condominioMorador->getCondominio(), $areaComum, $dados['data'], $dados['duracao']
        );

        $total = $totalAgendados + $dados['total_pessoas'];

        if ($total > $areaComum->getLimitacao()) {
            throw new AgendamentoException(
                "Lotação excedida. A área já está reservada no período para {$totalAgendados} pessoas. " .
                "Máximo permitido: {$areaComum->getLimitacao()} pessoas"
            );
        }

        $data = \DateTime::createFromFormat('d/m/Y H:i:s', $dados['data']);

        $agendamento = new Agendamento();
        $agendamento->setData($data);
        $agendamento->setAreaComum($areaComum);
        $agendamento->setCondominioMorador($condominioMorador);
        $agendamento->setDuracao($dados['duracao']);
        $agendamento->setTotalPessoas($dados['total_pessoas']);
        $agendamento->setCriadoEm(new \DateTime());
        $agendamento->setAtualizadoEm(new \DateTime());
        $agendamento->setAtivo(true);

        return $this->agendamentoRepository->salvar($agendamento);
    }

    public function cancelar(int $id)
    {
        $agendamento = $this->agendamentoRepository->buscarPorId($id);
        $agendamento->setAtivo(false);
        $agendamento->setAtualizadoEm(new \DateTime());
        return $this->agendamentoRepository->salvar($agendamento);
    }

    public function buscarPorId(int $id)
    {
        $agendamento = $this->agendamentoRepository->buscarPorId($id);
        if (is_null($agendamento)) {
            throw new EntityNotFoundException();
        }
        return $agendamento;
    }

    public function buscarPorAreaComum($id)
    {
        return $this->agendamentoRepository->buscarPorAreaComum($id);
    }

    public function buscarPorCondominioMorador($condominioId, $moradorId)
    {
        return$this->agendamentoRepository->buscarPorCondominioMorador($condominioId, $moradorId);
    }
}
