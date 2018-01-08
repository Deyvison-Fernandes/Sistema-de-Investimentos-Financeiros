<?php

namespace Application\Entity;

/**
 * InvestimentoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvestimentoRepository extends \Doctrine\ORM\EntityRepository
{
    public function listarInvestimentos($idTipoInvestimento, $dataInicial, $dataFinal){

        return $this->createQueryBuilder('tipo')
            ->andWhere('tipo.data BETWEEN :dataInicial AND :dataFinal')
            ->andWhere('tipo.tipoInvestimento = :tipoInvestimento')
            ->setParameters(array(
                'dataInicial' => $dataInicial->format('Y-m-d'),
                'dataFinal' => $dataFinal->format('Y-m-d'),
                'tipoInvestimento' => $idTipoInvestimento
            ))
            ->getQuery()
            ->execute();
    }
}
