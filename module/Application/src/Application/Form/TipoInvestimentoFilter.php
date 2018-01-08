<?php
/**
 * Created by PhpStorm.
 * User: deyvi
 * Date: 26/12/2017
 * Time: 22:13
 */

namespace Application\Form;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class TipoInvestimentoFilter extends InputFilter{

    public function __construct(){
        $descricao = new Input('descricao');
        $descricao->setRequired(true)
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $descricao->getValidatorChain()->attach(new NotEmpty());
        $this->add($descricao);
    }
}