<?php
namespace Application\Form;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class TipoInvestimentoForm extends Form{
    public function __construct()
    {
        parent::__construct(null);
        $this->setAttribute('method', 'POST');
        $this->setInputFilter(new TipoInvestimentoFilter());

        $descricao = new Text('descricao');
        $descricao->setLabel('Descrição')
            ->setAttributes(array(
                'maxlength' => 100
            ));
        $this->add($descricao);

        $rentabilidade = new Text('rentabilidade', array('add-on-append' => '%'));
        $rentabilidade->setLabel('Rentabilidade')
            ->setAttributes(array(
                'maxlength' => 5,
                'id' => 'rentabilidade'
            ));
        $this->add($rentabilidade);

        $taxa = new Text('taxa', array('add-on-append' => '%'));
        $taxa->setLabel('Taxa')
            ->setAttributes(array(
                'maxlength' => 5,
                'id' => 'taxa'
            ));
        $this->add($taxa);

        $periodoAplicacao = new Text('periodo_aplicacao', array('add-on-append' => 'Dias'));
        $periodoAplicacao->setLabel('Período da Aplicação')
            ->setAttributes(array(
                'maxlength' => 3
            ));
        $this->add($periodoAplicacao);

        $button = new Button('submit');
        $button->setLabel('Salvar')
            ->setAttributes(array(
                'type' => 'submit',
                'class' => 'btn  btn-success'
            ));
        $this->add($button);
    }
}