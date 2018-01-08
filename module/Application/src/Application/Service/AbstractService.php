<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class AbstractService
 * 
 * @package Application\Service
 */
abstract class AbstractService{

	protected $manager;
	protected $entity;
	
	/**
	 *	@param EntityManager $manager
	 */
	public function __construct(EntityManager $manager){
		$this->manager = $manager;
	}

	/**
	 * @param array $data
	 * 
	 * @return object $entity
	 */
	public function save(Array $data = array()){
		if(isset($data['id'])){
			$entity = $this->manager->getReference($this->entity, $data['id']);

			$hydrator = new ClassMethods();
			$hydrator->hydrate($data, $entity);
		}else{
			$entity = new $this->entity($data);
		}

		$this->manager->persist($entity);
		$this->manager->flush();

		return $entity;
	}

	/**
	 * @param array $data
	 * 
	 * @return object $entity
	 */
	public function remove(Array $data = array()){
		$entity = $this->manager->getRepository($this->entity)->findOneBy($data);

		if($entity){
			$this->manager->remove($entity);
			$this->manager->flush();

			return $entity;
		}
	}
}