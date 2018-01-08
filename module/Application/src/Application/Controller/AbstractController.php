<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

abstract class AbstractController extends AbstractActionController{

	protected $manager;
	protected $entity;
	protected $controller;
	protected $route;
	protected $service;
	protected $form;

	abstract function __construct();

	/**
	 * Lista dos Resultados
	 * 
	 * @return array|ViewModel
	 */
	public function indexAction(){
		$lista = $this->getManager()->getRepository($this->entity)->findAll();

		$page = $this->params()->fromRoute('page');

		$paginator = new Paginator(new ArrayAdapter($lista));
		$paginator->setCurrentPageNumber($page)
				->setDefaultItemCountPerPage(15);

		$view = new ViewModel(array('data' => $paginator, 'page' => $page));
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

	/**
	 * Inserir Registro
	 */
	public function inserirAction(){
		if(is_string($this->form))
			$form = new $this->form;
		else
			$form = $this->form;

		$request = $this->getRequest();

		if($request->isPost()){
			$form->setData($request->getPost());

			if($form->isValid()){
				$service = $this->getServiceLocator()->get($this->service);

				if($service->save($request->getPost()->toArray()))
				    $this->flashMessenger()->addSuccessMessage('Cadastrado com Sucesso!');
				else
				    $this->flashMessenger()->addErrorMessage('Ocorreu um erro ao cadastrar!');

                return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
			}
		}

        $view = new ViewModel(array('form' => $form));

		return $view;
	}

	/**
	 * Editar Registro
	 */
	public function editarAction(){
        if(is_string($this->form))
            $form = new $this->form;
        else
            $form = $this->form;

        $request = $this->getRequest();
        $param = $this->params()->fromRoute('id', 0);

        $repository = $this->getManager()->getRepository($this->entity)->find($param);
        if($repository){
            $data = array();
            foreach ($repository->toArray() as $key => $value){
                if($value instanceof \DateTime)
                    $data[$key] = $value->format('d/m/Y');
                else
                    $data[$key] = $value;
            }

            $form->setData($data);

            if($request->isPost()) {
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $service = $this->getServiceLocator()->get($this->service);

                    $data = $request->getPost()->toArray();
                    $data['id'] = (int) $param;

                    if ($service->save($data))
                        $this->flashMessenger()->addSuccessMessage('Atualizado com Sucesso!');
                    else
                        $this->flashMessenger()->addErrorMessage('Ocorreu um erro ao atualizadar!');

                    return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
                }
            }
        }else{
            $this->flashMessenger()->addInfoMessage('Registro não encontrado!');
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }

        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('Application/'.$this->route.'/inserir');

        return $view;
	}

	/**
	 * Excluir Registro
	 */
	public function excluirAction(){

	    $service = $this->getServiceLocator()->get($this->service);
	    $id = $this->params()->fromRoute('id',0);

	    if($service->remove(array('id' => $id)))
	        $this->flashMessenger()->addSuccessMessage('Registro excluído com sucesso!');
	    else
	        $this->flashMessenger()->addErrorMessage('Não foi possível deletar o registro!');

	    return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
	}

	/**
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getManager(){
		if($this->manager == null){
			$this->manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}

		return $this->manager;
	}
}
