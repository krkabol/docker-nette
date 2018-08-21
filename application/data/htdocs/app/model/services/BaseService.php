<?php
namespace App\Model\Services;

use Doctrine\ORM\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Neon\Exception;
use Nette\Security\User;
use Nette\SmartObject;

abstract class BaseService
{
    use SmartObject;
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repo;

    /** @var  User */
    protected $user;


    public function __construct(EntityManager $em, User $user)
    {
        $this->em = $em;
        $this->user=$user;
        $this->repo = $this->em->getRepository($this->entityClass);
    }

    /**
     * @return EntityRepository
     */
    public function getRepo()
    {
        return $this->repo;
    }

    protected function onlyAdmin(){
        if(!$this->user->isInRole('admin')){
            throw new Exception('Změny může provádět pouze admin.');
        }
    }

    public function find($id){
        return $this->repo->find($id);
    }

    public function delete($id){
        $this->onlyAdmin();
        $item=$this->getRepo()->find($id);
        $this->em->remove($item);
        $this->em->flush();
        return $item;
    }
}