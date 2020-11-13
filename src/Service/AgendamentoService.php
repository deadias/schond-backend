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

    /**
     * Adicionar um novo agendamento
     * @var array $dados
     */
    public function adicionar(array $dados)
    {
        $condominioMorador = $this->condominioMoradorService->buscarPorId($dados['condominioMoradorId']);
        $areaComum = $this->areaComumService->buscarPorId($dados['areaComumId']);

        //Verificando se o morador já possui agendamentos para área comum
        $agendamento = $this->agendamentoRepository->consultarAgendamento(
            $condominioMorador, $areaComum, $dados['data']
        );

        if (!is_null($agendamento)) {
            throw new AgendamentoException(
                'Já existe um agendamento para a UH na área comum e período: ' . $agendamento->getData()->format('d/m/Y H:i:s')
            );
        }

        //Consultando o total de pessoas jã agendadas para a área comum e data
        $totalAgendados = $this->agendamentoRepository->getLotacaoPorCondominioAreaData(
            $condominioMorador->getCondominio(), $areaComum, $dados['data'], $dados['duracao']
        );

        $total = $totalAgendados + $dados['total_pessoas'];

        //Se o total já agendado mais o total do agendamento que está sendo feito ultrapassar
        //a lmitição da área, uma exceção será lançada 
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

    /**
     * Cancelar agendamento
     * @var int $id
     */
    public function cancelar(int $id)
    {
        $agendamento = $this->agendamentoRepository->buscarPorId($id);
        $agendamento->setAtivo(false);
        $agendamento->setAtualizadoEm(new \DateTime());
        return $this->agendamentoRepository->salvar($agendamento);
    }

    /**
     * Buscar agendamento por id
     * @var int $id
     */
    public function buscarPorId(int $id)
    {
        $agendamento = $this->agendamentoRepository->buscarPorId($id);
        if (is_null($agendamento)) {
            throw new EntityNotFoundException();
        }
        return $agendamento;
    }

    /**
     * Buscar agendamento por área comum
     * @var int $id
     */
    public function buscarPorAreaComum($id)
    {
        return $this->agendamentoRepository->buscarPorAreaComum($id);
    }

    /**
     * Buscar agendamento por condomínio e morador
     * @var int $condominioId
     * @var int $moradorId
     */
    public function buscarPorCondominioMorador($condominioId, $moradorId)
    {
        return$this->agendamentoRepository->buscarPorCondominioMorador($condominioId, $moradorId);
    }
}
