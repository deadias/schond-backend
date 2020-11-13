<?php

namespace Schond\Repository;

use Schond\Entity\Agendamento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Schond\Entity\AreaComum;
use Schond\Entity\Condominio;
use Schond\Entity\CondominioMorador;

class AgendamentoRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agendamento::class);
    }

    /**
     * Consultar se o morador já tem algum agendamento em um intervalo de até 1 hora 
     * antes ou 1 hora depois na área comum e no horário desejados
     */
    public function consultarAgendamento(CondominioMorador $condominioMorador, AreaComum $areaComum, string $dataHora)
    {
        $qb = $this->_em->createQueryBuilder();
        $expr = $qb->expr();

        //Aplicando uma hora a menos e uma hora a mais
        $dataInicio = \DateTime::createFromFormat('d/m/Y H:i:s', $dataHora)->modify('-60 minutes');
        $dataFim = \DateTime::createFromFormat('d/m/Y H:i:s', $dataHora)->modify('+60 minutes');

        return $qb->select('a')
            ->from(Agendamento::class, 'a')
            ->join('a.condominioMorador', 'cm')
            ->where(
                $expr->andX(
                    $expr->eq('cm.unidadeHabitacional', ':uhab'),
                    $expr->eq('a.areaComum', ':areaId'),
                    $expr->between('a.data', ':inicio', ':fim')
                )
            )
            ->setParameter('uhab', $condominioMorador->getUnidadeHabitacional())
            ->setParameter('areaId', $areaComum->getId())
            ->setParameter('inicio', $dataInicio)
            ->setParameter('fim', $dataFim)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * Consultar o total de pessoas agendadas para a área comum e período
     * @var Condominio $condominio
     * @var AreaComum $areaComum
     * @var string $dataHora
     * @var int $duracao
     */
    public function getLotacaoPorCondominioAreaData(Condominio $condominio, AreaComum $areaComum, string $dataHora, int $duracao)
    {
        $dataFormatada = \DateTime::createFromFormat('d/m/Y H:i:s', $dataHora)->format('Y-m-d H:i:s');

        $sql = "SELECT sum(total_pessoas) as total FROM agendamento a
                JOIN condominio_morador cm ON cm.id = a.condominio_morador_id
                WHERE cm.condominio_id = ? AND a.area_comum_id = ? AND
                (? BETWEEN a.data AND DATE_ADD(a.data, INTERVAL a.duracao MINUTE)
                OR DATE_ADD(?, INTERVAL ? MINUTE) BETWEEN a.data AND DATE_ADD(a.data, INTERVAL a.duracao MINUTE));";

        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $condominio->getId());
        $stmt->bindValue(2, $areaComum->getId());
        $stmt->bindValue(3, $dataFormatada);
        $stmt->bindValue(4, $dataFormatada);
        $stmt->bindValue(5, $duracao);
        $stmt->execute();
        return $stmt->fetchColumn(0);
    }

    /**
     * Buscar agendamento por área comum
     * @var int $id
     */
    public function buscarPorAreaComum($id)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('a')
            ->from(Agendamento::class, 'a')
            ->where('a.areaComum = :id')
            ->andWhere('a.ativo = :ativo')
            ->setParameter('id', $id)
            ->setParameter('ativo', true)
            ->orderBy('a.data')
            ->getQuery()
            ->getResult();
    }

    /**
     * Buscar agendamento por condomínio e morador
     * @var int $condominioId
     * @var int $moradorId
     */
    public function buscarPorCondominioMorador($condominioId, $moradorId)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('a')
            ->from(Agendamento::class, 'a')
            ->join('a.condominioMorador', 'cm')
            ->where('cm.condominio = :condominioId')
            ->andWhere('cm.morador = :moradorId')
            ->andWhere('a.ativo = :ativo')
            ->setParameter('condominioId', $condominioId)
            ->setParameter('moradorId', $moradorId)
            ->setParameter('ativo', true)
            ->orderBy('a.data')
            ->getQuery()
            ->getResult();
    }
}
