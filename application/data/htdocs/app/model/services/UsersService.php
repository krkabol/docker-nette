<?php

namespace App\Model\Services;

use App\Model\Authenticator;
use App\Model\Entities\User;
use App\Model\Entities\UsersQuery;
use Nette\Neon\Exception;

class UsersService extends BaseService
{

    protected $entityClass=User::class;

    public function findFulltext($text)
    {
        $query = $this->createQuery()
            ->notDeleted()
            ->byFulltext($text);
        return $this->repo->fetch($query);
    }

    public function findAllSorted()
    {
        $query = $this->createQuery();
        return $this->repo->fetch($query);
    }

    public function createUser($formData)
    {
        $this->onlyAdmin();
        $role = $this->getRepo()->related('role')->find($formData['role']);
        $i = (new User())->setEmail($formData['email'])
            ->setPassword(Authenticator::calculateHash())
            ->setName($formData['name'])
            ->setRole($role);
        $this->em->persist($i);
        $this->em->flush();
        return $i;
    }
    public function updateUser($formData)
    {
        $this->onlyAdmin();
        $i = $this->getRepo()->find($formData['id']);
        if (NULL === $i) {
            throw new Exception('Neznámé ID');
        }
        $i->setEmail($formData['email'])
            ->setName($formData['name'])
            ->setSurname($formData['surname']);
        $this->em->flush();
        return $i;
    }

    public function deleteUser($userId)
    {
        $i = $this->getRepo()->find($userId);
        $name = $i->getFullname();
        $this->em->remove($i);
        $this->em->flush();
        return $name;
    }

    public function changeRole($user_id, $role_id){
        $this->onlyAdmin();
        $role=$this->getRepo()->related('role')->find($role_id);
        $user=$this->getRepo()->find($user_id);
        if($this->user->getId()==$user_id){
            throw new Exception('Nemůžete měnit svou vlastní roli.');
        }
        $user->setRole($role);
        $this->em->flush();
        return $user;
    }
    public function resetPassword($user_id){
        $this->onlyAdmin();
        $user=$this->getRepo()->find($user_id)->setPassword(Authenticator::calculateHash());
        $this->em->flush();
        return  $user;
    }

    /** @return UsersQuery  */
    protected function createQuery(): UsersQuery
    {
        return new UsersQuery();
    }
}