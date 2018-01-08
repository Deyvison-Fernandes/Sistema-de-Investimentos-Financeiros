<?php
namespace Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Form\Element\Button;
use Zend\Form\Element\Date;
use Zend\Form\Element\DateTime;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class InvestimentoForm extends Form implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);

        parent::__construct(null);
        $this->setAttribute('method', 'POST');

        $tipoAplicacao = new ObjectSelect('tipo_investimento');
        $tipoAplicacao->setLabel('Tipo de Investimento')
            ->setOptions(array(
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Application\Entity\TipoInvestimento',
                'property' => 'descricao',
                'empty_option' => 'Selecione',
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

        $data = new Text('aplicacao', array('add-on-prepend' => 'R$'));
        $data->setLabel('Aplicação')
            ->setAttributes(array(
                'id' => 'aplicacao'
            ));
        $this->add($data);

        $data = new Text('data');
        $data->setLabel('Data')
            ->setAttributes(array(
                'maxlength' => 10,
                'id' => 'data'
            ));
        $this->add($data);

        $button = new Button('submit');
        $button->setLabel('Salvar')
            ->setAttributes(array(
                'type' => 'submit',
                'class' => 'btn  btn-success'
            ));
        $this->add($button);

        $this->setInputFilter(new InvestimentoFilter($tipoAplicacao->getValueOptions()));
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