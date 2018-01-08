<?php
/**
 * Created by PhpStorm.
 * User: deyvi
 * Date: 26/12/2017
 * Time: 22:13
 */

namespace Application\Form;

use Zend\Filter\Digits;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Date;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class InvestimentoFilter extends InputFilter{

    public function __construct(Array $tipoInvestimento = array()){
        $aplicacao = new Input('aplicacao');
        $aplicacao->setRequired(true)
            ->getFilterChain()
            ->attach(new Digits());
        $aplicacao->getValidatorChain()->attach(new NotEmpty());
        $this->add($aplicacao);

        $this->add ( array (
                'name' => 'data',
                'required' => 'true',
                'validators' => array (
                    array (
                        'name' => 'date',
                        'options' => array (
                            'format' => "d/m/Y"
                        )
                    )
                )
            )
        );

        $inArray = new InArray();
        $inArray->setOptions(array('haystack' => $this->haystack($tipoInvestimento)));

        $selectTipoInvestimento = new Input('tipo_investimento');
        $selectTipoInvestimento->setRequired(true)
            ->getFilterChain()->attach(new StripTags())->attach(new StringTrim());
        $selectTipoInvestimento->getValidatorChain()->attach($inArray);
        $this->add($selectTipoInvestimento);
    }

    public function haystack(Array $haystack = array()){

        if(empty($haystack))
            return array();

        $lista = array();
        foreach ($haystack as $valor){
            $lista[$valor['value']] = $valor['label'];
        }

        return array_keys($lista);
    }
}