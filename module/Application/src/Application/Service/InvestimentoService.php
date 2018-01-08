<?php
namespace Application\Service;
use Doctrine\ORM\EntityManager;

class InvestimentoService extends AbstractService{

    public function __construct(EntityManager $manager)
    {
        $this->entity = 'Application\Entity\Investimento';
        parent::__construct($manager);
    }

    public function save(Array $data = array())
    {
        $tipoInvestimento = $this->manager
            ->getRepository('Application\Entity\TipoInvestimento')
            ->find($data['tipo_investimento']);
        $data['tipo_investimento'] = $tipoInvestimento;


       /* echo '<pre>';
        var_dump($data);die;*/

        return parent::save($data);
    }
}