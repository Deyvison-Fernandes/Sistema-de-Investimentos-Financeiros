<?php
namespace Application\Form;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class TipoInvestimentoListaForm extends Form {

    public function __construct()
    {

        parent::__construct(null);
        $this->setAttribute('method', 'GET');
        $this->setInputFilter(new TipoInvestimentoListarFilter());

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
}