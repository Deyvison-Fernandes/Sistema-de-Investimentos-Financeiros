<?php
namespace Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class InvestimentoListaForm extends Form implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);

        parent::__construct(null);
        $this->setAttribute('method', 'GET');
        $this->setInputFilter(new InvestimentoListarFilter());

        $tipoAplicacao = new ObjectSelect('tipo_investimento');
        $tipoAplicacao->setLabel('Tipo de Investimento')
            ->setOptions(array(
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Application\Entity\TipoInvestimento',
                'property' => 'descricao',
                'empty_option' => 'Tipo de Investimento',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('descricao' => 'ASC')
                    )
                )
            ));

        $this->add($tipoAplicacao);

        $dataInicial = new Text('data_inicial');
        $dataInicial->setLabel('Data Inicial')
            ->setAttributes(array(
                'maxlength' => 10,
                'id' => 'data_inicial',
                'placeholder' => 'Data Inicial'
            ));
        $this->add($dataInicial);

        $dataFim = new Text('data_final');
        $dataFim->setLabel('Data Final')
            ->setAttributes(array(
                'maxlength' => 10,
                'id' => 'data_final',
                'placeholder' => 'Data Final'
            ));
        $this->add($dataFim);

        $button = new Button('submit');
        $button->setLabel('Filtrar')
            ->setAttributes(array(
                'type' => 'submit',
                'class' => 'btn  btn-default'
            ));
        $this->add($button);
    }

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}