<?php
namespace Application\Controller;

use Application\Form\InvestimentoForm;
use Application\Form\InvestimentoListaForm;
use Application\Form\TipoInvestimentoListaForm;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class InvestimentoController extends AbstractController {

    public function __construct(){
        $this->form = 'Application\Form\InvestimentoForm';
        $this->controller = 'Investimento';
        $this->route = 'investimento';
        $this->service = 'Application\Service\InvestimentoService';
        $this->entity = 'Application\Entity\Investimento';
    }

    /**
     * Lista dos Resultados
     *
     * @return array|ViewModel
     */
    public function indexAction(){
        $form = new InvestimentoListaForm($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));

        $lista = $this->getManager()->getRepository($this->entity);

        $request = $this->getRequest();
        if(count($this->params()->fromQuery()) > 0) {
            $form->setData($this->params()->fromQuery());
            if($form->isValid()){
                $data = $form->getData();

                $dataInicialTime = strtotime(str_replace('/', '-', $data['data_inicial']));
                $dataInicial = new \DateTime();
                $dataInicial->setTimestamp($dataInicialTime);

                $dataFinalTime = strtotime(str_replace('/', '-', $data['data_final']));
                $dataFinal = new \DateTime();
                $dataFinal->setTimestamp($dataFinalTime);

                $lista = $lista->listarInvestimentos($data['data_inicial'], $dataInicial, $dataFinal);
            }else{
                $lista = $lista->findAll();
            }
        }else{
            $lista = $lista->findAll();
        }


        $page = $this->params()->fromRoute('page');

        $paginator = new Paginator(new ArrayAdapter($lista));
        $paginator->setCurrentPageNumber($page)
            ->setDefaultItemCountPerPage(15);

        $view = new ViewModel(array('data' => $paginator, 'page' => $page, 'form' => $form));
        if($this->flashMessenger()->hasSuccessMessages()) {
            $view->setVariables(array(
                'success' => $this->flashMessenger()->getSuccessMessages()
            ));
        }elseif($this->flashMessenger()->hasErrorMessages()) {
            $view->setVariables(array(
                'error' => $this->flashMessenger()->getErrorMessages()
            ));
        }elseif($this->flashMessenger()->hasInfoMessages()) {
            $view->setVariables(array(
                'warning' => $this->flashMessenger()->getInfoMessages()
            ));
        }

        return $view;
    }

    public function inserirAction(){
        $this->form = new InvestimentoForm($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));

        return parent::inserirAction();
    }

    public function editarAction(){
        $this->form = new InvestimentoForm($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));

        return parent::editarAction();
    }
}