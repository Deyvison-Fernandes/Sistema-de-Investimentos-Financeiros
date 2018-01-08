<?php
namespace Application\Service;


use Doctrine\ORM\EntityManager;

class TipoInvestimentoService extends AbstractService{

    public function __construct(EntityManager $manager)
    {
        $this->entity = 'Application\Entity\TipoInvestimento';
        parent::__construct($manager);
    }
}