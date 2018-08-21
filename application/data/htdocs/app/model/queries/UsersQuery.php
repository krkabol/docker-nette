<?php
namespace App\Model\Entities;

use Doctrine\ORM\Query;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class UsersQuery extends QueryObject
{
    /**
     * @var array|\Closure[]
     */
    private $filter = [];

    /**
     * @var array|\Closure[]
     */
    private $select = [];

    public function notDeleted()
    {
        $this->filter[] = function (QueryBuilder $qb) {
            $qb->andWhere($qb->expr()->eq('u.deleted', ':key'))
                ->setParameter('key', FALSE);
        };
        return $this;
    }

    public function inRole(UsersRole $value)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($value) {
            $qb->andWhere($qb->expr()->eq('u.role', ':key'))
                ->setParameter('key', $value);
        };
        return $this;
    }

    public function byFulltext($text)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($text) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.email', '?1'),
                    $qb->expr()->like('u.surname', '?1')
                )
            )
                ->orderBy('u.email')
                ->setParameter(1, "%" . $text . "%");
        };
        return $this;
    }

    /**
     * @param Queryable $repository
     * @return Query|QueryBuilder
     */
    protected function doCreateQuery(Queryable $repository)
    {
        $qb = $this->createBasicDql($repository);

        foreach ($this->select as $modifier) {
            $modifier($qb);
        }
        return $qb->addOrderBy('u.surname', 'ASC');
    }

    private function createBasicDql(Queryable $repository)
    {
        $qb = $repository->createQueryBuilder()
            ->select('u')->from(User::class, 'u');

        foreach ($this->filter as $modifier) {
            $modifier($qb);
        }

        return $qb;
    }

    protected function doCreateCountQuery(Queryable $repository)
    {
        return $this->createBasicDql($repository)->select('COUNT(u.id)');
    }
}