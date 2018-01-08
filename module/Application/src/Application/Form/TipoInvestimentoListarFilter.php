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

class TipoInvestimentoListarFilter extends InputFilter{

    public function __construct(){
        $this->add ( array (
                'name' => 'data_inicial',
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
        $this->add ( array (
                'name' => 'data_final',
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
    }
}