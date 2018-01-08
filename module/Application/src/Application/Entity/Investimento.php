<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Investimento
 *
 * @ORM\Table(name="investimento", indexes={@ORM\Index(name="FK_investimento_tipo_investimento", columns={"tipo_investimento_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Entity\InvestimentoRepository")
 */
class Investimento  extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="aplicacao", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $aplicacao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="date", nullable=false)
     */
    private $data;

    /**
     * @var \Application\Entity\TipoInvestimento
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\TipoInvestimento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_investimento_id", referencedColumnName="id")
     * })
     */
    private $tipoInvestimento;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set aplicacao
     *
     * @param string $aplicacao
     *
     * @return Investimento
     */
    public function setAplicacao($aplicacao)
    {
        if(!is_numeric($aplicacao))
            $aplicacao = str_replace(',', '.',str_replace('.', '', $aplicacao));

        $this->aplicacao = $aplicacao;

        return $this;
    }

    /**
     * Get aplicacao
     *
     * @return string
     */
    public function getAplicacao()
    {
        return $this->aplicacao;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     * @ORM\PrePersist
     * @return Investimento
     */
    public function setData($data)
    {
        if(is_string($data))
            $data = $this->convertStringToDateTime($data);

        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set tipoInvestimento
     *
     * @param \Application\Entity\TipoInvestimento $tipoInvestimento
     *
     * @return Investimento
     */
    public function setTipoInvestimento(\Application\Entity\TipoInvestimento $tipoInvestimento = null)
    {
        $this->tipoInvestimento = $tipoInvestimento;

        return $this;
    }

    /**
     * Get tipoInvestimento
     *
     * @return \Application\Entity\TipoInvestimento
     */
    public function getTipoInvestimento()
    {
        return $this->tipoInvestimento;
    }

    /**
     * convertStringToDateTime converte uma string em um objeto DateTime
     *
     * @return \DateTime
     */
    private function convertStringToDateTime($dateStr){
        $dateStr = strtotime(str_replace('/', '-', $dateStr));

        $date = new \DateTime();
        $date->setTimestamp($dateStr);

        return $date;
    }

    public static function calcularPeriodoTotal($data){
        $dataAtual = new \DateTime();
        $diff = $data->diff($dataAtual);

        if($diff->invert == 1)
            return 0;

        return $diff->days;
    }

    public static function calcularRentabilidade(Investimento $investimento){
        $totalDias = self::calcularPeriodoTotal($investimento->getData());

        if($totalDias > 0){
            $periodo = $investimento->getTipoInvestimento()->getPeriodoAplicacao();
            $multiplicador = intval($totalDias / $periodo);

            if($multiplicador > 0) {
                $rentabilidade = $investimento->getTipoInvestimento()->getRentabilidade();
                $rentabilidade = $investimento->getAplicacao() / 100 * $rentabilidade;

                $taxa = $investimento->getTipoInvestimento()->getTaxa();
                $taxa = $investimento->getAplicacao() / 100 * $taxa;

                $total = ($rentabilidade - $taxa) * $multiplicador;

                return $total;
            }
        }

        return 0;
    }
}
