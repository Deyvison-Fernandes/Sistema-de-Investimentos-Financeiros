<?php
namespace Application\Controller;

use Application\Entity\TipoInvestimentoRepository;
use Application\Form\TipoInvestimentoListaForm;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class TipoInvestimentoController extends AbstractController {

    public function __construct(){
        $this->form = 'Application\Form\TipoInvestimentoForm';
        $this->controller = 'TipoInvestimento';
        $this->route = 'tipo-investimento';
        $this->service = 'Application\Service\TipoInvestimentoService';
        $this->entity = 'Application\Entity\TipoInvestimento';
    }

    /**
     * Lista dos Resultados
     *
     * @return array|ViewModel
     */
    public function indexAction(){
        $form = new TipoInvestimentoListaForm();

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

                $lista = $lista->listarTipos($dataInicial, $dataFinal);
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
}